@extends('layouts.admin')

@section('title', 'Editar Rol - DiabTrack')

@section('content')
    <div class="admin-title-section animate-fade-in">
        <div class="mb-3">
            <a href="{{ route('admin.roles.index') }}" class="text-decoration-none text-diab-primary fw-bold small">
                <i class="fa-solid fa-arrow-left me-1"></i> Volver al listado
            </a>
        </div>
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="fw-extrabold mb-1 text-dark">Editar Rol: {{ $role->name }}</h2>
                <p class="text-diab-text-secondary mb-0">Modifica el nombre y la descripción de este rol del sistema.</p>
            </div>
            
            <button type="button" class="btn btn-diab-danger shadow-sm" data-bs-toggle="modal" data-bs-target="#deleteRoleModal">
                <i class="fa-solid fa-trash me-2"></i> Eliminar Rol
            </button>
        </div>
    </div>

    <!-- Modal Eliminar -->
    <x-admin-modal id="deleteRoleModal" title="Confirmar Eliminación">
        <div class="text-center p-3">
            <div class="mb-4 text-diab-danger">
                <i class="fa-solid fa-shield-halved display-3 opacity-25"></i>
            </div>
            <h5 class="fw-bold mb-3">¿Eliminar el rol {{ ucfirst($role->name) }}?</h5>
            
            @if($role->users()->count() > 0)
                <div class="alert bg-diab-warning-light text-diab-warning border-0 text-start">
                    <div class="d-flex gap-3">
                        <i class="fa-solid fa-triangle-exclamation fs-4"></i>
                        <div>
                            <strong class="d-block mb-1">Rol en uso activo</strong>
                            <p class="mb-0 small">Este rol tiene <strong>{{ $role->users()->count() }} usuarios</strong> asignados. No puedes eliminar un rol mientras tenga personal activo vinculado.</p>
                        </div>
                    </div>
                </div>
            @else
                <p class="text-muted">Esta acción removerá el privilegio del sistema. Asegúrate de que no sea necesario para futuras configuraciones.</p>
            @endif
            
            <div class="mt-5 d-flex justify-content-center gap-3">
                <button type="button" class="btn btn-light px-4 border" data-bs-dismiss="modal">Cancelar</button>
                
                @if($role->users()->count() == 0)
                    <form action="{{ route('admin.roles.destroy', $role) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-diab-danger px-4 shadow-sm">
                            Confirmar Eliminación
                        </button>
                    </form>
                @else
                    <button type="button" class="btn btn-diab-danger px-4 shadow-sm" disabled>
                        Acción Bloqueada
                    </button>
                @endif
            </div>
        </div>
    </x-admin-modal>

    <div class="admin-card p-4 mx-auto animate-fade-in" style="max-width: 600px; animation-delay: 0.1s;">
        <form action="{{ route('admin.roles.update', $role) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <x-admin-form-input name="name" label="Nombre del Rol" value="{{ $role->name }}" required="true" />
            </div>
            
            <div class="mb-4">
                <label for="description" class="form-label fw-bold mb-2 text-dark">Descripción (Opcional)</label>
                <textarea class="form-control diab-input @error('description') is-invalid @enderror" 
                          id="description" name="description" rows="4" 
                          placeholder="Breve descripción de los alcances funcionales de este rol...">{{ old('description', $role->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback mt-2 fw-medium"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-end gap-3 mt-5 pt-4 border-top">
                <a href="{{ route('admin.roles.index') }}" class="btn btn-light px-4 border text-diab-text-secondary">Cancelar</a>
                <button type="submit" class="btn btn-diab-primary px-5 shadow-sm">Guardar Cambios</button>
            </div>
        </form>
    </div>
@endsection
