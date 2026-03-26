@extends('layouts.app')

@section('title', 'DiabTrack - Registro de Signos Vitales')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/tracking.css') }}">
@endsection

@section('content')
<div class="tracking-container animate-fade-in">
    <div class="tracking-header">
        <h1>{{ __('Registro de Datos') }}</h1>
        <p class="tracking-subtitle">{{ __('Registra tus signos vitales para un mejor control') }}</p>
    </div>

    <x-tracking-nav active="signos" />

    <form class="tracking-form-layout" action="{{ route('tracking.vital.store') }}" method="POST">
        @csrf
        
        <section class="tracking-form-main">
            <div class="diab-card p-4 mb-4">
                <div class="tracking-field">
                    <label>{{ __('Nivel de Glucosa') }}: <strong id="glucose_val">{{ old('glucose_level', 120) }}</strong> mg/dL</label>
                    <input type="range" name="glucose_level" class="tracking-range" min="40" max="300" value="{{ old('glucose_level', 120) }}" oninput="document.getElementById('glucose_val').innerText = this.value">
                    <x-input-error :messages="$errors->get('glucose_level')" />
                </div>

                <div class="tracking-field">
                    <label>{{ __('Presión Arterial (Sistólica / Diastólica)') }}:</label>
                    <div class="d-flex gap-3">
                        <input type="number" name="systolic" class="tracking-input" placeholder="{{ __('Sistólica') }}" value="{{ old('systolic') }}">
                        <input type="number" name="diastolic" class="tracking-input" placeholder="{{ __('Diastólica') }}" value="{{ old('diastolic') }}">
                    </div>
                    <x-input-error :messages="$errors->get('systolic')" />
                    <x-input-error :messages="$errors->get('diastolic')" />
                </div>

                <div class="tracking-field">
                    <label>{{ __('Frecuencia Cardiaca') }}: <strong id="heart_val">{{ old('heart_rate', 75) }}</strong> bpm</label>
                    <input type="range" name="heart_rate" class="tracking-range" min="40" max="200" value="{{ old('heart_rate', 75) }}" oninput="document.getElementById('heart_val').innerText = this.value">
                    <x-input-error :messages="$errors->get('heart_rate')" />
                </div>

                <div class="tracking-field" style="margin-bottom: 0;">
                    <label>{{ __('Hemoglobina Glicosilada (HbA1c)') }}:</label>
                    <input type="number" step="0.1" name="hba1c" class="tracking-input" placeholder="{{ __('% de HbA1c') }}" value="{{ old('hba1c') }}">
                    <x-input-error :messages="$errors->get('hba1c')" />
                </div>
            </div>
        </section>

        <aside class="tracking-form-aside">
            <div class="tracking-panel">
                <h3>{{ __('Momento de la Medición') }}</h3>
                <input type="hidden" name="measurement_moment" id="measurement_moment" value="{{ old('measurement_moment', 'Ayunas') }}">
                
                <div class="selector-grid">
                    @php
                        $moments = [
                            ['id' => 'Ayunas', 'icon' => 'wb_sunny.png', 'label' => 'Ayunas'],
                            ['id' => 'Antes de Comer', 'icon' => 'no_meals_ouline.png', 'label' => 'Antes de Comer'],
                            ['id' => 'Después de Comer', 'icon' => 'restaurant.png', 'label' => 'Después de Comer'],
                            ['id' => 'Al Dormir', 'icon' => 'bedtime.png', 'label' => 'Al Dormir'],
                        ];
                    @endphp

                    @foreach($moments as $moment)
                        <button type="button" 
                                class="selector-btn {{ old('measurement_moment', 'Ayunas') == $moment['id'] ? 'active' : '' }}" 
                                onclick="setMoment('{{ $moment['id'] }}', this)">
                            <img src="{{ asset('img/medios/iconos/' . $moment['icon']) }}" alt="{{ $moment['label'] }}">
                            <span>{{ __($moment['label']) }}</span>
                        </button>
                    @endforeach
                </div>
                <x-input-error :messages="$errors->get('measurement_moment')" />
            </div>
        </aside>

        <div class="tracking-actions">
            <button type="reset" class="btn-track-reset">{{ __('Borrar') }}</button>
            <button type="submit" class="btn-track-save">{{ __('Guardar') }}</button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    function setMoment(val, btn) {
        document.getElementById('measurement_moment').value = val;
        document.querySelectorAll('#measurement_moment ~ .selector-grid .selector-btn, .selector-grid .selector-btn').forEach(function(b) {
            if (b.closest('.tracking-panel')) b.classList.remove('active');
        });
        btn.classList.add('active');
    }
</script>
@endsection
