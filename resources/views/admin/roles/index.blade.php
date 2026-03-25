@extends('layouts.admin')

@section('title', 'Control de Roles - DiabTrack')

@section('content')
    <div class="admin-title-section d-flex justify-content-between align-items-center animate-fade-in">
        <div>
            <h2 class="fw-extrabold mb-1 text-dark">Roles y Privilegios</h2>
            <p class="text-diab-text-secondary mb-0">Administra los roles disponibles en el sistema y define sus alcances.</p>
        </div>
        
        <a href="{{ route('admin.roles.create') }}" class="btn btn-diab-primary shadow-sm">
            <i class="fa-solid fa-plus me-2"></i> Nuevo Rol
        </a>
    </div>

    <!-- Lista -->
    <div class="animate-fade-in" style="animation-delay: 0.1s;">
        <x-admin-table :headers="['Nombre del Rol', 'Descripción Detallada', 'Usuarios Activos', 'Fecha de Registro', 'Acciones']">
            @forelse ($roles as $role)
                <tr>
                    <td class="fw-bold">
                        <span class="badge badge-role bg-diab-primary-light text-diab-primary border-0 px-3 py-2">
                            {{ ucfirst($role->name) }}
                        </span>
                    </td>
                    <td class="text-diab-text-secondary" style="max-width: 350px;">
                        <span class="d-inline-block text-truncate w-100" title="{{ $role->description }}">
                            {{ $role->description ?: 'Sin descripción detallada' }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="bg-diab-bg p-2 rounded-circle" style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
                                <i class="fa-solid fa-users text-diab-text-muted opacity-75"></i>
                            </div>
                            <span class="fw-extrabold text-dark">{{ $role->users_count }}</span>
                        </div>
                    </td>
                    <td class="text-diab-text-secondary small">
                        {{ $role->created_at->format('d M, Y') }}
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.roles.edit', $role) }}" class="btn-action" title="Editar Rol">
                                <i class="fa-solid fa-pen text-diab-primary"></i>
                            </a>
                            
                            <button type="button" class="btn-action" title="Eliminar Rol" data-bs-toggle="modal" data-bs-target="#deleteRoleModal{{ $role->id }}">
                                <i class="fa-solid fa-trash text-diab-danger"></i>
                            </button>
                            
                            <!-- Modal Eliminar -->
                            <x-admin-modal id="deleteRoleModal{{ $role->id }}" title="Confirmar Eliminación">
                                <div class="text-center p-3">
                                    <div class="mb-4 text-diab-danger">
                                        <i class="fa-solid fa-shield-halved display-3 opacity-25"></i>
                                    </div>
                                    <h5 class="fw-bold mb-3">¿Eliminar el rol {{ ucfirst($role->name) }}?</h5>
                                    
                                    @if($role->users_count > 0)
                                        <div class="alert bg-diab-warning-light text-diab-warning border-0 text-start">
                                            <div class="d-flex gap-3">
                                                <i class="fa-solid fa-triangle-exclamation fs-4"></i>
                                                <div>
                                                    <strong class="d-block mb-1">Rol en uso activo</strong>
                                                    <p class="mb-0 small">Este rol tiene <strong>{{ $role->users_count }} usuarios</strong> asignados. No puedes eliminar un rol mientras tenga personal activo vinculado.</p>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <p class="text-muted">Esta acción removerá el privilegio del sistema. Asegúrate de que no sea necesario para futuras configuraciones.</p>
                                    @endif
                                    
                                    <div class="mt-5 d-flex justify-content-center gap-3">
                                        <button type="button" class="btn btn-light px-4 border" data-bs-dismiss="modal">Cancelar</button>
                                        
                                        @if($role->users_count == 0)
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
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-5">
                        <div class="mb-4 text-muted opacity-25">
                            <i class="fa-solid fa-shield-slash display-1"></i>
                        </div>
                        <h5 class="text-muted fw-bold">No se encontraron roles</h5>
                        <p class="text-muted small">El sistema requiere al menos un rol para la gestión de accesos.</p>
                    </td>
                </tr>
            @endforelse
        </x-admin-table>
    </div>

    <!-- Paginación -->
    @if ($roles->hasPages())
        <div class="mt-5 d-flex justify-content-center">
            {{ $roles->links('pagination::bootstrap-5') }}
        </div>
    @endif
@endsection
