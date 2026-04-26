@push('styles')
    @vite('resources/css/tracking.css')
@endpush

<x-guest-layout>

    <x-auth-card>
        <form method="POST" action="{{ route('onboarding.caregiver.store') }}">
            @csrf

            <div class="text-center mb-5 animate-fade-in">
                <div class="admin-card-icon-wrapper mx-auto bg-diab-warning-light">
                    <i class="fa-solid fa-hand-holding-heart fs-2 text-diab-warning"></i>
                </div>
                <h3 class="fw-extrabold text-diab-primary">Perfil de Cuidador</h3>
                <p class="text-diab-secondary small">Ayúdanos a personalizar las herramientas de supervisión.</p>
            </div>

            <div class="mb-4 animate-fade-in" style="animation-delay: 0.1s;">
                <x-input-label value="{{ __('Género') }}" class="text-uppercase extra-small fw-bold mb-3 text-center d-block" />
                <input type="hidden" name="gender" id="gender" value="{{ old('gender') }}">
                <div class="selector-grid">
                    <button type="button" class="selector-btn {{ old('gender') == 'Masculino' ? 'active' : '' }}" onclick="setGender('Masculino', this)">
                        <span class="selector-emoji"><i class="fa-solid fa-mars"></i></span>
                        <span>Masculino</span>
                    </button>
                    <button type="button" class="selector-btn {{ old('gender') == 'Femenino' ? 'active' : '' }}" onclick="setGender('Femenino', this)">
                        <span class="selector-emoji"><i class="fa-solid fa-venus"></i></span>
                        <span>Femenino</span>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('gender')" />
            </div>

            <div class="mb-5 animate-fade-in" style="animation-delay: 0.2s;">
                <x-input-label value="{{ __('Parentesco con el paciente') }}" class="text-uppercase extra-small fw-bold mb-2" />
                <div class="input-group">
                    <select name="relationship" class="full-select">
                        <option value="">Selecciona una opción</option>
                        <option value="Padre/Madre" {{ old('relationship') == 'Padre/Madre' ? 'selected' : '' }}>Padre / Madre</option>
                        <option value="Hijo/a" {{ old('relationship') == 'Hijo/a' ? 'selected' : '' }}>Hijo / a</option>
                        <option value="Hermano/a" {{ old('relationship') == 'Hermano/a' ? 'selected' : '' }}>Hermano / a</option>
                        <option value="Pareja" {{ old('relationship') == 'Pareja' ? 'selected' : '' }}>Pareja</option>
                        <option value="Médico Particular" {{ old('relationship') == 'Médico Particular' ? 'selected' : '' }}>Médico Particular</option>
                        <option value="Otro" {{ old('relationship') == 'Otro' ? 'selected' : '' }}>Otro</option>
                    </select>
                </div>
                <x-input-error :messages="$errors->get('relationship')" />
            </div>

            <button type="submit" class="btn-primary animate-fade-in" style="animation-delay: 0.3s;">
                {{ __('Completar Registro') }}
            </button>
        </form>
    </x-auth-card>
</x-guest-layout>

<script>
    function setGender(val, btn) {
        document.getElementById('gender').value = val;
        const container = btn.closest('.selector-grid');
        container.querySelectorAll('.selector-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
    }
</script>
