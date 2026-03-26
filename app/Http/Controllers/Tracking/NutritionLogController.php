<?php

namespace App\Http\Controllers\Tracking;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tracking\NutritionLogRequest;
use App\Models\NutritionLog;
use Illuminate\Support\Facades\Auth;

class NutritionLogController extends Controller
{
    public function create()
    {
        return view('tracking.nutrition.create');
    }

    public function store(NutritionLogRequest $request)
    {
        NutritionLog::create([
            'user_id' => Auth::id(),
            'meal_type' => $request->meal_type,
            'carbs_grams' => $request->carbs_grams,
            'consumed_at' => $request->consumed_at,
            'food_categories' => $request->food_categories,
            'medication_taken' => $request->medication_taken,
            'medication_dose' => $request->medication_dose,
        ]);

        return redirect()->route('dashboard')->with('status', __('Registro de nutrición guardado con éxito.'));
    }
}
