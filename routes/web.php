<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Tracking\VitalSignController;
use App\Http\Controllers\Tracking\TrackingController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Onboarding Routes
    Route::get('/onboarding', [OnboardingController::class, 'index'])->name('onboarding.index');
    Route::post('/onboarding', [OnboardingController::class, 'store'])->name('onboarding.store');

    // Tracking Routes
    Route::prefix('tracking')->name('tracking.')->group(function () {
        Route::get('/vitals', [VitalSignController::class, 'create'])->name('vital.create');
        Route::post('/vitals', [VitalSignController::class, 'store'])->name('vital.store');
    });

    Route::prefix('registro')->name('registro.')->group(function () {
        Route::get('/signos', [TrackingController::class, 'signos'])->name('signos');
        Route::post('/signos', [TrackingController::class, 'storeSignos'])->name('signos.store');
        
        Route::get('/sintomas', [TrackingController::class, 'sintomas'])->name('sintomas');
        Route::post('/sintomas', [TrackingController::class, 'storeSintomas'])->name('sintomas.store');
        
        Route::get('/nutricion', [TrackingController::class, 'nutricion'])->name('nutricion');
        Route::post('/nutricion', [TrackingController::class, 'storeNutricion'])->name('nutricion.store');
        
        Route::get('/movimiento', [TrackingController::class, 'movimiento'])->name('movimiento');
        Route::post('/movimiento', [TrackingController::class, 'storeMovimiento'])->name('movimiento.store');

        Route::get('/historial', [TrackingController::class, 'historial'])->name('historial');
        Route::get('/signos/{id}/edit', [TrackingController::class, 'editSigno'])->name('signos.edit');
        Route::put('/signos/{id}', [TrackingController::class, 'updateSigno'])->name('signos.update');
        Route::delete('/signos/{id}', [TrackingController::class, 'destroySigno'])->name('signos.destroy');
        
        Route::get('/nutricion/{id}/edit', [TrackingController::class, 'editNutricion'])->name('nutricion.edit');
        Route::put('/nutricion/{id}', [TrackingController::class, 'updateNutricion'])->name('nutricion.update');
        Route::delete('/nutricion/{id}', [TrackingController::class, 'destroyNutricion'])->name('nutricion.destroy');

        Route::get('/movimiento/{id}/edit', [TrackingController::class, 'editMovimiento'])->name('movimiento.edit');
        Route::put('/movimiento/{id}', [TrackingController::class, 'updateMovimiento'])->name('movimiento.update');
        Route::delete('/movimiento/{id}', [TrackingController::class, 'destroyMovimiento'])->name('movimiento.destroy');
        
        Route::get('/sintomas/edit', [TrackingController::class, 'editSintoma'])->name('sintomas.edit');
        Route::delete('/sintomas', [TrackingController::class, 'destroySintoma'])->name('sintomas.destroy');
    });

    Route::view('/vital_signs', 'signos.index')->name('vital_signs.index');

});

require __DIR__ . '/auth.php';

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::resource('roles', \App\Http\Controllers\Admin\RoleController::class);
});
