@extends('layouts.app')

@section('title', 'Roles del Sistema')

@section('styles')
<style>
    .role-card {
        border-radius: 1rem;
        border: 1px solid var(--border-light);
        background: white;
        box-shadow: var(--card-shadow);
        overflow: hidden;
        transition: var(--transition);
    }
    .role-card:hover { box-shadow: var(--shadow-md); transform: translateY(-2px); }
    .role-card-header { padding: 1.5rem 1.5rem 1.25rem; border-bottom: 1px solid var(--border-light); }
    .role-icon {
        width: 48px; height: 48px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.25rem; color: white; flex-shrink: 0;
    }
    .role-badge {
        display: inline-flex; align-items: center; padding: 0.2rem 0.625rem;
        border-radius: 9999px; font-size: 0.68rem; font-weight: 700;
        letter-spacing: 0.04em; color: white;
    }
    .perm-row {
        display: flex; justify-content: space-between; align-items: center;
        padding: 0.45rem 0; border-bottom: 1px solid var(--border-light); font-size: 0.8rem;
    }
    .perm-row:last-child { border-bottom: none; }
    .perm-label { color: var(--text-secondary); font-weight: 500; }
    .perm-yes  { color: var(--success); font-weight: 600; }
    .perm-no   { color: var(--text-muted); }
    .perm-text { color: var(--primary); font-weight: 600; }
    .user-counter { display: flex; align-items: baseline; gap: 4px; }
    .user-counter strong { font-size: 1.75rem; font-weight: 800; letter-spacing: -0.04em; }
    .user-counter span   { font-size: 0.72rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.04em; }
    .perm-group-check {
        display: flex; align-items: flex-start; gap: 10px;
        padding: 8px 10px; border-radius: 8px; cursor: pointer; transition: background .12s;
    }
    .perm-group-check:hover { background: var(--primary-light); }
    .perm-group-check input[type=checkbox] { margin-top: 2px; flex-shrink: 0; width: 16px; height: 16px; cursor: pointer; }
    .perm-group-check .pgi { font-size: 1rem; color: var(--primary); flex-shrink: 0; margin-top: 1px; }
    .perm-group-check .pgl { font-size: 0.82rem; font-weight: 600; color: var(--text-primary); }
</style>
@endsection

