@push('styles')
    @vite('resources/css/tracking.css')
@endpush

<x-guest-layout>
    <x-auth-card>
        <form method="POST" action="{{ route('onboarding.doctor.store') }}">
            @csrf

            <div class="text-center mb-5 animate-fade-in">
                <div class="admin-card-icon-wrapper mx-auto bg-diab-info-light">
                    <i class="fa-solid fa-user-doctor fs-2 text-diab-info"></i>
                </div>
                <h3 class="fw-extrabold text-diab-primary">Perfil Profesional</h3>
                <p class="text-diab-secondary small">Habilita tus herramientas de monitoreo clínico.</p>
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

            <div class="mb-4 animate-fade-in" style="animation-delay: 0.2s;">
                <x-input-label value="{{ __('Cédula Profesional') }}" class="text-uppercase extra-small fw-bold mb-2" />
                <x-text-input type="text" name="license_number" :value="old('license_number')" placeholder="Ej. 12345678" />
                <x-input-error :messages="$errors->get('license_number')" />
            </div>

            <div class="mb-5 animate-fade-in" style="animation-delay: 0.3s;">
                <x-input-label value="{{ __('Especialidad') }}" class="text-uppercase extra-small fw-bold mb-2" />
                <div class="input-group">
                    <select name="specialty" class="full-select">
                        <option value="">Selecciona especialidad</option>
                        <option value="Endocrinología" {{ old('specialty') == 'Endocrinología' ? 'selected' : '' }}>Endocrinología</option>
                        <option value="Medicina Interna" {{ old('specialty') == 'Medicina Interna' ? 'selected' : '' }}>Medicina Interna</option>
                        <option value="Nutrición Clínica" {{ old('specialty') == 'Nutrición Clínica' ? 'selected' : '' }}>Nutrición Clínica</option>
                        <option value="Medicina General" {{ old('specialty') == 'Medicina General' ? 'selected' : '' }}>Medicina General</option>
                        <option value="Pediatría" {{ old('specialty') == 'Pediatría' ? 'selected' : '' }}>Pediatría</option>
                    </select>
                </div>
                <x-input-error :messages="$errors->get('specialty')" />
            </div>

            <button type="submit" class="btn-primary animate-fade-in" style="animation-delay: 0.4s;">
                {{ __('Activar Perfil Médico') }}
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
