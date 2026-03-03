@extends('layouts.app')

@section('title', 'Sucursales')

@section('content')
<div class="animate-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="outfit fw-bold mb-1">Sucursales</h2>
            <p class="text-muted mb-0">Gestiona las ubicaciones del taller</p>
        </div>
        <button class="btn-primary-premium" data-bs-toggle="modal" data-bs-target="#modalNuevo">
            <i class="bi bi-building-fill-add"></i> Nueva Sucursal
        </button>
    </div>

    @if($errors->any())
        <div class="alert alert-danger border-0 shadow-sm mb-4" style="border-radius:1rem;">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ $errors->first() }}
        </div>
    @endif

    <div class="row g-4">
        @forelse($branches as $branch)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                style="width:44px;height:44px;background:#eff6ff;flex-shrink:0;">
                                <i class="bi bi-building-fill text-primary" style="font-size:1.2rem;"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-0">{{ $branch->name }}</h5>
                                @if($branch->active)
                                    <span class="badge bg-success rounded-pill" style="font-size:0.65rem;">Activa</span>
                                @else
                                    <span class="badge bg-secondary rounded-pill" style="font-size:0.65rem;">Inactiva</span>
                                @endif
                            </div>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light rounded-pill px-2" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                <li>
                                    <button class="dropdown-item edit-btn"
                                        data-bs-toggle="modal" data-bs-target="#modalEditar"
                                        data-id="{{ $branch->id }}"
                                        data-name="{{ $branch->name }}"
                                        data-address="{{ $branch->address }}"
                                        data-phone="{{ $branch->phone }}"
                                        data-email="{{ $branch->email }}"
                                        data-active="{{ $branch->active ? '1' : '0' }}">
                                        <i class="bi bi-pencil me-2 text-primary"></i>Editar
                                    </button>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('branches.destroy', $branch) }}"
                                        onsubmit="return confirm('¿Eliminar sucursal {{ addslashes($branch->name) }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-trash me-2"></i>Eliminar
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>

                    @if($branch->address)
                    <p class="small text-muted mb-2">
                        <i class="bi bi-geo-alt me-1"></i>{{ $branch->address }}
                    </p>
                    @endif
                    @if($branch->phone)
                    <p class="small text-muted mb-2">
                        <i class="bi bi-telephone me-1"></i>{{ $branch->phone }}
                    </p>
                    @endif
                    @if($branch->email)
                    <p class="small text-muted mb-2">
                        <i class="bi bi-envelope me-1"></i>{{ $branch->email }}
                    </p>
                    @endif

                    <hr class="my-3">

                    <div class="row text-center g-2">
                        <div class="col-4">
                            <div class="fw-bold fs-5 text-primary">{{ $branch->users_count }}</div>
                            <div class="small text-muted">Usuarios</div>
                        </div>
                        <div class="col-4">
                            <div class="fw-bold fs-5 text-success">{{ $branch->clients_count }}</div>
                            <div class="small text-muted">Clientes</div>
                        </div>
                        <div class="col-4">
                            <div class="fw-bold fs-5 text-warning">{{ $branch->quotations_count }}</div>
                            <div class="small text-muted">Cotizaciones</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center text-muted py-5">
                    <i class="bi bi-building fs-1 d-block mb-3 opacity-25"></i>
                    No hay sucursales registradas.
                </div>
            </div>
        </div>
        @endforelse
    </div>
</div>

{{-- Modal Nuevo --}}
<div class="modal fade" id="modalNuevo" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="outfit fw-bold mb-0">Nueva Sucursal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('branches.store') }}" method="POST">
                @csrf
                <div class="modal-body pt-3">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Nombre <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required autofocus
                            placeholder="Ej: Sucursal Viña del Mar">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Dirección</label>
                        <input type="text" name="address" class="form-control" placeholder="Av. Libertad 1234">
                    </div>
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold small">Teléfono</label>
                            <input type="text" name="phone" class="form-control">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold small">Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4"
                        data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn-primary-premium">
                        <i class="bi bi-building-fill-check"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Editar --}}
<div class="modal fade" id="modalEditar" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="outfit fw-bold mb-0">Editar Sucursal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editForm" method="POST">
                @csrf @method('PUT')
                <div class="modal-body pt-3">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Nombre <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Dirección</label>
                        <input type="text" name="address" id="edit_address" class="form-control">
                    </div>
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold small">Teléfono</label>
                            <input type="text" name="phone" id="edit_phone" class="form-control">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold small">Email</label>
                            <input type="email" name="email" id="edit_email" class="form-control">
                        </div>
                    </div>
                    <div class="form-check mt-3">
                        <input type="hidden" name="active" value="0">
                        <input class="form-check-input" type="checkbox" name="active" id="edit_active" value="1">
                        <label class="form-check-label small fw-semibold" for="edit_active">Sucursal activa</label>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4"
                        data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn-primary-premium">
                        <i class="bi bi-check-lg"></i> Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        document.getElementById('editForm').action = `/branches/${this.dataset.id}`;
        document.getElementById('edit_name').value    = this.dataset.name;
        document.getElementById('edit_address').value = this.dataset.address || '';
        document.getElementById('edit_phone').value   = this.dataset.phone || '';
        document.getElementById('edit_email').value   = this.dataset.email || '';
        document.getElementById('edit_active').checked = this.dataset.active === '1';
    });
});
</script>
@endsection
