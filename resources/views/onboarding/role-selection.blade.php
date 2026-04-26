<x-guest-layout>
    <x-auth-card>
        <div class="text-center mb-5 animate-fade-in">
            <h2 class="fw-extrabold text-diab-primary mb-2">{{ __('Bienvenido a DiabTrack') }}</h2>
            <p class="text-diab-secondary small">{{ __('Para comenzar, selecciona el rol que mejor te describa.') }}</p>
        </div>

        <div class="d-flex flex-column">
            {{-- Paciente --}}
            <div class="animate-fade-in mb-4" style="animation-delay: 0.1s;">
                <a href="{{ route('onboarding.patient') }}" class="btn-social text-decoration-none py-4 px-4 h-auto d-flex align-items-center text-start">
                    <div class="admin-card-icon-wrapper mb-0 me-4 bg-diab-primary-light" style="width: 50px; height: 50px; border-radius: 12px;">
                        <i class="fa-solid fa-heart-pulse fs-4 text-diab-primary"></i>
                    </div>
                    <div class="flex-grow-1">
                        <strong class="d-block text-dark">{{ __('Soy Paciente') }}</strong>
                        <p class="mb-0 extra-small text-muted">{{ __('Gestiona tu glucosa, alimentación y actividad con IA.') }}</p>
                    </div>
                </a>
            </div>

            {{-- Cuidador --}}
            <div class="animate-fade-in mb-4" style="animation-delay: 0.2s;">
                <a href="{{ route('onboarding.caregiver') }}" class="btn-social text-decoration-none py-4 px-4 h-auto d-flex align-items-center text-start">
                    <div class="admin-card-icon-wrapper mb-0 me-4 bg-diab-warning-light" style="width: 50px; height: 50px; border-radius: 12px;">
                        <i class="fa-solid fa-hand-holding-heart fs-4 text-diab-warning"></i>
                    </div>
                    <div class="flex-grow-1">
                        <strong class="d-block text-dark">{{ __('Soy Cuidador') }}</strong>
                        <p class="mb-0 extra-small text-muted">{{ __('Supervisa y acompaña a tus seres queridos en su salud.') }}</p>
                    </div>
                </a>
            </div>

            {{-- Médico --}}
            <div class="animate-fade-in mb-4" style="animation-delay: 0.3s;">
                <a href="{{ route('onboarding.doctor') }}" class="btn-social text-decoration-none py-4 px-4 h-auto d-flex align-items-center text-start">
                    <div class="admin-card-icon-wrapper mb-0 me-4 bg-diab-info-light" style="width: 50px; height: 50px; border-radius: 12px;">
                        <i class="fa-solid fa-user-doctor fs-4 text-diab-info"></i>
                    </div>
                    <div class="flex-grow-1">
                        <strong class="d-block text-dark">{{ __('Soy Médico') }}</strong>
                        <p class="mb-0 extra-small text-muted">{{ __('Monitorea métricas clínicas y ajusta metas terapéuticas.') }}</p>
                    </div>
                </a>
            </div>
        </div>
    </x-auth-card>
</x-guest-layout>
