<?php

namespace App\Http\Controllers;

//obtener los datos
use App\Models\VitalSign;
use App\Models\PatientProfile;
use Illuminate\Support\Facades\Auth;


use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        if (!auth()->user()->patientProfile) {
            return redirect()->route('onboarding.index');
        }

        //obtener la última medición de signos vitales del usuario logueado
        $ultimaMedicion = VitalSign::where('user_id', Auth::id())
            ->latest('created_at')
            ->first();

        //si no hay datos, enviar valores por defecto o 0
        return view('dashboard', compact('ultimaMedicion'));
    }

}
