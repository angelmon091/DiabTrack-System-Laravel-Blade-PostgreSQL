<?php

namespace App\Http\Controllers;

use App\Models\VitalSign;
use App\Models\ActivityLog;
use App\Models\NutritionLog;
use App\Models\Symptom;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        if (!auth()->user()->patientProfile) {
            return redirect()->route('onboarding.index');
        }

        $userId = Auth::id();
        $today = Carbon::today();

        // Última medición de signos vitales
        $ultimaMedicion = VitalSign::where('user_id', $userId)
            ->latest('created_at')
            ->first();

        // HbA1c: último registro
        $ultimaHba1c = VitalSign::where('user_id', $userId)
            ->whereNotNull('hba1c')
            ->latest('created_at')
            ->first();

        // Carbohidratos del día
        $carbsHoy = NutritionLog::where('user_id', $userId)
            ->whereDate('created_at', $today)
            ->sum('carbs_grams');

        // Calorías estimadas del día (carbohidratos * 4 kcal/g como base)
        $caloriasHoy = NutritionLog::where('user_id', $userId)
            ->whereDate('created_at', $today)
            ->sum('carbs_grams') * 4;

        // Meta de calorías y carbohidratos (valores por defecto ajustables)
        $metaCalorias = 2000;
        $metaCarbs = 200;

        // Actividad del día: duración total en minutos
        $actividadMinutos = ActivityLog::where('user_id', $userId)
            ->whereDate('created_at', $today)
            ->sum('duration_minutes');

        // Meta de actividad en minutos
        $metaActividad = 60;

        // Pasos estimados del día (basado en actividades tipo caminar: ~100 pasos/min)
        $pasosEstimados = ActivityLog::where('user_id', $userId)
            ->whereDate('created_at', $today)
            ->where('activity_type', 'caminar')
            ->sum('duration_minutes') * 100;

        $metaPasos = 8000;

        // Síntomas registrados hoy
        $sintomasHoy = \DB::table('symptom_user')
            ->where('user_id', $userId)
            ->whereDate('logged_at', $today)
            ->count();

        // Porcentajes para barras de progreso
        $porcentajeCalorias = $metaCalorias > 0 ? min(round(($caloriasHoy / $metaCalorias) * 100), 100) : 0;
        $porcentajeActividad = $metaActividad > 0 ? min(round(($actividadMinutos / $metaActividad) * 100), 100) : 0;
        $porcentajePasos = $metaPasos > 0 ? min(round(($pasosEstimados / $metaPasos) * 100), 100) : 0;

        // Tiempo en rango (últimos 7 días): mediciones con glucosa entre 70 y 140
        $medicionesRecientes = VitalSign::where('user_id', $userId)
            ->whereNotNull('glucose_level')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->count();

        $medicionesEnRango = VitalSign::where('user_id', $userId)
            ->whereNotNull('glucose_level')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->whereBetween('glucose_level', [70, 140])
            ->count();

        $tiempoEnRango = $medicionesRecientes > 0 ? round(($medicionesEnRango / $medicionesRecientes) * 100) : 0;

        // Datos para gráfica de glucosa semanal (Chart.js)
        $glucosaLabels = [];
        $glucosaData = [];

        for ($i = 6; $i >= 0; $i--) {
            $day = Carbon::today()->subDays($i);
            $glucosaLabels[] = $day->isoFormat('ddd D');

            $avgGlucose = VitalSign::where('user_id', $userId)
                ->whereNotNull('glucose_level')
                ->whereDate('created_at', $day)
                ->avg('glucose_level');

            $glucosaData[] = $avgGlucose ? round($avgGlucose) : null;
        }

        // Tip del Día Dinámico
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
        
        // Seleccionamos un tip usando el día del año para que cambie cada 24 horas automáticamente.
        $tipDelDia = $tips[date('z') % count($tips)];

        return view('dashboard', compact(
            'ultimaMedicion',
            'ultimaHba1c',
            'carbsHoy',
            'caloriasHoy',
            'metaCalorias',
            'metaCarbs',
            'actividadMinutos',
            'metaActividad',
            'pasosEstimados',
            'metaPasos',
            'sintomasHoy',
            'porcentajeCalorias',
            'porcentajeActividad',
            'porcentajePasos',
            'tiempoEnRango',
            'glucosaLabels',
            'glucosaData',
            'tipDelDia'
        ));
    }
}

