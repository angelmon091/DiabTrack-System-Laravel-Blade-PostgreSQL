@extends('layouts.app')

@section('title', 'DiabTrack - Registro de Signos Vitales')

@section('styles')
    @vite('resources/css/tracking.css')
@endsection

@section('content')
    <div class="tracking-container animate-fade-in">
        <div class="tracking-header">
            <h1>{{ __('Registro de Signos Vitales') }}</h1>
            <p class="tracking-subtitle">{{ __('Registra tus mediciones corporales para un mejor control') }}</p>
        </div>

        <x-tracking-nav active="signos" />

        <form class="tracking-form-layout" action="{{ route('tracking.vital.store') }}" method="POST">
            @csrf

            <section class="tracking-form-main">
                <div class="diab-card p-4 mb-4">
                    <div class="tracking-field">
                        <label class="d-flex justify-content-between align-items-center w-100 mb-3">
                            <span>{{ __('Nivel de Glucosa (Azúcar)') }}: <strong id="glucose_val">{{ old('glucose_level', 120) }}</strong> mg/dL</span>
                            <i class="fa-solid fa-circle-info info-icon opacity-50 text-muted" data-bs-toggle="tooltip" title="Tu nivel de azúcar. Se mide con un aparatito (glucómetro) pinchando el dedo o con un sensor."></i>
                        </label>
                        <input type="range" name="glucose_level" class="tracking-range" min="40" max="300"
                            value="{{ old('glucose_level', 120) }}"
                            oninput="document.getElementById('glucose_val').innerText = this.value">
                        <x-input-error :messages="$errors->get('glucose_level')" />
                    </div>

                    <div class="tracking-field">
                        <label class="d-flex justify-content-between align-items-center w-100 mb-3">
                            <span>{{ __('Presión Arterial (Sistólica / Diastólica)') }} <span class="text-muted small fw-normal">(Opcional)</span>:</span>
                            <i class="fa-solid fa-circle-info info-icon opacity-50 text-muted" data-bs-toggle="tooltip" title="La fuerza de tu corazón. Se mide con el aparato de la presión (baumanómetro) en el brazo."></i>
                        </label>
                        <div class="d-flex gap-3">
                            <input type="number" name="systolic" class="tracking-input" placeholder="{{ __('Sistólica') }}"
                                value="{{ old('systolic') }}">
                            <input type="number" name="diastolic" class="tracking-input"
                                placeholder="{{ __('Diastólica') }}" value="{{ old('diastolic') }}">
                        </div>
                        <x-input-error :messages="$errors->get('systolic')" />
                        <x-input-error :messages="$errors->get('diastolic')" />
                    </div>

                    <div class="tracking-field">
                        <label class="d-flex justify-content-between align-items-center w-100 mb-3">
                            <span>{{ __('Frecuencia Cardiaca') }} <span class="text-muted small fw-normal">(Opcional)</span>: <strong id="heart_val">{{ old('heart_rate', 75) }}</strong> bpm</span>
                            <i class="fa-solid fa-circle-info info-icon opacity-50 text-muted" data-bs-toggle="tooltip" title="Tus latidos por minuto. Puedes verlos en un reloj inteligente o sintiendo el pulso en tu muñeca."></i>
                        </label>
                        <input type="range" name="heart_rate" class="tracking-range" min="40" max="200"
                            value="{{ old('heart_rate', 75) }}"
                            oninput="document.getElementById('heart_val').innerText = this.value">
                        <x-input-error :messages="$errors->get('heart_rate')" />
                    </div>

                    <div class="tracking-field" style="margin-bottom: 0;">
                        <label>{{ __('Hemoglobina Glicosilada (HbA1c)') }} <span class="text-muted small fw-normal">(Opcional)</span>:</label>
                        <p class="text-muted extra-small mb-2 mt-1">El promedio de tu azúcar de los últimos 3 meses.</p>
                        <input type="number" step="0.1" name="hba1c" class="tracking-input"
                            placeholder="{{ __('% de HbA1c') }}" value="{{ old('hba1c') }}">
                        <x-input-error :messages="$errors->get('hba1c')" />
                    </div>
                </div>

                <div class="diab-card p-4 mb-4">
                    <div class="tracking-field" style="margin-bottom: 0;">
                        <label>{{ __('Notas Adicionales') }} <span class="text-muted small fw-normal">(Opcional)</span>:</label>
                        <p class="text-muted extra-small mb-2 mt-1">Escribe si te sentiste mal, o si comiste algo fuera de lo común.</p>
                        <textarea name="notes" class="tracking-input" rows="3" placeholder="{{ __('Ej: Fui a una fiesta y comí pastel...') }}">{{ old('notes') }}</textarea>
                        <x-input-error :messages="$errors->get('notes')" />
                    </div>
                </div>
            </section>

            <aside class="tracking-form-aside">
                <div class="tracking-panel">
                    <h3>{{ __('Momento de la Medición') }}</h3>
                    <input type="hidden" name="measurement_moment" id="measurement_moment"
                        value="{{ old('measurement_moment', 'Ayunas') }}">

                    <div class="selector-grid">
                        @php
                            $moments = [
                                ['id' => 'Ayunas', 'icon' => 'fa-solid fa-sun', 'label' => 'Ayunas'],
                                ['id' => 'Antes de Comer', 'icon' => 'fa-solid fa-utensils', 'label' => 'Antes de Comer'],
                                ['id' => 'Después de Comer', 'icon' => 'fa-solid fa-mug-hot', 'label' => 'Después de Comer'],
                                ['id' => 'Al Dormir', 'icon' => 'fa-solid fa-moon', 'label' => 'Antes de Dormir'],
                            ];
                        @endphp

                        @foreach($moments as $moment)
                            <button type="button"
                                class="selector-btn {{ old('measurement_moment', 'Ayunas') == $moment['id'] ? 'active' : '' }}"
                                onclick="setMoment('{{ $moment['id'] }}', this)">
                                <span class="selector-emoji"><i class="{{ $moment['icon'] }}"></i></span>
                                <span>{{ __($moment['label']) }}</span>
                            </button>
                        @endforeach
                    </div>
                    <x-input-error :messages="$errors->get('measurement_moment')" />
                </div>

                <div class="tracking-panel mt-4">
                    <h3>{{ __('Nivel de Estrés') }} <span class="text-muted fs-6 fw-normal">(Opcional)</span></h3>
                    <input type="hidden" name="stress_level" id="stress_level" value="{{ old('stress_level') }}">

                    <div class="selector-grid">
                        @php
                            $stressLevels = [
                                ['id' => 'Bajo', 'icon' => 'fa-solid fa-face-smile', 'label' => 'Bajo'],
                                ['id' => 'Medio', 'icon' => 'fa-solid fa-face-meh', 'label' => 'Medio'],
                                ['id' => 'Alto', 'icon' => 'fa-solid fa-face-frown', 'label' => 'Alto'],
                            ];
                        @endphp

                        @foreach($stressLevels as $stress)
                            <button type="button"
                                class="selector-btn {{ old('stress_level') == $stress['id'] ? 'active' : '' }}"
                                onclick="setStress('{{ $stress['id'] }}', this)">
                                <span class="selector-emoji"><i class="{{ $stress['icon'] }}"></i></span>
                                <span>{{ __($stress['label']) }}</span>
                            </button>
                        @endforeach
                    </div>
                    <x-input-error :messages="$errors->get('stress_level')" />
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
            const container = btn.closest('.selector-grid');
            container.querySelectorAll('.selector-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
        }

        function setStress(val, btn) {
            document.getElementById('stress_level').value = val;
            const container = btn.closest('.selector-grid');
            container.querySelectorAll('.selector-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
        }
    </script>
@endsection