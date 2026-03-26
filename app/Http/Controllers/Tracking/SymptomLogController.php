<?php

namespace App\Http\Controllers\Tracking;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tracking\SymptomLogRequest;
use App\Models\Symptom;
use Illuminate\Support\Facades\Auth;

class SymptomLogController extends Controller
{
    public function create()
    {
        $symptoms = Symptom::all()->groupBy('category');

        return view('tracking.symptom.create', compact('symptoms'));
    }

    public function store(SymptomLogRequest $request)
    {
        $user = Auth::user();
        $now = now();

        $pivotData = [];
        foreach ($request->symptoms as $symptomId) {
            $pivotData[$symptomId] = ['logged_at' => $now];
        }

        $user->symptoms()->attach($pivotData);

        return redirect()->route('dashboard')->with('status', __('Registro de síntomas guardado con éxito.'));
    }
}
