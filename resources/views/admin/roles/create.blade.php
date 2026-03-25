@extends('layouts.admin')

@section('title', 'Crear Rol - DiabTrack')

@section('content')
    <div class="admin-title-section animate-fade-in">
        <div class="mb-3">
            <a href="{{ route('admin.roles.index') }}" class="text-decoration-none text-diab-primary fw-bold small">
                <i class="fa-solid fa-arrow-left me-1"></i> Volver al listado
            </a>
        </div>
        <h2 class="fw-extrabold mb-1 text-dark">Registrar Nuevo Rol</h2>
        <p class="text-diab-text-secondary mb-0">Define un nuevo nivel de acceso para agrupar permisos en el sistema DiabTrack.</p>
    </div>

    <div class="admin-card p-4 mx-auto animate-fade-in" style="max-width: 600px; animation-delay: 0.1s;">
        <form action="{{ route('admin.roles.store') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <x-admin-form-input name="name" label="Nombre del Rol" placeholder="Ej. Nutricionista, Médico, VIP..." required="true" />
            </div>
            
            <div class="mb-4">
                <label for="description" class="form-label fw-bold mb-2 text-dark">Descripción (Opcional)</label>
                <textarea class="form-control diab-input @error('description') is-invalid @enderror" 
                          id="description" name="description" rows="4" 
                          placeholder="Breve descripción de los privilegios y alcances de este rol... Ej. 'Acceso completo al módulo de reportes nutricionales.'">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback mt-2 fw-medium"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-end gap-3 mt-5 pt-4 border-top">
                <a href="{{ route('admin.roles.index') }}" class="btn btn-light px-4 border text-diab-text-secondary">Cancelar</a>
                <button type="submit" class="btn btn-diab-primary px-5 shadow-sm">Crear Rol</button>
            </div>
        </form>
    </div>
@endsection
