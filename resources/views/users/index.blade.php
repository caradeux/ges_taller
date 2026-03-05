@extends('layouts.app')

@section('title', 'Usuarios')

@section('content')
<div class="animate-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="outfit fw-bold mb-1">Usuarios del Sistema</h2>
            <p class="text-muted mb-0">Administra el acceso al sistema</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('users.permissions') }}" class="btn btn-outline-secondary rounded-pill px-4">
                <i class="bi bi-shield-check"></i> Ver Permisos
            </a>
            <a href="{{ route('users.create') }}" class="btn-primary-premium">
                <i class="bi bi-person-plus-fill"></i> Nuevo Usuario
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger border-0 shadow-sm mb-4" style="border-radius:1rem;">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ $errors->first() }}
        </div>
    @endif

    <div class="card">
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center"
                                    style="width:36px;height:36px;background:{{ $user->active ? '#dbeafe' : '#f1f5f9' }};flex-shrink:0;">
                                    <i class="bi bi-person-fill" style="color:{{ $user->active ? '#2563eb' : '#94a3b8' }};font-size:1rem;"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">{{ $user->name }}</div>
                                    @if($user->id === auth()->id())
                                        <small class="text-muted">Tú</small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="text-muted">{{ $user->email }}</td>
                        <td>
                            @php
                                $roleObj = $roles->firstWhere('name', $user->role);
                            @endphp
                            @if($roleObj)
                                <span class="badge rounded-pill px-3"
                                      style="background:{{ $roleObj->badge_color }};color:#fff;">
                                    {{ $roleObj->label }}
                                </span>
                            @else
                                <span class="badge bg-secondary rounded-pill px-3">{{ $user->role }}</span>
                            @endif
                        </td>
                        <td>
                            @if($user->active)
                                <span class="badge bg-success rounded-pill px-3">Activo</span>
                            @else
                                <span class="badge bg-secondary rounded-pill px-3">Inactivo</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light rounded-pill px-3" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('users.edit', $user) }}">
                                            <i class="bi bi-pencil me-2 text-primary"></i>Editar
                                        </a>
                                    </li>
                                    @if($user->id !== auth()->id())
                                    <li>
                                        <form method="POST" action="{{ route('users.toggle', $user) }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                @if($user->active)
                                                    <i class="bi bi-pause-circle me-2 text-warning"></i>Desactivar
                                                @else
                                                    <i class="bi bi-play-circle me-2 text-success"></i>Activar
                                                @endif
                                            </button>
                                        </form>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('users.destroy', $user) }}"
                                            onsubmit="return confirm('¿Eliminar usuario {{ $user->name }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="bi bi-trash me-2"></i>Eliminar
                                            </button>
                                        </form>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-5">No hay usuarios registrados.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
