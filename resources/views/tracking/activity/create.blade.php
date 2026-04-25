@extends('layouts.app')

@section('title', 'DiabTrack - Registro de Movimiento')

@section('styles')
    @vite('resources/css/tracking.css')
@endsection

@section('content')
    <div class="tracking-container animate-fade-in">
        <div class="tracking-header">
            <h1>{{ __('Registro de Actividad Física') }}</h1>
            <p class="tracking-subtitle">{{ __('Registra tu movimiento y nivel de energía diario') }}</p>
        </div>

        <x-tracking-nav active="movimiento" />

        <form class="tracking-form-layout" action="{{ route('tracking.activity.store') }}" method="POST">
            @csrf

            <section class="tracking-form-main">
                <div class="diab-card p-4 mb-4">
                    <div class="tracking-field">
                        <label>{{ __('Tipo de Actividad') }}:</label>
                        <select name="activity_type" class="tracking-select">
                            <option value="" disabled {{ old('activity_type') ? '' : 'selected' }}>
                                {{ __('Selecciona una actividad') }}</option>
                            @php
                                $activities = [
                                    'caminar' => 'Caminar',
                                    'correr' => 'Correr',
                                    'nadar' => 'Nadar',
                                    'bicicleta' => 'Bicicleta',
                                    'yoga' => 'Yoga',
                                    'gimnasio' => 'Gimnasio',
                                    'baile' => 'Baile',
                                    'estiramiento' => 'Estiramiento',
                                    'otro' => 'Otro',
                                ];
                            @endphp
                            @foreach($activities as $value => $label)
                                <option value="{{ $value }}" {{ old('activity_type') == $value ? 'selected' : '' }}>
                                    {{ __($label) }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('activity_type')" />
                    </div>

                    <div class="tracking-field">
                        <label>{{ __('Duración') }}: <strong id="duration_val">{{ old('duration_minutes', 30) }}</strong>
                            min</label>
                        <input type="range" name="duration_minutes" class="tracking-range" min="1" max="180"
                            value="{{ old('duration_minutes', 30) }}"
                            oninput="document.getElementById('duration_val').innerText = this.value">
                        <x-input-error :messages="$errors->get('duration_minutes')" />
                    </div>

                    <div class="tracking-field" style="margin-bottom: 0;">
                        <label class="d-flex justify-content-between align-items-center w-100 mb-3">
                            <span>{{ __('Nivel de Energía') }}:</span>
                            <i class="fa-solid fa-circle-info info-icon opacity-50 text-muted" data-bs-toggle="tooltip" title="¿Cómo sentías tu cuerpo antes y durante el ejercicio? ¿Cansado o con mucha fuerza?"></i>
                        </label>
                        <input type="hidden" name="energy_level" id="energy_level"
                            value="{{ old('energy_level', 'normal') }}">
                        <div class="selector-grid" id="energy-grid">
                            @php
                                $energyLevels = [
                                    ['id' => 'muy_baja', 'label' => 'Muy Baja', 'icon' => 'fa-solid fa-battery-empty'],
                                    ['id' => 'baja', 'label' => 'Baja', 'icon' => 'fa-solid fa-battery-quarter'],
                                    ['id' => 'normal', 'label' => 'Normal', 'icon' => 'fa-solid fa-battery-half'],
                                    ['id' => 'alta', 'label' => 'Alta', 'icon' => 'fa-solid fa-battery-three-quarters'],
                                    ['id' => 'muy_alta', 'label' => 'Muy Alta', 'icon' => 'fa-solid fa-battery-full'],
                                ];
                            @endphp
                            @foreach($energyLevels as $level)
                                <button type="button"
                                    class="selector-btn {{ old('energy_level', 'normal') == $level['id'] ? 'active' : '' }}"
                                    onclick="setEnergy('{{ $level['id'] }}', this)">
                                    <span class="selector-emoji"><i class="{{ $level['icon'] }}"></i></span>
                                    <span>{{ __($level['label']) }}</span>
                                </button>
                            @endforeach
                        </div>
                        <x-input-error :messages="$errors->get('energy_level')" />
                    </div>
                </div>
            </section>

            <aside class="tracking-form-aside">
                <div class="tracking-panel">
                    <h3 class="d-flex justify-content-between align-items-center w-100 mb-3">
                        <span>{{ __('Intensidad') }}</span>
                        <i class="fa-solid fa-circle-info info-icon opacity-50 text-muted fs-6" data-bs-toggle="tooltip" title="Baja: podías platicar. Media: podías platicar pero no cantar. Alta: te faltaba el aire al hablar."></i>
                    </h3>
                    <input type="hidden" name="intensity" id="intensity" value="{{ old('intensity', 'media') }}">

                    <div class="selector-grid" id="intensity-grid">
                        @php
                            $intensities = [
                                ['id' => 'baja', 'label' => 'Baja', 'icon' => 'fa-solid fa-gauge-simple text-success'],
                                ['id' => 'media', 'label' => 'Media', 'icon' => 'fa-solid fa-gauge text-warning'],
                                ['id' => 'alta', 'label' => 'Alta', 'icon' => 'fa-solid fa-gauge-high text-danger'],
                            ];
                        @endphp
                        @foreach($intensities as $intensity)
                            <button type="button"
                                class="selector-btn {{ old('intensity', 'media') == $intensity['id'] ? 'active' : '' }}"
                                onclick="setIntensity('{{ $intensity['id'] }}', this)">
                                <span class="selector-emoji"><i class="{{ $intensity['icon'] }}"></i></span>
                                <span>{{ __($intensity['label']) }}</span>
                            </button>
                        @endforeach
                    </div>
                    <x-input-error :messages="$errors->get('intensity')" />

                    <h3 class="mt-4">{{ __('Horario') }}</h3>
                    <div class="d-flex gap-3 mt-3">
                        <div style="flex:1;">
                            <label class="tracking-field-label">{{ __('Inicio') }} <span class="text-muted fw-normal" style="font-size: 0.75rem;">(Opcional)</span></label>
                            <input type="time" name="start_time" class="tracking-input" value="{{ old('start_time') }}">
                        </div>
                        <div style="flex:1;">
                            <label class="tracking-field-label">{{ __('Fin') }} <span class="text-muted fw-normal" style="font-size: 0.75rem;">(Opcional)</span></label>
                            <input type="time" name="end_time" class="tracking-input" value="{{ old('end_time') }}">
                        </div>
                    </div>
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
        function setIntensity(val, btn) {
            document.getElementById('intensity').value = val;
            document.getElementById('intensity-grid').querySelectorAll('.selector-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
        }

        function setEnergy(val, btn) {
            document.getElementById('energy_level').value = val;
            document.getElementById('energy-grid').querySelectorAll('.selector-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
        }
    </script>
@endsection