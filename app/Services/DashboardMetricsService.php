<?php

namespace App\Services;

use App\Models\VitalSign;
use App\Models\ActivityLog;
use App\Models\NutritionLog;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * Clase DashboardMetricsService
 * 
 * Se encarga de toda la lógica de cálculo y procesamiento de datos de salud 
 * para el panel principal (Dashboard). Centraliza las consultas a modelos de 
 * nutrición, actividad física y signos vitales.
 */
class DashboardMetricsService
{
    /**
     * Calcula y retorna todas las métricas necesarias para el panel principal del usuario.
     * 
     * Este método procesa:
     * - Última medición de glucosa y HbA1c.
     * - Ingesta calórica diaria basada en carbohidratos.
     * - Progreso de metas de actividad y pasos (cálculos en tiempo real).
     * - Estadísticas semanales para gráficas de tendencias.
     * - Cálculo del "Tiempo en Rango" de glucosa (70-140 mg/dL).
     *
     * @param int $userId ID del usuario autenticado.
     * @return array Conjunto de métricas procesadas.
     */
    public function getDashboardMetrics($userId)
    {
        return \Illuminate\Support\Facades\Cache::remember("dashboard_metrics_{$userId}", 300, function () use ($userId) {
            $today = Carbon::today();
            $user = \App\Models\User::with('patientProfile')->findOrFail($userId);
            $profile = $user->patientProfile;

            // 1. Signos Vitales (Glucosa y HbA1c)
            $ultimaMedicion = VitalSign::where('user_id', $userId)->latest('created_at')->first();
            $ultimaHba1c = VitalSign::where('user_id', $userId)->whereNotNull('hba1c')->latest('created_at')->first();

            // 2. Nutrición
            $carbsHoy = NutritionLog::where('user_id', $userId)->whereDate('created_at', $today)->sum('carbs_grams');
            $caloriasHoy = $carbsHoy * 4;
            
            $metaCalorias = 2000; 
            $metaCarbs = 200;
            $porcentajeCalorias = $metaCalorias > 0 ? min(round(($caloriasHoy / $metaCalorias) * 100), 100) : 0;

            // 3. Actividad
            $actividadMinutos = ActivityLog::where('user_id', $userId)->whereDate('created_at', $today)->sum('duration_minutes');
            $metaActividad = 60;
            $porcentajeActividad = $metaActividad > 0 ? min(round(($actividadMinutos / $metaActividad) * 100), 100) : 0;

            $pasosEstimados = ActivityLog::where('user_id', $userId)
                ->whereDate('created_at', $today)
                ->where('activity_type', 'caminar')
                ->sum('duration_minutes') * 100;
                
            $metaPasos = 8000;
            $porcentajePasos = $metaPasos > 0 ? min(round(($pasosEstimados / $metaPasos) * 100), 100) : 0;

            // 4. Síntomas registrados
            $sintomasHoy = DB::table('symptom_user')
                ->where('user_id', $userId)
                ->whereDate('logged_at', $today)
                ->count();

            // 5. Estadísticas Semanales de Glucosa y Rango
            $medicionesGlucosaSemana = VitalSign::where('user_id', $userId)
                ->whereNotNull('glucose_level')
                ->where('created_at', '>=', Carbon::now()->subDays(7))
                ->get();

            $medicionesRecientes = $medicionesGlucosaSemana->count();
            $minRango = $profile->target_glucose_min ?? 70;
            $maxRango = $profile->target_glucose_max ?? 140;

            $medicionesEnRango = $medicionesGlucosaSemana->filter(function ($item) use ($minRango, $maxRango) {
                return $item->glucose_level >= $minRango && $item->glucose_level <= $maxRango;
            })->count();
            
            $tiempoEnRango = $medicionesRecientes > 0 ? round(($medicionesEnRango / $medicionesRecientes) * 100) : 0;

            $registrosGlucosaAgrupados = $medicionesGlucosaSemana->groupBy(function($item) {
                return $item->created_at->toDateString();
            });

            $glucosaLabels = [];
            $glucosaData = [];

            for ($i = 6; $i >= 0; $i--) {
                $day = Carbon::today()->subDays($i);
                $dateString = $day->toDateString();
                
                $glucosaLabels[] = $day->isoFormat('ddd D');
                
                if ($registrosGlucosaAgrupados->has($dateString)) {
                    $avgGlucose = $registrosGlucosaAgrupados->get($dateString)->avg('glucose_level');
                    $glucosaData[] = round($avgGlucose);
                } else {
                    $glucosaData[] = null;
                }
            }

            // 6. Tip de Salud rotativo
            $tips = [
                "Mantener un horario regular de comidas ayuda a estabilizar tus niveles de glucosa durante el día.",
                "Beber al menos 2 litros de agua diarios mejora la circulación y reduce el riesgo de hiperglucemia.",
                "Caminar 15 minutos después de comer reduce significativamente los picos de azúcar en sangre.",
                "Revisa tus pies a diario y mantenlos hidratados para prevenir posibles complicaciones.",
                "Prioriza el consumo de proteínas y fibra en tus desayunos para evitar hipoglucemias reactivas.",
                "Lleva siempre contigo un carbohidrato de rápida absorción (jugo o caramelos) para emergencias.",
                "Dormir de 7 a 8 horas cada noche promueve una mejor sensibilidad a la insulina.",
                "Anotar lo que comes te ayudará a detectar patrones en cómo ciertos alimentos afectan tu glucosa.",
                "El estrés eleva el azúcar en sangre de forma natural. Prueba técnicas de respiración si te sientes tenso.",
                "Comer la ensalada o fibra antes de los carbohidratos ayuda a aplanar tu curva de glucosa."
            ];
            $tipDelDia = $tips[date('z') % count($tips)];

            // 7. Recordatorio Mensual de Peso
            $ultimoPesoRegistro = VitalSign::where('user_id', $userId)
                ->whereNotNull('weight')
                ->latest('created_at')
                ->first();

            $needsWeightUpdate = true;
            $ultimoPesoValor = null;

            if ($ultimoPesoRegistro) {
                $ultimoPesoValor = $ultimoPesoRegistro->weight;
                if ($ultimoPesoRegistro->created_at->diffInDays(Carbon::now()) < 30) {
                    $needsWeightUpdate = false;
                }
            }

            return compact(
                'ultimaMedicion', 'ultimaHba1c', 'carbsHoy', 'caloriasHoy',
                'metaCalorias', 'metaCarbs', 'actividadMinutos', 'metaActividad',
                'pasosEstimados', 'metaPasos', 'sintomasHoy', 'porcentajeCalorias',
                'porcentajeActividad', 'porcentajePasos', 'tiempoEnRango',
                'glucosaLabels', 'glucosaData', 'tipDelDia', 'needsWeightUpdate', 'ultimoPesoValor'
            );
        });
    }
}
