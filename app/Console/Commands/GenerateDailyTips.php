<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Models\User;
use App\Models\DailyTip;
use App\Models\VitalSign;
use App\Services\DailyTipSuggestionService;
use App\Services\DashboardMetricsService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

#[Signature('app:generate-daily-tips')]
#[Description('Genera tips de salud diarios personalizados para pacientes usando todos sus datos de salud disponibles')]
class GenerateDailyTips extends Command
{
    public function __construct(private readonly DailyTipSuggestionService $suggestionService)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $patients = User::whereHas('roles', function ($query) {
            $query->where('name', 'paciente');
        })->with('patientProfile')->get();

        if ($patients->isEmpty()) {
            $this->info('No se encontraron pacientes para generar tips.');
            return;
        }

        $geminiKey = config('services.gemini.key');
        $geminiModel = config('services.gemini.model', 'gemini-2.5-flash');
        $anthropicKey = config('services.anthropic.key');
        $anthropicModel = config('services.anthropic.model', 'claude-haiku-4-5');

        $maxInactivityDays = (int) env('DAILY_TIPS_MAX_INACTIVITY_DAYS', 3);

        foreach ($patients as $patient) {
            $this->info("Procesando paciente: {$patient->name} (ID: {$patient->id})");

            // Verificar si el paciente ha ingresado datos clínicos o de estilo de vida recientemente
            $since = Carbon::now()->subDays($maxInactivityDays);
            $hasRecentData = $patient->vitalSigns()->where('created_at', '>=', $since)->exists() ||
                             $patient->activityLogs()->where('created_at', '>=', $since)->exists() ||
                             $patient->nutritionLogs()->where('created_at', '>=', $since)->exists() ||
                             DB::table('symptom_user')->where('user_id', $patient->id)->where('logged_at', '>=', $since)->exists();

            if (!$hasRecentData) {
                $this->warn("  Paciente inactivo en los últimos {$maxInactivityDays} días. Omitiendo generación de tip.");
                continue;
            }

            $context = $this->buildPatientContext($patient);
            $tipGenerado = null;

            if ($geminiKey) {
                try {
                    $tipGenerado = $this->suggestionService->generateGemini($context, $geminiKey, $geminiModel);
                    $this->line("  Tip generado con Gemini: {$geminiModel}");
                } catch (\Throwable $e) {
                    $this->error("  Error con Gemini: " . $e->getMessage());
                }
            }

            if (!$tipGenerado && $anthropicKey) {
                try {
                    $tipGenerado = $this->suggestionService->generateAnthropic($context, $anthropicKey, $anthropicModel);
                    $this->line("  Tip generado con Anthropic: {$anthropicModel}");
                } catch (\Throwable $e) {
                    $this->error("  Error con Anthropic: " . $e->getMessage());
                }
            }

            if (!$tipGenerado) {
                $this->error("  No se pudo generar tip para el paciente {$patient->id} con ninguna IA configurada.");
                continue;
            }

            DailyTip::create([
                'user_id'  => $patient->id,
                'tip_text' => $tipGenerado,
                'status'   => 'approved',
            ]);

            DashboardMetricsService::forgetUserCache($patient->id);

            $this->info("  ✓ Tip diario generado con éxito para el paciente {$patient->id}.");
        }

