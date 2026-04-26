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
                        </label>
                        <input type="range" name="glucose_level" class="tracking-range" min="40" max="300"
                            value="{{ old('glucose_level', 120) }}"
                            oninput="document.getElementById('glucose_val').innerText = this.value">
                        <x-input-error :messages="$errors->get('glucose_level')" />
                    </div>

                    <div class="tracking-field" style="margin-bottom: 0;">
                        <label>{{ __('Notas Adicionales') }} <span class="text-muted small fw-normal">(Opcional)</span>:</label>
                        <textarea name="notes" class="tracking-input" rows="3" placeholder="{{ __('Ej: El paciente mencionó sentirse mareado...') }}">{{ old('notes') }}</textarea>
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
                    <h3>{{ __('Nivel de Estrés Percibido') }}</h3>
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
                <a href="{{ route('caregiver.patient.show', $patient) }}" class="btn-track-reset text-center text-decoration-none d-flex align-items-center justify-content-center">{{ __('Cancelar') }}</a>
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
