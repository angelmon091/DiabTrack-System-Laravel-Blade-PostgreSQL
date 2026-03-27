<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        if (!auth()->user()->patientProfile) {
            return redirect()->route('onboarding.index');
        }

        $user = auth()->user();
        
        // Fetch latest data for the dashboard
        $latestVitalSign = \App\Models\VitalSign::where('user_id', $user->id)->latest()->first();
        $totalCarbsToday = \App\Models\NutritionLog::where('user_id', $user->id)
            ->whereDate('created_at', today())
            ->sum('carbs_grams');
            
        $activityStats = \App\Models\ActivityLog::where('user_id', $user->id)
            ->whereDate('created_at', today())
            ->selectRaw('count(*) as count, sum(duration_minutes) as duration')
            ->first();

        return view('dashboard', compact('latestVitalSign', 'totalCarbsToday', 'activityStats'));
    }
}
