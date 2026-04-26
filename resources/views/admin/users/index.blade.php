@extends('layouts.admin')

@section('title', 'Control de Usuarios - DiabTrack')

@section('content')
    <div class="admin-title-section d-flex justify-content-between align-items-center animate-fade-in">
        <div>
            <h2 class="fw-extrabold mb-1 text-dark">Control de Usuarios</h2>
            <p class="text-diab-text-secondary mb-0">Gestiona los accesos, roles y permisos de los integrantes del sistema.</p>
        </div>
        
        <a href="{{ route('admin.users.create') }}" class="btn btn-diab-primary shadow-sm">
            <i class="fa-solid fa-plus me-2"></i> Nuevo Usuario
        </a>
    </div>

    <!-- Buscador -->
    <div class="diab-card mb-5 p-4 animate-fade-in" style="animation-delay: 0.1s;">

        <form action="{{ route('admin.users.index') }}" method="GET" class="row g-3">
            <div class="col-12 col-md-8">
                <div class="position-relative">
                    <i class="fa-solid fa-search position-absolute text-muted" style="top: 50%; left: 18px; transform: translateY(-50%);"></i>
                    <input type="text" name="search" class="form-control diab-input ps-5" placeholder="Buscar por nombre o correo electrónico..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-12 col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-diab-primary flex-grow-1">Buscar</button>
                @if(request('search'))
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary px-3">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Lista -->
    <div class="animate-fade-in" style="animation-delay: 0.2s;">
        <x-admin-table :headers="['Usuario', 'Correo Electrónico', 'Tipo de Cuenta', 'Roles Asignados', 'Acciones']">
            @forelse ($users as $user)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-diab-primary-light text-diab-primary rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 40px; height: 40px; font-size: 0.9rem;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="fw-bold text-dark">{{ $user->name }}</div>
                                @if ($user->id === auth()->id())
                                    <span class="badge bg-secondary-subtle text-secondary rounded-pill" style="font-size: 0.65rem;">SESIÓN ACTUAL</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="text-diab-text-secondary">{{ $user->email }}</td>
                    <td>
                        @if ($user->is_admin)
                            <span class="badge badge-role bg-diab-danger-light text-diab-danger border-0">Administrador</span>
                        @else
                            <span class="badge badge-role bg-diab-primary-light text-diab-primary border-0">Estándar</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex flex-wrap gap-1">
                            @forelse ($user->roles as $role)
                                <span class="badge badge-role bg-diab-info-light text-diab-info border-0">
                                    {{ $role->name }}
                                </span>
                            @empty
                                <span class="text-muted small fst-italic">Sin roles</span>
                            @endforelse
                        </div>
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn-action" title="Editar Perfil">
                                <i class="fa-solid fa-pen text-diab-primary"></i>
                            </a>
                            
                            @if ($user->id !== auth()->id())
                                <button type="button" class="btn-action" title="Eliminar Usuario" data-bs-toggle="modal" data-bs-target="#deleteUserModal{{ $user->id }}">
                                    <i class="fa-solid fa-trash text-diab-danger"></i>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-5">
                        <div class="mb-4 text-muted opacity-25">
                            <i class="fa-solid fa-users-slash display-1"></i>
                        </div>
                        <h5 class="text-muted fw-bold">No se encontraron usuarios</h5>
                        <p class="text-muted small">Intenta ajustar los criterios de búsqueda o limpia los filtros.</p>
                        @if(request('search'))
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm mt-3 rounded-pill">Limpiar búsqueda</a>
                        @endif
                    </td>
                </tr>
            @endforelse
        </x-admin-table>
    </div>

    <!-- Paginación -->
    @if ($users->hasPages())
        <div class="mt-5 d-flex justify-content-center">
            {{ $users->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    @endif

@push('modals')
    <!-- Modales de Eliminación (Fuera de la tabla para evitar conflictos de z-index) -->
    @foreach ($users as $user)
        @if ($user->id !== auth()->id())
            <x-admin-modal id="deleteUserModal{{ $user->id }}" title="Confirmar Eliminación">
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
    @endforeach
@endpush
@endsection
