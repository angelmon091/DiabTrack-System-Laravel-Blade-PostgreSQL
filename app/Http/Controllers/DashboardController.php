<?php

namespace App\Http\Controllers;

use App\Services\DashboardMetricsService;
use App\Models\PatientLink;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Clase DashboardController
 * 
 * Gestiona la visualización del panel principal del usuario, integrando 
 * las métricas de salud procesadas por el servicio correspondiente.
 */
class DashboardController extends Controller
{
    /**
     * Instancia del servicio de métricas.
     *
     * @var DashboardMetricsService
     */
    protected $metricsService;

    /**
     * Crea una nueva instancia del controlador.
     *
     * @param DashboardMetricsService $metricsService
     * @return void
     */
    public function __construct(DashboardMetricsService $metricsService)
    {
        $this->metricsService = $metricsService;
    }

    /**
     * Muestra el panel de control con analíticas y resumen para el usuario autenticado.
     * 
     * Verifica si el usuario tiene un perfil de paciente completado antes de 
     * renderizar la vista con las métricas de salud.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        $user = auth()->user();
        
        // Redirigir al proceso de configuración inicial si no tiene rol asignado
        if (!$user->hasCompletedOnboarding()) {
            return redirect()->route('onboarding.index');
        }

        // Redirigir al dashboard correcto según el rol
        if (!$user->isPatient() && $user->isCaregiver()) {
            return redirect()->route('caregiver.dashboard');
        }
        if (!$user->isPatient() && $user->isDoctor()) {
            return redirect()->route('doctor.dashboard');
        }

        // Si es paciente pero no tiene perfil aún
        if (!$user->patientProfile) {
            return redirect()->route('onboarding.index');
        }

        // Obtener los datos procesados a través de la capa de servicios (Service Layer)
        $metrics = $this->metricsService->getDashboardMetrics($user->id);

        // Obtener últimos 5 registros para llenar el espacio del dashboard
        $recentLogs = \App\Models\VitalSign::where('user_id', $user->id)
            ->whereNotNull('glucose_level')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', array_merge($metrics, compact('recentLogs')));
    }

    /**
     * Guarda el peso del usuario desde la tarjeta rápida del Dashboard.
     * Crea un registro mínimo de VitalSign con solo el peso.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeWeight(Request $request)
    {
        $request->validate([
            'weight' => ['required', 'numeric', 'min:20', 'max:350'],
        ]);

        \App\Models\VitalSign::create([
            'user_id' => auth()->id(),
            'weight' => $request->weight,
            'glucose_level' => null,
            'measurement_moment' => 'Ayunas',
        ]);

        // Actualizar también el perfil del paciente
        $profile = auth()->user()->patientProfile;
        if ($profile) {
            $profile->update(['weight' => $request->weight]);
        }

        return redirect()->route('dashboard')->with('status', __('Peso registrado correctamente.'));
    }

    /**
     * Muestra una previsualización detallada de todos los datos y métricas (Vista Resumen).
     *
     * @return \Illuminate\View\View
     */
    public function summary()
    {
        $user = auth()->user();
        $metrics = $this->metricsService->getDashboardMetrics($user->id);

        // Obtener registros históricos para la vista detallada
        $vitalsHistory = \App\Models\VitalSign::where('user_id', $user->id)
            ->latest()
            ->take(30)
            ->get();

        $nutritionHistory = \App\Models\NutritionLog::where('user_id', $user->id)
            ->latest()
            ->take(30)
            ->get();

        $activityHistory = \App\Models\ActivityLog::where('user_id', $user->id)
            ->latest()
            ->take(30)
            ->get();

        $symptomsHistory = \Illuminate\Support\Facades\DB::table('symptom_user')
            ->join('symptoms', 'symptom_user.symptom_id', '=', 'symptoms.id')
            ->where('symptom_user.user_id', $user->id)
            ->select('symptoms.name', 'symptoms.category', 'symptom_user.logged_at')
            ->latest('symptom_user.logged_at')
            ->take(50)
            ->get();

        // Métricas adicionales para el resumen profundo
        $extraMetrics = [
            'avgGlucose' => round($vitalsHistory->avg('glucose_level')),
            'avgSystolic' => round($vitalsHistory->avg('systolic')),
            'avgDiastolic' => round($vitalsHistory->avg('diastolic')),
            'avgHeartRate' => round($vitalsHistory->avg('heart_rate')),
            'totalWeight' => $user->patientProfile->weight ?? '--',
            'weightCount' => $vitalsHistory->whereNotNull('weight')->count(),
            'symptomsCount' => $symptomsHistory->count(),
            'medicationCount' => $nutritionHistory->whereNotNull('medication_taken')->count(),
            'totalActivityMinutes' => $activityHistory->sum('duration_minutes'),
        ];

        // Preparar datos para gráfica de categorías de comida
        $foodCategoryCounts = [];
        foreach ($nutritionHistory as $log) {
            if ($log->food_categories) {
                foreach ($log->food_categories as $cat) {
                    $foodCategoryCounts[$cat] = ($foodCategoryCounts[$cat] ?? 0) + 1;
                }
            }
        }
        $extraMetrics['foodCategoryLabels'] = array_keys($foodCategoryCounts);
        $extraMetrics['foodCategoryData'] = array_values($foodCategoryCounts);

        return view('tracking.summary', array_merge($metrics, $extraMetrics, compact(
            'vitalsHistory', 'nutritionHistory', 'activityHistory', 'symptomsHistory'
        )));
    }

    /**
     * Genera un código de invitación para vincular un cuidador o médico.
     */
    public function generateInviteCode(Request $request)
    {
        $user = auth()->user();

        // Eliminar códigos previos pendientes del usuario
        PatientLink::where('patient_id', $user->id)
            ->where('status', 'pending')
            ->delete();

        $code = strtoupper(Str::random(6));

        PatientLink::create([
            'patient_id' => $user->id,
            'role' => $request->input('role', 'caregiver'),
            'invite_code' => $code,
            'status' => 'pending',
            'expires_at' => now()->addHours(24),
        ]);

        return redirect()->route('dashboard')
            ->with('invite_code', $code)
            ->with('status', 'Código de invitación generado. Compártelo con tu cuidador o médico.');
    }
}
