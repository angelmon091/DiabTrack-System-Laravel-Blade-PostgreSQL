<section>
    <header class="mb-4">
        <h3 class="fw-bold text-dark fs-5">
            <i class="fa-solid fa-id-card me-2 text-diab-primary"></i> {{ __('Información del Perfil') }}
        </h3>

        <p class="mt-1 small text-muted">
            {{ __("Actualiza los datos básicos de tu cuenta y tu dirección de correo electrónico.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div class="mb-4 d-flex align-items-center gap-4">
            <div class="user-avatar rounded-circle overflow-hidden shadow-sm flex-shrink-0" style="width: 80px; height: 80px;">
                @php
                    $gender = strtolower($user->patientProfile->gender ?? '');
                    $avatar = $user->avatar;
                @endphp
                @if($avatar && str_starts_with($avatar, 'http'))
                    <img src="{{ $avatar }}" alt="User" class="w-100 h-100 object-fit-cover">
                @elseif($avatar)
                    <img src="{{ asset('storage/' . $avatar) }}" alt="User" class="w-100 h-100 object-fit-cover">
                @elseif($gender === 'femenino')
                    <div class="w-100 h-100 d-flex align-items-center justify-content-center text-white" style="background: linear-gradient(135deg, #FF6B6B, #C0392B);">
                        <i class="fa-solid fa-person-dress fs-1"></i>
                    </div>
                @elseif($gender === 'masculino')
                    <div class="w-100 h-100 d-flex align-items-center justify-content-center text-white" style="background: linear-gradient(135deg, #4A90E2, #2980B9);">
                        <i class="fa-solid fa-user-tie fs-1"></i>
                    </div>
                @else
                    <div class="w-100 h-100 d-flex align-items-center justify-content-center text-white bg-secondary">
                        <i class="fa-solid fa-user fs-1"></i>
                    </div>
                @endif
            </div>
            <div class="flex-grow-1">
                <label class="form-label small fw-bold text-muted text-uppercase" for="avatar">{{ __('Foto de Perfil') }}</label>
                <input id="avatar" name="avatar" type="file" class="form-control diab-input" accept="image/jpeg, image/png, image/webp" />
                <p class="text-muted extra-small mt-1 mb-0">Recomendado: 150x150. Max: 5MB.</p>
                @if($errors->has('avatar'))
                    <span class="text-danger extra-small">{{ $errors->first('avatar') }}</span>
                @endif
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label small fw-bold text-muted text-uppercase" for="name">{{ __('Nombre completo') }}</label>
            <input id="name" name="name" type="text" class="form-control diab-input" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
            @if($errors->has('name'))
                <span class="text-danger extra-small">{{ $errors->first('name') }}</span>
            @endif
        </div>

        <div class="mb-4">
            <label class="form-label small fw-bold text-muted text-uppercase" for="email">{{ __('Correo Electrónico') }}</label>
            <input id="email" name="email" type="email" class="form-control diab-input" value="{{ old('email', $user->email) }}" required autocomplete="username" />
            @if($errors->has('email'))
                <span class="text-danger extra-small">{{ $errors->first('email') }}</span>
            @endif

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2">
                    <p class="extra-small text-dark">
                        {{ __('Tu dirección de correo no está verificada.') }}
                        <button form="send-verification" class="btn btn-link p-0 extra-small text-decoration-underline text-muted">
                            {{ __('Haz clic aquí para re-enviar el correo de verificación.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 fw-medium extra-small text-success">
                            {{ __('Se ha enviado un nuevo enlace de verificación a tu correo.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="d-flex align-items-center gap-4 mt-4">
            <button type="submit" class="btn-diab-primary shadow-sm">{{ __('Guardar Cambios') }}</button>

            @if (session('status') === 'profile-updated')
                <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="small text-success fw-semibold animate-fade-in">
                    <i class="fa-solid fa-circle-check me-1"></i> {{ __('Perfil actualizado con éxito.') }}
                </div>
            @endif
        </div>
    </form>
</section>

