<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Models\User;
use App\Models\DailyTip;
use App\Services\DailyTipSuggestionService;
use Illuminate\Support\Facades\Log;

#[Signature('app:generate-daily-tips')]
#[Description('Genera tips de salud diarios para pacientes con Anthropic o un proveedor local de respaldo')]
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
        // 1. Obtener todos los pacientes activos (quienes tengan el rol 'paciente')
        $patients = User::whereHas('roles', function ($query) {
            $query->where('name', 'paciente');
        })->get();

        if ($patients->isEmpty()) {
            $this->info('No se encontraron pacientes para generar tips.');
            return;
        }

        $anthropicKey = config('services.anthropic.key');
        $anthropicModel = config('services.anthropic.model', 'claude-haiku-4-5');

        foreach ($patients as $patient) {
            $this->info("Procesando paciente: {$patient->name} (ID: {$patient->id})");

            // 2. Recopilar métricas de las últimas 48 horas
            $glucosaPromedio = $patient->vitalSigns()
                ->where('created_at', '>=', now()->subHours(48))
                ->avg('glucose_level');

            $carbsAyer = $patient->nutritionLogs()
                ->whereDate('created_at', today()->subDay())
                ->sum('carbs_grams');

            $actividadAyer = $patient->activityLogs()
                ->whereDate('created_at', today()->subDay())
                ->sum('duration_minutes');

            $tipContext = [
                'glucose_average' => $glucosaPromedio !== null ? (float) $glucosaPromedio : null,
                'carbs_yesterday' => (int) $carbsAyer,
                'activity_minutes_yesterday' => (int) $actividadAyer,
                'days_since_last_tip' => (int) (DailyTip::where('user_id', $patient->id)->latest('id')->first()?->created_at?->diffInDays(now()) ?? 0),
            ];

            if ($anthropicKey) {
                $tipGenerado = $this->suggestionService->generateAnthropic($tipContext, $anthropicKey, $anthropicModel);
                $this->line("Tip generado con Anthropic: {$anthropicModel}");
            } else {
                $this->error('ANTHROPIC_API_KEY no está configurada. No se generará tip local.');
                Log::warning("GenerateDailyTips: tip omitido para el paciente {$patient->id} por falta de Anthropic.");
                continue;
            }

            // 5. Guardar en Base de Datos como 'approved' (publicado automáticamente)
            DailyTip::create([
                'user_id' => $patient->id,
                'tip_text' => $tipGenerado,
                'status' => 'approved',
            ]);

            $this->info("Tip diario generado con éxito para el paciente {$patient->id}.");
        }

        $this->info('Proceso de generación de tips diarios completado.');
    }
}
