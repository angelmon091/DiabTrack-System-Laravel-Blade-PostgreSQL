<?php

namespace App\Http\Controllers\Tracking;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VitalSign;
use App\Models\Symptom;
use App\Models\NutritionLog;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class TrackingController extends Controller
{
    public function signos()
    {
        return view('tracking.signos');
    }

    public function sintomas()
    {
        $symptoms = Symptom::all()->groupBy('category');
        
        if ($symptoms->isEmpty()) {
            // Hardcoded fallback to match the original layout if DB is not seeded
            $fallback = collect([
                'physical' => collect([
                    (object)['id' => 1, 'name' => 'Sudoración fría'],
                    (object)['id' => 2, 'name' => 'Temblores'],
                    (object)['id' => 3, 'name' => 'Hambre repentina'],
                    (object)['id' => 4, 'name' => 'Palpitaciones'],
                    (object)['id' => 5, 'name' => 'Ansiedad'],
                ]),
                'nocturnal' => collect([
                    (object)['id' => 6, 'name' => 'Pesadillas'],
                    (object)['id' => 7, 'name' => 'Sudoración Nocturna'],
                    (object)['id' => 8, 'name' => 'Dolor de cabeza matutino'],
                    (object)['id' => 9, 'name' => 'Cansancio al despertar'],
                ]),
                'neurological' => collect([
                    (object)['id' => 10, 'name' => 'Confusión'],
                    (object)['id' => 11, 'name' => 'Visión borrosa'],
                    (object)['id' => 12, 'name' => 'Dificultad para hablar'],
                    (object)['id' => 13, 'name' => 'Debilidad'],
                    (object)['id' => 14, 'name' => 'Mareo'],
                ]),
                'atypical' => collect([
                    (object)['id' => 15, 'name' => 'Cambios de Humor'],
                    (object)['id' => 16, 'name' => 'Náuseas'],
                    (object)['id' => 17, 'name' => 'Hormigueo en labios y/o lengua'],
                ])
            ]);
            $symptoms = $fallback;
        }

        return view('tracking.sintomas', compact('symptoms'));
    }

    public function nutricion()
    {
        return view('tracking.nutricion');
    }

    public function movimiento()
    {
        return view('tracking.movimiento');
    }

    public function storeSignos(Request $request)
    {
        $request->validate([
            'glucose_level' => 'required|numeric',
            'measurement_moment' => 'required|string',
        ]);

        VitalSign::create([
            'user_id' => Auth::id(),
            'glucose_level' => $request->glucose_level,
            'systolic' => $request->systolic,
            'diastolic' => $request->diastolic,
            'heart_rate' => $request->heart_rate,
            'hba1c' => $request->hba1c,
            'measurement_moment' => $request->measurement_moment,
        ]);

        return redirect()->route('dashboard')->with('status', 'Signos vitales registrados.');
    }

    public function storeSintomas(Request $request)
    {
        $request->validate(['symptoms' => 'array']);
        $user = auth()->user();
        $loggedAt = $request->logged_at ?? now();

        // If editing, we clear previous ones for this specific time first
        if ($request->has('logged_at')) {
            $user->symptoms()->wherePivot('logged_at', $request->logged_at)->detach();
        }

        if ($request->has('symptoms')) {
            $user->symptoms()->attach($request->symptoms, ['logged_at' => $loggedAt]);
        }

        return redirect()->route('registro.historial')->with('status', 'Síntomas actualizados.');
    }

    public function storeNutricion(Request $request)
    {
        $request->validate([
            'meal_time' => 'required',
            'carbs' => 'nullable|numeric',
        ]);

        NutritionLog::create([
            'user_id' => Auth::id(),
            'meal_type' => implode(', ', $request->meal_type ?? []),
            'carbs_grams' => $request->carbs,
            'consumed_at' => $request->meal_time,
            'food_categories' => $request->foods ?? [],
            'medication_taken' => implode(', ', $request->medication ?? []),
            'medication_dose' => $request->medication_amount,
        ]);

        return redirect()->route('dashboard')->with('status', 'Nutrición registrada.');
    }

    public function storeMovimiento(Request $request)
    {
        $request->validate([
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $start = new \DateTime($request->start_time);
        $end = new \DateTime($request->end_time);
        $diff = $start->diff($end);
        $duration = ($diff->h * 60) + $diff->i;

        ActivityLog::create([
            'user_id' => Auth::id(),
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'duration_minutes' => $duration,
            'intensity' => $request->intensity,
            'activity_type' => implode(', ', $request->activities ?? []),
        ]);

        return redirect()->route('dashboard')->with('status', 'Actividad registrada.');
    }

    public function historial()
    {
        $user = auth()->user();
        $vitals = VitalSign::where('user_id', $user->id)->latest()->get();
        $nutrition = NutritionLog::where('user_id', $user->id)->latest()->get();
        $activity = ActivityLog::where('user_id', $user->id)->latest()->get();
        $symptomsEntries = $user->symptoms()->latest('logged_at')->get();

        return view('tracking.historial', compact('vitals', 'nutrition', 'activity', 'symptomsEntries'));
    }

    public function destroySigno($id)
    {
        $signo = VitalSign::where('user_id', auth()->id())->findOrFail($id);
        $signo->delete();
        return back()->with('status', 'Registro eliminado correctamente.');
    }

    public function editSigno($id)
    {
        $signo = VitalSign::where('user_id', auth()->id())->findOrFail($id);
        return view('tracking.signos', compact('signo'));
    }

    public function updateSigno(Request $request, $id)
    {
        $signo = VitalSign::where('user_id', auth()->id())->findOrFail($id);
        
        $request->validate([
            'glucose_level' => 'required|numeric',
            'measurement_moment' => 'required|string',
        ]);

        $signo->update([
            'glucose_level' => $request->glucose_level,
            'systolic' => $request->systolic,
            'diastolic' => $request->diastolic,
            'heart_rate' => $request->heart_rate,
            'hba1c' => $request->hba1c,
            'measurement_moment' => $request->measurement_moment,
        ]);

        return redirect()->route('registro.historial')->with('status', 'Registro actualizado.');
    }

    public function destroyNutricion($id)
    {
        NutritionLog::where('user_id', auth()->id())->findOrFail($id)->delete();
        return back()->with('status', 'Registro de nutrición eliminado.');
    }

    public function destroyMovimiento($id)
    {
        ActivityLog::where('user_id', auth()->id())->findOrFail($id)->delete();
        return back()->with('status', 'Registro de actividad eliminado.');
    }

    public function destroySintoma(Request $request)
    {
        $user = auth()->user();
        $user->symptoms()->wherePivot('symptom_id', $request->symptom_id)
             ->wherePivot('logged_at', $request->logged_at)
             ->detach($request->symptom_id);
             
        return back()->with('status', 'Sintoma eliminado del historial.');
    }

    public function editSintoma(Request $request)
    {
        // To edit, we find all symptoms the user logged at that same 'logged_at' time
        $user = auth()->user();
        $logged_at = $request->logged_at;
        $selectedSymptoms = $user->symptoms()->wherePivot('logged_at', $logged_at)->pluck('symptoms.id')->toArray();
        
        $symptoms = \App\Models\Symptom::all()->groupBy('category');
        return view('tracking.sintomas', compact('symptoms', 'selectedSymptoms', 'logged_at'));
    }

    public function editNutricion($id)
    {
        $nutritionEntry = NutritionLog::where('user_id', auth()->id())->findOrFail($id);
        return view('tracking.nutricion', compact('nutritionEntry'));
    }

    public function updateNutricion(Request $request, $id)
    {
        $log = NutritionLog::where('user_id', auth()->id())->findOrFail($id);
        $request->validate(['meal_time' => 'required', 'carbs' => 'nullable|numeric']);

        $log->update([
            'meal_type' => implode(', ', $request->meal_type ?? []),
            'carbs_grams' => $request->carbs,
            'consumed_at' => $request->meal_time,
            'food_categories' => $request->foods ?? [],
            'medication_taken' => implode(', ', $request->medication ?? []),
            'medication_dose' => $request->medication_amount,
        ]);

        return redirect()->route('registro.historial')->with('status', 'Nutrición actualizada.');
    }

    public function editMovimiento($id)
    {
        $activityEntry = ActivityLog::where('user_id', auth()->id())->findOrFail($id);
        return view('tracking.movimiento', compact('activityEntry'));
    }

    public function updateMovimiento(Request $request, $id)
    {
        $log = ActivityLog::where('user_id', auth()->id())->findOrFail($id);
        $request->validate(['start_time' => 'required', 'end_time' => 'required']);

        $start = \Carbon\Carbon::parse($request->start_time);
        $end = \Carbon\Carbon::parse($request->end_time);
        $duration = $start->diffInMinutes($end);

        $log->update([
            'activity_type' => implode(', ', $request->activities ?? []),
            'intensity' => $request->intensity,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'duration_minutes' => $duration,
        ]);

        return redirect()->route('registro.historial')->with('status', 'Actividad actualizada.');
    }
}
