@extends('layouts.app')

@section('title', 'DiabTrack - Registrar Signos de ' . $patient->name)

@section('styles')
    @vite('resources/css/tracking.css')
@endsection

@section('content')
    <div class="tracking-container animate-fade-in">
        <div class="tracking-header">
            <h1>{{ __('Registro de Signos Vitales') }}</h1>
            <p class="tracking-subtitle">{{ __('Registrando datos para') }} <strong>{{ $patient->name }}</strong></p>
        </div>

        <form class="tracking-form-layout" action="{{ route('caregiver.patient.vital.store', $patient) }}" method="POST">
            @csrf

            <section class="tracking-form-main">
                <div class="diab-card p-4 mb-4">
                    <div class="tracking-field">
                        <label class="d-flex justify-content-between align-items-center w-100 mb-3">
                            <span>{{ __('Nivel de Glucosa (Azúcar)') }}: <strong id="glucose_val">{{ old('glucose_level', 120) }}</strong> mg/dL</span>
                            <i class="fa-solid fa-circle-info info-icon opacity-50 text-muted" data-bs-toggle="tooltip" title="Nivel de azúcar del paciente. Se mide con glucómetro."></i>
                        </label>
                        <input type="range" name="glucose_level" class="tracking-range" min="40" max="300"
                            value="{{ old('glucose_level', 120) }}"
                            oninput="document.getElementById('glucose_val').innerText = this.value">
                        <x-input-error :messages="$errors->get('glucose_level')" />
                    </div>

                    <div class="tracking-field">
                        <label class="d-flex justify-content-between align-items-center w-100 mb-3">
                            <span>{{ __('Presión Arterial (Sistólica / Diastólica)') }} <span class="text-muted small fw-normal">(Opcional)</span>:</span>
                            <i class="fa-solid fa-circle-info info-icon opacity-50 text-muted" data-bs-toggle="tooltip" title="Presión en el brazo medida con baumanómetro."></i>
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
                            <i class="fa-solid fa-circle-info info-icon opacity-50 text-muted" data-bs-toggle="tooltip" title="Latidos por minuto del paciente."></i>
                        </label>
                        <input type="range" name="heart_rate" class="tracking-range" min="40" max="200"
                            value="{{ old('heart_rate', 75) }}"
                            oninput="document.getElementById('heart_val').innerText = this.value">
                        <x-input-error :messages="$errors->get('heart_rate')" />
                    </div>

                    <div class="tracking-field" style="margin-bottom: 0;">
                        <label>{{ __('Hemoglobina Glicosilada (HbA1c)') }} <span class="text-muted small fw-normal">(Opcional)</span>:</label>
                        <p class="text-muted extra-small mb-2 mt-1">El promedio de azúcar de los últimos 3 meses.</p>
                        <input type="number" step="0.1" name="hba1c" class="tracking-input"
                            placeholder="{{ __('% de HbA1c') }}" value="{{ old('hba1c') }}">
                        <x-input-error :messages="$errors->get('hba1c')" />
                    </div>
                </div>

                <div class="diab-card p-4 mb-4">
                    <div class="tracking-field" style="margin-bottom: 0;">
                        <label>{{ __('Notas Adicionales') }} <span class="text-muted small fw-normal">(Opcional)</span>:</label>
                        <p class="text-muted extra-small mb-2 mt-1">Escribe cómo se sintió el paciente o cualquier otra observación.</p>
                        <textarea name="notes" class="tracking-input" rows="3" placeholder="{{ __('Ej: El paciente mencionó tener dolor de cabeza...') }}">{{ old('notes') }}</textarea>
                        <x-input-error :messages="$errors->get('notes')" />
                    </div>
                </div>
            </section>

            <aside class="tracking-form-aside">
                <div class="tracking-panel">
                    <label class="d-flex justify-content-between align-items-center w-100 mb-3">
                        <span style="font-size: 1.1rem; font-weight: 700; color: var(--diab-text);">{{ __('¿Cuándo se midió?') }}</span>
                        <i class="fa-solid fa-circle-info info-icon opacity-50 text-muted" data-bs-toggle="tooltip" title="En ayunas: al despertar sin haber comido nada (8+ hrs). Antes de comer: justo antes de desayunar, comer o cenar. Después de comer: 1-2 horas después de cualquier comida. Al dormir: antes de acostarse."></i>
                    </label>
                    <input type="hidden" name="measurement_moment" id="measurement_moment"
                        value="{{ old('measurement_moment', 'Ayunas') }}">

                    <div class="selector-grid">
                        @php
                            $moments = [
                                [
                                    'id'   => 'Ayunas',
                                    'icon' => 'fa-solid fa-sun',
                                    'label'=> 'En Ayunas',
                                    'desc' => 'Al despertar, sin haber comido (8+ hrs)',
                                ],
                                [
                                    'id'   => 'Antes de Comer',
                                    'icon' => 'fa-solid fa-clock',
                                    'label'=> 'Antes de Comer',
                                    'desc' => 'Justo antes de desayunar, comer o cenar',
                                ],
                                [
                                    'id'   => 'Después de Comer',
                                    'icon' => 'fa-solid fa-utensils',
                                    'label'=> 'Después de Comer',
                                    'desc' => '1-2 horas después de la comida',
                                ],
                                [
                                    'id'   => 'Al Dormir',
                                    'icon' => 'fa-solid fa-moon',
                                    'label'=> 'Al Dormir',
                                    'desc' => 'Antes de acostarse',
                                ],
                            ];
                        @endphp

                        @foreach($moments as $moment)
                            <button type="button"
                                class="selector-btn {{ old('measurement_moment', 'Ayunas') == $moment['id'] ? 'active' : '' }}"
                                onclick="setMoment('{{ $moment['id'] }}', this)">
                                <span class="selector-emoji"><i class="{{ $moment['icon'] }}"></i></span>
                                <span>{{ __($moment['label']) }}</span>
                                <span style="display:block; font-size:0.6rem; color:inherit; opacity:0.65; line-height:1.3; margin-top:2px;">{{ $moment['desc'] }}</span>
                            </button>
                        @endforeach
                    </div>
                    <x-input-error :messages="$errors->get('measurement_moment')" />
                </div>

                <div class="tracking-panel mt-4">
                    <h3>{{ __('Nivel de Estrés Percibido') }} <span class="text-muted fs-6 fw-normal">(Opcional)</span></h3>
                    <input type="hidden" name="stress_level" id="stress_level" value="{{ old('stress_level') }}">

                    <div class="selector-grid">
                        @php
                            $stressLevels = [
                                ['id' => 'Bajo',  'icon' => 'fa-solid fa-face-smile', 'label' => 'Bajo',  'desc' => 'Relajado, sin tensión'],
                                ['id' => 'Medio', 'icon' => 'fa-solid fa-face-meh',   'label' => 'Medio', 'desc' => 'Algo de presión o ansiedad'],
                                ['id' => 'Alto',  'icon' => 'fa-solid fa-face-frown', 'label' => 'Alto',  'desc' => 'Muy estresado o tenso'],
                            ];
                        @endphp

                        @foreach($stressLevels as $stress)
                            <button type="button"
                                class="selector-btn {{ old('stress_level') == $stress['id'] ? 'active' : '' }}"
                                onclick="setStress('{{ $stress['id'] }}', this)">
                                <span class="selector-emoji"><i class="{{ $stress['icon'] }}"></i></span>
                                <span>{{ __($stress['label']) }}</span>
                                <span style="display:block; font-size:0.6rem; color:inherit; opacity:0.65; line-height:1.3; margin-top:2px;">{{ $stress['desc'] }}</span>
                            </button>
                        @endforeach
                    </div>
                    <x-input-error :messages="$errors->get('stress_level')" />
                </div>
            </aside>

            <div class="tracking-actions">
                <a href="{{ route('caregiver.dashboard', ['patient_id' => $patient->id]) }}" class="btn-track-reset text-center text-decoration-none d-flex align-items-center justify-content-center">{{ __('Cancelar') }}</a>
                <button type="submit" class="btn-track-save">{{ __('Guardar Registro') }}</button>
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
