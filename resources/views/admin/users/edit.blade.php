@extends('layouts.admin')

@section('title', 'Editar Usuario - DiabTrack')

@section('content')
    <div class="admin-title-section animate-fade-in">
        <div class="mb-3">
            <a href="{{ route('admin.users.index') }}" class="text-decoration-none text-diab-primary fw-bold small">
                <i class="fa-solid fa-arrow-left me-1"></i> Volver al listado
            </a>
        </div>
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="fw-extrabold mb-1 text-dark">Editar Usuario: {{ $user->name }}</h2>
                <p class="text-diab-text-secondary mb-0">Actualiza la información personal y los permisos de acceso al sistema.</p>
            </div>
            @if ($user->id !== auth()->id())
                <button type="button" class="btn btn-diab-danger shadow-sm" data-bs-toggle="modal" data-bs-target="#deleteUserModal">
                    <i class="fa-solid fa-trash me-2"></i> Eliminar Usuario
                </button>
            @endif
        </div>
    </div>

@push('modals')
    <!-- Modal Eliminar -->
    @if ($user->id !== auth()->id())
        <x-admin-modal id="deleteUserModal" title="Confirmar Eliminación">
            <div class="text-center p-3">
                <div class="mb-4 text-diab-danger">
                    <i class="fa-solid fa-triangle-exclamation display-3 opacity-25"></i>
                </div>
                <h5 class="fw-bold mb-3">¿Eliminar a {{ $user->name }}?</h5>
                <p class="text-muted">Esta acción es irreversible y se perderán todos los datos y registros asociados a este usuario permanentemente.</p>
                
                <div class="mt-5 d-flex justify-content-center gap-3">
                    <button type="button" class="btn btn-light px-4 border" data-bs-dismiss="modal">Cancelar</button>
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-diab-danger px-4 shadow-sm">
                            Eliminar Definitivamente
                        </button>
                    </form>
                </div>
            </div>
        </x-admin-modal>
    @endif
@endpush

    <div class="diab-card p-4 p-md-5 mx-auto animate-fade-in" style="max-width: 900px; animation-delay: 0.1s;">
        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <x-admin-form-input name="name" label="Nombre Completo" value="{{ $user->name }}" required="true" />
                </div>
                <div class="col-md-6">
                    <x-admin-form-input name="email" label="Correo Electrónico" type="email" value="{{ $user->email }}" required="true" />
                </div>
            </div>
            
            <div class="bg-diab-info-light border-0 rounded-4 p-3 mb-5 d-flex align-items-center gap-3">
                <i class="fa-solid fa-circle-info text-diab-info fs-4"></i> 
                <p class="mb-0 text-diab-info small fw-medium">
                    Deja los campos de contraseña en blanco si no deseas realizar cambios en la seguridad de la cuenta.
                </p>
            </div>

            <div class="row g-4 mb-5">
                <div class="col-md-6">
                    <x-admin-form-input name="password" label="Nueva Contraseña" type="password" />
                </div>
                <div class="col-md-6">
                    <x-admin-form-input name="password_confirmation" label="Confirmar Nueva Contraseña" type="password" />
                </div>
            </div>

            <div class="mt-2 pt-4 border-top">
                <h5 class="fw-bold mb-4 text-dark"><i class="fa-solid fa-shield-halved me-2 text-diab-primary"></i>Gestión de Privilegios</h5>
                
                <div class="diab-card p-4 mb-4 border-white shadow-sm" style="background: rgba(255,255,255,0.4) !important;">
                    <div class="form-check form-switch custom-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="is_admin" name="is_admin" value="1" 
                               {{ old('is_admin', $user->is_admin) ? 'checked' : '' }}
                               {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                        <label class="form-check-label fw-bold ms-2 text-dark" for="is_admin">Acceso Administrativo Completo</label>
                    </div>
                    @if($user->id === auth()->id())
                        <p class="text-diab-text-secondary small mb-0 mt-2 ms-5">
                            <i class="fa-solid fa-lock me-1"></i> Por seguridad, no puedes revocar tus propios permisos administrativos.
                        </p>
                    @else
                        <p class="text-diab-text-secondary small mb-0 mt-2 ms-5">
                            <i class="fa-solid fa-circle-exclamation me-1 text-diab-danger"></i> 
                            Habilitar esta opción permite al usuario gestionar configuraciones globales del sistema DiabTrack.
                        </p>
                    @endif
                </div>

                <div class="mb-5">
                    <label class="form-label fw-bold mb-3 text-dark">Roles Asignados</label>
                    <div class="row g-3">
                        @forelse($roles as $role)
                            <div class="col-md-4">
                                @php $isActive = in_array($role->id, old('roles', $userRoles)); @endphp
                                <div class="diab-card h-100 p-3 shadow-none border position-relative hover-shadow-sm transition-all {{ $isActive ? 'border-diab-primary' : '' }}" 
                                     style="{{ $isActive ? 'background: var(--diab-primary-light);' : '' }}">
                                    <div class="form-check">
                                        <input class="form-check-input position-static me-2" type="checkbox" name="roles[]" value="{{ $role->id }}" id="role_{{ $role->id }}" 
                                               {{ $isActive ? 'checked' : '' }}>
                                        <label class="form-check-label stretched-link" for="role_{{ $role->id }}">
                                            <div class="fw-bold {{ $isActive ? 'text-diab-primary' : 'text-dark' }}">{{ ucfirst($role->name) }}</div>
                                            @if($role->description)
                                                <div class="extra-small text-muted mt-1 lh-sm">{{ $role->description }}</div>
                                            @endif
                                        </label>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 py-3 text-center bg-light rounded-3 text-muted fst-italic">
                                <i class="fa-solid fa-info-circle me-1"></i> No hay roles adicionales definidos.
                            </div>
                        @endforelse
                    </div>
                    @error('roles')
                        <div class="text-diab-danger small mt-2 fw-bold"><i class="fa-solid fa-xmark-circle me-1"></i>{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-end gap-3 mt-5 pt-4 border-top">
                <a href="{{ route('admin.users.index') }}" class="btn btn-light px-4 border text-diab-text-secondary">Cancelar</a>
                <button type="submit" class="btn btn-diab-primary px-5 shadow-sm">Guardar Cambios</button>
            </div>
        </form>
    </div>
@endsection
