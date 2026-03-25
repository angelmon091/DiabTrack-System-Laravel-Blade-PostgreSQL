@extends('layouts.admin')

@section('title', 'Crear Usuario - DiabTrack')

@section('content')
    <div class="admin-title-section animate-fade-in">
        <div class="mb-3">
            <a href="{{ route('admin.users.index') }}" class="text-decoration-none text-diab-primary fw-bold small">
                <i class="fa-solid fa-arrow-left me-1"></i> Volver al listado
            </a>
        </div>
        <h2 class="fw-extrabold mb-1 text-dark">Registrar Nuevo Usuario</h2>
        <p class="text-diab-text-secondary mb-0">Completa la información básica y asigna los privilegios correspondientes.</p>
    </div>

    <div class="admin-card p-4 p-md-5 mx-auto animate-fade-in" style="max-width: 900px; animation-delay: 0.1s;">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            
            <div class="row g-4 mb-5">
                <div class="col-md-6">
                    <x-admin-form-input name="name" label="Nombre Completo" placeholder="Ej. Juan Pérez" required="true" />
                </div>
                <div class="col-md-6">
                    <x-admin-form-input name="email" label="Correo Electrónico" type="email" placeholder="ejemplo@correo.com" required="true" />
                </div>
                <div class="col-md-6">
                    <x-admin-form-input name="password" label="Contraseña de Acceso" type="password" required="true" />
                </div>
                <div class="col-md-6">
                    <x-admin-form-input name="password_confirmation" label="Confirmar Contraseña" type="password" required="true" />
                </div>
            </div>

            <div class="mt-2 pt-4 border-top">
                <h5 class="fw-bold mb-4 text-dark"><i class="fa-solid fa-shield-halved me-2 text-diab-primary"></i>Configuración de Privilegios</h5>
                
                <div class="bg-diab-bg rounded-4 p-4 mb-4 border border-white shadow-sm">
                    <div class="form-check form-switch custom-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="is_admin" name="is_admin" value="1" {{ old('is_admin') ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold ms-2 text-dark" for="is_admin">Acceso Administrativo Completo</label>
                    </div>
                    <p class="text-diab-text-secondary small mb-0 mt-2 ms-5">
                        <i class="fa-solid fa-circle-exclamation me-1 text-diab-danger"></i> 
                        Habilitar esta opción permite al usuario gestionar configuraciones globales del sistema DiabTrack.
                    </p>
                </div>

                <div class="mb-5">
                    <label class="form-label fw-bold mb-3 text-dark">Roles del Sistema</label>
                    <div class="row g-3">
                        @forelse($roles as $role)
                            <div class="col-md-4">
                                <div class="diab-card h-100 p-3 shadow-none border position-relative hover-shadow-sm transition-all">
                                    <div class="form-check">
                                        <input class="form-check-input position-static me-2" type="checkbox" name="roles[]" value="{{ $role->id }}" id="role_{{ $role->id }}" 
                                               {{ in_array($role->id, old('roles', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label stretched-link" for="role_{{ $role->id }}">
                                            <div class="fw-bold text-dark">{{ ucfirst($role->name) }}</div>
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
                <button type="submit" class="btn btn-diab-primary px-5 shadow-sm">Guardar Usuario</button>
            </div>
        </form>
    </div>
@endsection