@section('content')
<div class="animate-in">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-2">
        <div>
            <h2 class="page-title mb-1">Roles del Sistema</h2>
            <p class="page-subtitle">Gestiona los perfiles de acceso y sus permisos.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('users.permissions') }}" class="btn-app-secondary">
                <i class="bi bi-shield-check"></i> Ver permisos detallados
            </a>
            <button type="button" class="btn-primary-premium" data-bs-toggle="modal" data-bs-target="#createRoleModal">
                <i class="bi bi-plus-lg"></i> Nuevo Rol
            </button>
        </div>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success mb-4">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger mb-4">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
        </div>
    @endif

    {{-- Info Banner --}}
    <div class="card p-3 mb-4 d-flex flex-row align-items-center gap-3"
         style="background:var(--primary-light);border-color:var(--primary-border);">
        <i class="bi bi-info-circle-fill" style="color:var(--primary);font-size:1.2rem;flex-shrink:0;"></i>
        <p class="mb-0 text-sm" style="color:var(--primary);">
            Los tres roles del sistema (<strong>admin, recepción, taller</strong>) son fijos y no pueden eliminarse.
            Puedes crear roles personalizados con los permisos que necesites.
        </p>
    </div>

    {{-- Role Cards --}}
    <div class="row g-4">
        @foreach($roles as $role)
        @php
            $icons = ['admin' => 'bi-shield-fill-check', 'recepcion' => 'bi-telephone-fill', 'taller' => 'bi-wrench-adjustable'];
            $icon  = $icons[$role->name] ?? 'bi-person-badge-fill';
            $perms = $permissionSummary[$role->name] ?? [];
        @endphp

        <div class="col-xl-4 col-md-6">
            <div class="role-card h-100 d-flex flex-column">

                {{-- Card Header --}}
                <div class="role-card-header">
                    <div class="d-flex align-items-start gap-3">
                        <div class="role-icon" style="background:{{ $role->badge_color }};">
                            <i class="bi {{ $icon }}"></i>
                        </div>
                        <div class="flex-grow-1 min-w-0">
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <h5 class="fw-bold mb-0" style="font-size:1.05rem;">{{ $role->label }}</h5>
                                <span class="role-badge" style="background:{{ $role->badge_color }};">
                                    {{ $role->name }}
                                </span>
                                @if($role->is_system)
                                <span class="badge bg-light text-muted border" style="font-size:0.6rem;padding:2px 7px;">Sistema</span>
                                @else
                                <span class="badge border" style="font-size:0.6rem;padding:2px 7px;background:#fef3c7;color:#92400e;border-color:#fde68a!important;">Personalizado</span>
                                @endif
                            </div>
                            <p class="mb-0 mt-1 text-sm" style="color:var(--text-secondary);line-height:1.5;">
                                {{ $role->description ?? 'Sin descripción.' }}
                            </p>
                        </div>
                    </div>

                    <div class="d-flex gap-4 mt-3 pt-3" style="border-top:1px solid var(--border-light);">
                        <div class="user-counter">
                            <strong style="color:var(--text-primary);">{{ $role->users_count }}</strong>
                            <span>usuarios</span>
                        </div>
                        <div class="user-counter">
                            <strong style="color:var(--success);">{{ $role->active_count }}</strong>
                            <span>activos</span>
                        </div>
                        <div class="user-counter">
                            <strong style="color:var(--danger);">{{ $role->users_count - $role->active_count }}</strong>
                            <span>inactivos</span>
                        </div>
                    </div>
                </div>

                {{-- Permissions Summary --}}
                <div class="px-4 py-3 flex-grow-1">
                    <div class="text-xs fw-700 text-uppercase mb-2" style="color:var(--text-muted);letter-spacing:.07em;">
                        Permisos
                    </div>

                    @if($role->is_system)
                        @foreach($perms as $permLabel => $permValue)
                        <div class="perm-row">
                            <span class="perm-label">{{ $permLabel }}</span>
                            @if($permValue === true)
                                <span class="perm-yes"><i class="bi bi-check-lg me-1"></i>Sí</span>
                            @elseif($permValue === false)
                                <span class="perm-no"><i class="bi bi-dash me-1"></i>No</span>
                            @else
                                <span class="perm-text">{{ $permValue }}</span>
                            @endif
                        </div>
                        @endforeach
                    @else
                        @php $rolePerms = $role->permissions ?? []; @endphp
                        @if(empty($rolePerms))
                            <p class="text-muted small mb-0"><i class="bi bi-dash me-1"></i>Sin permisos asignados</p>
                        @else
                            @foreach($permissionGroups as $groupKey => $group)
                                @if(in_array($groupKey, $rolePerms))
                                <div class="perm-row">
                                    <span class="perm-label"><i class="bi {{ $group['icon'] }} me-1"></i>{{ $group['label'] }}</span>
                                    <span class="perm-yes"><i class="bi bi-check-lg"></i></span>
                                </div>
                                @endif
                            @endforeach
                        @endif
                    @endif
                </div>

                {{-- Card Footer --}}
                <div class="px-4 pb-4 d-flex gap-2">
                    <button type="button"
                            class="btn-primary-premium flex-grow-1 justify-content-center"
                            data-bs-toggle="modal"
                            data-bs-target="#editModal{{ $role->id }}">
                        <i class="bi bi-pencil-fill"></i> Editar
                    </button>
                    @if(!$role->is_system)
                    <form action="{{ route('roles.destroy', $role) }}" method="POST"
                          onsubmit="return confirm('¿Eliminar el rol «{{ $role->label }}»? Esta acción no se puede deshacer.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger rounded-pill px-3" title="Eliminar rol">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                    @endif
                </div>

            </div>
        </div>
        @endforeach
    </div>

    {{-- Quick link to users --}}
    <div class="card p-4 mt-4 d-flex flex-row align-items-center justify-content-between gap-3 flex-wrap">
        <div>
            <div class="fw-700" style="font-size:0.95rem;">¿Necesitas gestionar usuarios?</div>
            <p class="text-sm mb-0" style="color:var(--text-muted);">Asigna roles a los usuarios desde el mantenedor de usuarios.</p>
        </div>
        <a href="{{ route('users.index') }}" class="btn-app-secondary flex-shrink-0">
            <i class="bi bi-people-fill"></i> Ir a Usuarios
        </a>
    </div>

