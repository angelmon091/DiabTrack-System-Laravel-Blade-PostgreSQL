<?php

namespace App\Http\Controllers;

use App\Models\PatientLink;
use App\Models\User;
use App\Models\VitalSign;
use App\Services\DashboardMetricsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * Dashboard y gestión de pacientes para cuidadores.
 */
class CaregiverController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $patients = $user->linkedPatients()->with('patientProfile', 'vitalSigns')->get();

        return view('caregiver.dashboard', compact('user', 'patients'));
    }

    /**
     * Muestra el formulario para vincular un paciente con código.
     */
    public function showLinkForm()
    {
        return view('caregiver.link-patient');
    }

    /**
     * Vincula un paciente usando su código de invitación.
     */
    public function linkPatient(Request $request)
    {
        $request->validate([
            'invite_code' => 'required|string|size:6',
        ]);

        $link = PatientLink::where('invite_code', strtoupper($request->invite_code))
            ->where('status', 'pending')
            ->where('expires_at', '>', now())
            ->first();

        if (!$link) {
            return back()->withErrors(['invite_code' => 'El código es inválido o ha expirado.']);
        }

        $link->update([
            'linked_user_id' => Auth::id(),
            'status' => 'active',
        ]);

        return redirect()->route('caregiver.dashboard')
            ->with('status', '¡Paciente vinculado exitosamente!');
    }
    /**
     * Muestra el detalle de un paciente vinculado.
     */
    public function showPatient(User $patient, DashboardMetricsService $metricsService)
    {
        $this->checkLink($patient->id);

        $metrics = $metricsService->getDashboardMetrics($patient->id);
        
        $recentLogs = VitalSign::where('user_id', $patient->id)
            ->whereNotNull('glucose_level')
            ->latest()
            ->take(5)
            ->get();

        return view('caregiver.patient-detail', array_merge($metrics, compact('patient', 'recentLogs')));
    }

    /**
     * Registra signos vitales para un paciente vinculado.
     */
    public function storeVital(Request $request, User $patient)
    {
        $this->checkLink($patient->id);

        $validated = $request->validate([
            'glucose_level' => 'required|numeric|min:20|max:600',
            'measurement_moment' => 'required|string',
            'stress_level' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        VitalSign::create([
            'user_id' => $patient->id,
            'glucose_level' => $validated['glucose_level'],
            'measurement_moment' => $validated['measurement_moment'],
            'stress_level' => $validated['stress_level'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('caregiver.patient.show', $patient)
            ->with('status', 'Registro de salud añadido correctamente.');
    }

    /**
     * Muestra el formulario premium para registrar signos vitales.
     */
    public function createVital(User $patient)
    {
        $this->checkLink($patient->id);
        return view('caregiver.tracking.vital-create', compact('patient'));
    }

    private function checkLink($patientId)
    {
        $isLinked = PatientLink::where('patient_id', $patientId)
            ->where('linked_user_id', Auth::id())
            ->where('status', 'active')
            ->exists();
        
        if (!$isLinked) {
            abort(403, 'No tienes permiso para ver los datos de este paciente.');
        }
    }
}
