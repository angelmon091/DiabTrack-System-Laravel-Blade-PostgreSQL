<?php

namespace App\Http\Controllers;

use App\Models\PatientLink;
use App\Models\User;
use App\Models\VitalSign;
use App\Services\DashboardMetricsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Dashboard y gestión de pacientes para médicos.
 */
class DoctorController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $patients = $user->linkedPatients()->with('patientProfile', 'vitalSigns')->get();

        return view('doctor.dashboard', compact('user', 'patients'));
    }

    /**
     * Muestra el formulario para vincular un paciente con código.
     */
    public function showLinkForm()
    {
        return view('doctor.link-patient');
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

        return redirect()->route('doctor.dashboard')
            ->with('status', '¡Paciente vinculado exitosamente!');
    }
    /**
     * Muestra el detalle clínico de un paciente vinculado.
     */
    public function showPatient(User $patient, DashboardMetricsService $metricsService)
    {
        $this->checkLink($patient->id);

        $metrics = $metricsService->getDashboardMetrics($patient->id);
        
        $recentLogs = VitalSign::where('user_id', $patient->id)
            ->whereNotNull('glucose_level')
            ->latest()
            ->take(10)
            ->get();

        return view('doctor.patient-detail', array_merge($metrics, compact('patient', 'recentLogs')));
    }

    /**
     * Actualiza las metas glucémicas de un paciente.
     */
    public function updateTargets(Request $request, User $patient)
    {
        $this->checkLink($patient->id);

        $validated = $request->validate([
            'target_glucose_min' => 'required|integer|min:40|max:150',
            'target_glucose_max' => 'required|integer|min:100|max:300',
        ]);

        if ($patient->patientProfile) {
            $patient->patientProfile->update([
                'target_glucose_min' => $validated['target_glucose_min'],
                'target_glucose_max' => $validated['target_glucose_max'],
            ]);
        } else {
            return back()->withErrors(['general' => 'El perfil del paciente no existe.']);
        }

        return redirect()->route('doctor.patient.show', $patient)
            ->with('status', 'Metas terapéuticas actualizadas correctamente.');
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