</div>
@endsection

@push('modals')

{{-- ── CREATE ROLE MODAL ──────────────────────────────────────────── --}}
<div class="modal fade" id="createRoleModal" tabindex="-1" aria-labelledby="createRoleLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0" style="border-radius:1.25rem;box-shadow:var(--shadow-lg);overflow:hidden;">

            <div class="modal-header border-0 px-4 pt-4 pb-2">
                <div>
                    <h5 class="fw-bold mb-0" id="createRoleLabel">Crear nuevo rol</h5>
                    <p class="mb-0 text-xs" style="color:var(--text-muted);">Define el nombre, apariencia y permisos del nuevo perfil de acceso.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <form action="{{ route('roles.store') }}" method="POST">
                @csrf
                <div class="modal-body px-4 pb-0 pt-3">
                    <div class="row g-3">
                        <div class="col-md-7">
                            <label class="form-label">Nombre visible <span class="text-danger">*</span></label>
                            <input type="text" name="label" class="form-control"
                                   placeholder="Ej: Supervisor, Bodega, Gerente…" required maxlength="100">
                            <div class="form-text">El identificador interno se genera automáticamente.</div>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label d-flex align-items-center gap-2">
                                Color del badge
                                <input type="color" name="badge_color" value="#7c3aed" id="createColorPicker"
                                       style="width:32px;height:24px;padding:2px;border:1px solid var(--border);border-radius:4px;cursor:pointer;">
                                <span id="createColorHex" class="text-xs" style="color:var(--text-muted);font-family:monospace;">#7c3aed</span>
                            </label>
                            <div style="display:flex;gap:8px;flex-wrap:wrap;margin-top:6px;">
                                @foreach(['#1e40af','#0284c7','#16a34a','#d97706','#dc2626','#7c3aed','#db2777','#0f766e','#b45309','#334155'] as $c)
                                <button type="button"
                                        onclick="document.getElementById('createColorPicker').value='{{ $c }}';document.getElementById('createColorHex').textContent='{{ $c }}';"
                                        style="width:24px;height:24px;background:{{ $c }};border:2px solid rgba(0,0,0,.15);border-radius:50%;cursor:pointer;padding:0;"
                                        title="{{ $c }}"></button>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Descripción</label>
                            <textarea name="description" class="form-control" rows="2"
                                      placeholder="Breve descripción del rol y sus responsabilidades…"
                                      maxlength="500"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Permisos</label>
                            <p class="text-xs text-muted mb-2">El acceso al Panel General y al Perfil siempre está incluido.</p>
                            <div class="row g-1">
                                @foreach($permissionGroups as $groupKey => $group)
                                <div class="col-md-6">
                                    <label class="perm-group-check">
                                        <input type="checkbox" name="permissions[]" value="{{ $groupKey }}">
                                        <i class="bi {{ $group['icon'] }} pgi"></i>
                                        <span class="pgl">{{ $group['label'] }}</span>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-3 gap-2">
                    <button type="button" class="btn-app-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn-primary-premium">
                        <i class="bi bi-plus-lg"></i> Crear Rol
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

