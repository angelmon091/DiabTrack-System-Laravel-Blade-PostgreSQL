<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Tracking\VitalSignController;
use App\Http\Controllers\Tracking\ActivityLogController;
use App\Http\Controllers\Tracking\NutritionLogController;
use App\Http\Controllers\Tracking\SymptomLogController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/weight', [DashboardController::class, 'storeWeight'])->name('dashboard.weight.store');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Onboarding Routes
    Route::get('/onboarding', [OnboardingController::class, 'index'])->name('onboarding.index');
    Route::post('/onboarding', [OnboardingController::class, 'store'])->name('onboarding.store');

    // Tracking Routes
    Route::prefix('tracking')->middleware('throttle:30,1')->name('tracking.')->group(function () {
        Route::get('/summary', [DashboardController::class, 'summary'])->name('summary');

        Route::get('/vitals', [VitalSignController::class, 'create'])->name('vital.create');
        Route::post('/vitals', [VitalSignController::class, 'store'])->name('vital.store');

        Route::get('/activity', [ActivityLogController::class, 'create'])->name('activity.create');
        Route::post('/activity', [ActivityLogController::class, 'store'])->name('activity.store');

        Route::get('/nutrition', [NutritionLogController::class, 'index'])->name('nutrition.index');
        Route::get('/nutrition/create', [NutritionLogController::class, 'create'])->name('nutrition.create');
        Route::post('/nutrition', [NutritionLogController::class, 'store'])->name('nutrition.store');

        Route::get('/symptoms', [SymptomLogController::class, 'create'])->name('symptom.create');
        Route::post('/symptoms', [SymptomLogController::class, 'store'])->name('symptom.store');
    });
});

require __DIR__.'/auth.php';

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::resource('roles', \App\Http\Controllers\Admin\RoleController::class);
});