        $this->info('Proceso de generación de tips diarios completado.');
    }

    /**
     * Recopila todos los datos clínicos y de estilo de vida del paciente
     * disponibles en las últimas 48–72 horas para construir un contexto rico.
     */
    private function buildPatientContext(User $patient): array
    {
        $profile = $patient->patientProfile;
        $now     = Carbon::now();
        $today   = Carbon::today();
        $ayer    = $today->copy()->subDay();

        // ── 1. Perfil clínico base ──────────────────────────────────────────
        $edad = $profile?->birth_date
            ? Carbon::parse($profile->birth_date)->age
            : null;

        $imc = null;
        if ($profile?->weight && $profile?->height && $profile->height > 0) {
            $alturaM = $profile->height / 100;
            $imc     = round($profile->weight / ($alturaM * $alturaM), 1);
        }

        // ── 2. Signos Vitales (últimas 48h) ────────────────────────────────
        $vitals48h = $patient->vitalSigns()
            ->where('created_at', '>=', $now->copy()->subHours(48))
            ->get();

        $glucosaPromedio = $vitals48h->whereNotNull('glucose_level')
            ->where('glucose_level', '>', 0)
            ->avg('glucose_level');

        $glucosaMin = $vitals48h->whereNotNull('glucose_level')->where('glucose_level', '>', 0)->min('glucose_level');
        $glucosaMax = $vitals48h->whereNotNull('glucose_level')->where('glucose_level', '>', 0)->max('glucose_level');

        $ultimaPresion = $patient->vitalSigns()
            ->whereNotNull('systolic')->whereNotNull('diastolic')
            ->latest('id')->first();

        $ultimaFc = $patient->vitalSigns()
            ->whereNotNull('heart_rate')
            ->latest('id')->first();

        $ultimoNivelEstres = $patient->vitalSigns()
            ->whereNotNull('stress_level')
            ->latest('id')->first();

        // HbA1c más reciente (puede ser de hace semanas)
        $ultimaHba1c = $patient->vitalSigns()
            ->whereNotNull('hba1c')
            ->latest('id')->first();

        // Notas del último registro vital (pueden dar contexto cualitativo)
        $ultimaNotaVital = $patient->vitalSigns()
            ->whereNotNull('notes')
            ->where('notes', '!=', '')
            ->latest('id')->first();

        // Momento de medición más frecuente (ayunas, post-comida…)
        $momentoMedicion = $vitals48h
            ->whereNotNull('measurement_moment')
            ->groupBy('measurement_moment')
            ->map->count()
            ->sortDesc()
            ->keys()
            ->first();

        // ── 3. Actividad Física (ayer) ─────────────────────────────────────
        $actividadAyer = $patient->activityLogs()
            ->whereDate('created_at', $ayer)
            ->get();

        $minutosActividadAyer  = $actividadAyer->sum('duration_minutes');
        $tiposActividad        = $actividadAyer->pluck('activity_type')->filter()->unique()->values()->toArray();
        $intensidades          = $actividadAyer->pluck('intensity')->filter()->unique()->values()->toArray();
        $energiaPostActividad  = $actividadAyer->pluck('energy_level')->filter()->last();

        // ── 4. Nutrición (ayer) ────────────────────────────────────────────
        $nutricionAyer = $patient->nutritionLogs()
            ->whereDate('created_at', $ayer)
            ->get();

        $carbsAyer         = $nutricionAyer->sum('carbs_grams');
        $tiposComidaAyer   = $nutricionAyer->pluck('meal_type')->filter()->unique()->values()->toArray();
        $categoriaAlimentos = $nutricionAyer
            ->pluck('food_categories')
            ->filter()
            ->flatten()
            ->unique()
            ->values()
            ->toArray();

        // ── 5. Síntomas (últimas 48h) ──────────────────────────────────────
        $sintomasRecientes = DB::table('symptom_user')
            ->join('symptoms', 'symptom_user.symptom_id', '=', 'symptoms.id')
            ->where('symptom_user.user_id', $patient->id)
            ->where('symptom_user.logged_at', '>=', $now->copy()->subHours(48))
            ->pluck('symptoms.name')
            ->toArray();

        // ── 6. Historial de Tips ───────────────────────────────────────────
        $ultimoTip = DailyTip::where('user_id', $patient->id)
            ->latest('id')->first();

        $diasDesdeUltimoTip = $ultimoTip
            ? (int) $ultimoTip->created_at->diffInDays($now)
            : null;

        return [
            // Perfil clínico
            'nombre'               => $patient->name,
            'edad'                 => $edad,
            'genero'               => $profile?->gender,
            'tipo_diabetes'        => $profile?->diabetes_type,
            'imc'                  => $imc,
            'peso_kg'              => $profile?->weight,
            'rango_glucosa_min'    => $profile?->target_glucose_min ?? 70,
            'rango_glucosa_max'    => $profile?->target_glucose_max ?? 140,

            // Glucosa
            'glucosa_promedio_48h' => $glucosaPromedio !== null ? round((float) $glucosaPromedio, 1) : null,
            'glucosa_min_48h'      => $glucosaMin,
            'glucosa_max_48h'      => $glucosaMax,
            'momento_medicion'     => $momentoMedicion,

            // Otros signos vitales
            'presion_sistolica'    => $ultimaPresion?->systolic,
            'presion_diastolica'   => $ultimaPresion?->diastolic,
            'frecuencia_cardiaca'  => $ultimaFc?->heart_rate,
            'nivel_estres'         => $ultimoNivelEstres?->stress_level,
            'hba1c'                => $ultimaHba1c?->hba1c,
            'nota_vital'           => $ultimaNotaVital?->notes,

            // Actividad
            'minutos_actividad_ayer'  => (int) $minutosActividadAyer,
            'tipos_actividad_ayer'    => $tiposActividad,
            'intensidad_actividad'    => $intensidades,
            'energia_post_actividad'  => $energiaPostActividad,

            // Nutrición
            'carbs_ayer_gramos'       => (int) $carbsAyer,
            'tipos_comida_ayer'       => $tiposComidaAyer,
            'categorias_alimentos'    => $categoriaAlimentos,

            // Síntomas
            'sintomas_recientes'      => $sintomasRecientes,

            // Historial
            'dias_desde_ultimo_tip'   => $diasDesdeUltimoTip,
        ];
    }
}