{{-- ── EDIT MODALS ────────────────────────────────────────────────── --}}
@foreach($roles as $role)
@php
    $icons = ['admin' => 'bi-shield-fill-check', 'recepcion' => 'bi-telephone-fill', 'taller' => 'bi-wrench-adjustable'];
    $icon  = $icons[$role->name] ?? 'bi-person-badge-fill';
@endphp
<div class="modal fade" id="editModal{{ $role->id }}" tabindex="-1"
     aria-labelledby="editLabel{{ $role->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered {{ $role->is_system ? '' : 'modal-lg' }}">
        <div class="modal-content border-0" style="border-radius:1.25rem;box-shadow:var(--shadow-lg);overflow:hidden;">

            <div class="modal-header border-0 px-4 pt-4 pb-2">
                <div class="d-flex align-items-center gap-3">
                    <div class="role-icon" style="background:{{ $role->badge_color }};width:38px;height:38px;border-radius:9px;font-size:1rem;">
                        <i class="bi {{ $icon }}"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0" id="editLabel{{ $role->id }}">Editar rol</h5>
                        <p class="mb-0 text-xs" style="color:var(--text-muted);">
                            Slug: <code>{{ $role->name }}</code> (no editable)
                        </p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <form action="{{ route('roles.update', $role) }}" method="POST">
                @csrf @method('PUT')
                <div class="modal-body px-4 pb-0 pt-3">

                    <div class="mb-3">
                        <label class="form-label">Nombre visible</label>
                        <input type="text" name="label" class="form-control"
                               value="{{ old('label', $role->label) }}"
                               placeholder="Ej: Administrador" required maxlength="100">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea name="description" class="form-control" rows="3"
                                  placeholder="Descripción del rol y sus responsabilidades..."
                                  maxlength="500">{{ old('description', $role->description) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label d-flex align-items-center gap-2">
                            Color del badge
                            <input type="color" name="badge_color"
                                   value="{{ $role->badge_color }}"
                                   id="colorPicker{{ $role->id }}"
                                   style="width:32px;height:24px;padding:2px;border:1px solid var(--border);border-radius:4px;cursor:pointer;">
                            <span id="colorHex{{ $role->id }}" class="text-xs" style="color:var(--text-muted);font-family:monospace;">
                                {{ $role->badge_color }}
                            </span>
                        </label>
                        <div style="display:flex;gap:8px;flex-wrap:wrap;margin-top:8px;">
                            @foreach(['#1e40af','#0284c7','#16a34a','#d97706','#dc2626','#7c3aed','#db2777','#0f766e','#b45309','#334155'] as $c)
                            <button type="button"
                                    onclick="document.getElementById('colorPicker{{ $role->id }}').value='{{ $c }}';document.getElementById('colorHex{{ $role->id }}').textContent='{{ $c }}';"
                                    style="width:24px;height:24px;background:{{ $c }};border:2px solid rgba(0,0,0,.15);border-radius:50%;cursor:pointer;padding:0;transition:.1s;"
                                    title="{{ $c }}"></button>
                            @endforeach
                        </div>
                    </div>

                    @if(!$role->is_system)
                    <div class="mb-3">
                        <label class="form-label fw-bold">Permisos</label>
                        <p class="text-xs text-muted mb-2">El acceso al Panel General y al Perfil siempre está incluido.</p>
                        <div class="row g-1">
                            @foreach($permissionGroups as $groupKey => $group)
                            <div class="col-md-6">
                                <label class="perm-group-check">
                                    <input type="checkbox" name="permissions[]" value="{{ $groupKey }}"
                                           {{ in_array($groupKey, $role->permissions ?? []) ? 'checked' : '' }}>
                                    <i class="bi {{ $group['icon'] }} pgi"></i>
                                    <span class="pgl">{{ $group['label'] }}</span>
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                </div>

                <div class="modal-footer border-0 px-4 pb-4 pt-3 gap-2">
                    <button type="button" class="btn-app-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn-primary-premium">
                        <i class="bi bi-check-lg"></i> Guardar cambios
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endforeach

@endpush
