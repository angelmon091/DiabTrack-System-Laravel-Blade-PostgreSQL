<?php

namespace App\Http\Controllers;

use App\Models\PatientProfile;
use App\Models\CaregiverProfile;
use App\Models\DoctorProfile;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Clase OnboardingController
 * 
 * Gestiona el proceso de onboarding para nuevos usuarios.
 * Adapta el formulario según el rol seleccionado (paciente, cuidador o médico).
 */
class OnboardingController extends Controller
{
    /**
     * Muestra la pantalla de selección de rol.
     */
    public function index()
    {
        if (Auth::user()->hasCompletedOnboarding()) {
            return redirect()->route('dashboard');
        }

        return view('onboarding.role-selection');
    }

    /**
     * Muestra el formulario de datos de paciente.
     */
    public function showPatientForm()
    {
        return view('onboarding.personal-data');
    }

    /**
     * Almacena los datos del paciente.
     */
    public function storePatient(Request $request)
    {
        $validated = $request->validate([
            'birth_day' => 'required|integer|min:1|max:31',
            'birth_month' => 'required|string',
            'birth_year' => 'required|integer|min:1920|max:' . date('Y'),
            'diabetes_type' => 'required|string',
            'weight' => 'required|numeric|min:1|max:500',
            'height' => 'required|numeric|min:30|max:300',
            'gender' => 'required|string|in:Masculino,Femenino',
        ]);

        $birthDate = sprintf('%04d-%02d-%02d', 
            $validated['birth_year'], 
            $this->getMonthNumber($validated['birth_month']), 
            $validated['birth_day']
        );

        PatientProfile::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'birth_date' => $birthDate,
                'diabetes_type' => $validated['diabetes_type'],
                'weight' => $validated['weight'],
                'height' => $validated['height'],
                'gender' => $validated['gender'],
            ]
        );

        // Asignar rol de paciente
        $role = Role::where('name', 'paciente')->first();
        Auth::user()->roles()->syncWithoutDetaching([$role->id]);

        return redirect()->route('dashboard')->with('status', __('¡Bienvenido! Tu perfil de paciente ha sido configurado.'));
    }

    /**
     * Muestra el formulario de datos de cuidador.
     */
    public function showCaregiverForm()
    {
        return view('onboarding.caregiver-data');
    }

    /**
     * Almacena los datos del cuidador.
     */
    public function storeCaregiver(Request $request)
    {
        $validated = $request->validate([
            'gender' => 'required|string|in:Masculino,Femenino',
            'relationship' => 'required|string',
        ]);

        CaregiverProfile::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'gender' => $validated['gender'],
                'relationship' => $validated['relationship'],
            ]
        );

        // Asignar rol de cuidador
        $role = Role::where('name', 'cuidador')->first();
        Auth::user()->roles()->syncWithoutDetaching([$role->id]);

        return redirect()->route('caregiver.dashboard')->with('status', __('¡Bienvenido! Tu perfil de cuidador ha sido configurado.'));
    }

    /**
     * Muestra el formulario de datos del médico.
     */
    public function showDoctorForm()
    {
        return view('onboarding.doctor-data');
    }

    /**
     * Almacena los datos del médico.
     */
    public function storeDoctor(Request $request)
    {
        $validated = $request->validate([
            'gender' => 'required|string|in:Masculino,Femenino',
            'license_number' => 'required|string|max:50',
            'specialty' => 'required|string',
        ]);

        DoctorProfile::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'gender' => $validated['gender'],
                'license_number' => $validated['license_number'],
                'specialty' => $validated['specialty'],
            ]
        );

        // Asignar rol de médico
        $role = Role::where('name', 'médico')->first();
        Auth::user()->roles()->syncWithoutDetaching([$role->id]);

        return redirect()->route('doctor.dashboard')->with('status', __('¡Bienvenido! Tu perfil profesional ha sido configurado.'));
    }

    private function getMonthNumber($monthName)
    {
        $months = [
            'Enero' => '01', 'Febrero' => '02', 'Marzo' => '03', 'Abril' => '04',
            'Mayo' => '05', 'Junio' => '06', 'Julio' => '07', 'Agosto' => '08',
            'Septiembre' => '09', 'Octubre' => '10', 'Noviembre' => '11', 'Diciembre' => '12'
        ];
        return $months[$monthName] ?? '01';
    }
}
