@extends('layouts.app')

@section('title', 'Liquidadores')

@section('content')
<div class="animate-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="outfit fw-bold mb-1">Liquidadores</h2>
            <p class="text-muted mb-0">Profesionales asignados a inspecciones de seguros</p>
        </div>
        <button class="btn-primary-premium" data-bs-toggle="modal" data-bs-target="#modalNuevo">
            <i class="bi bi-person-plus-fill"></i> Nuevo Liquidador
        </button>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Compañía de Seguros</th>
                        <th>Contacto</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($liquidators as $liquidator)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center"
                                    style="width:36px;height:36px;background:#f0fdf4;flex-shrink:0;">
                                    <i class="bi bi-person-badge-fill text-success" style="font-size:1rem;"></i>
                                </div>
                                <span class="fw-semibold">{{ $liquidator->name }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-primary-subtle text-primary rounded-pill px-3">
                                {{ $liquidator->insuranceCompany->name ?? '—' }}
                            </span>
                        </td>
                        <td>
                            <div class="small">
                                @if($liquidator->phone)
                                    <div><i class="bi bi-phone me-1 text-muted"></i>{{ $liquidator->phone }}</div>
                                @endif
                                @if($liquidator->email)
                                    <div class="text-muted"><i class="bi bi-envelope me-1"></i>{{ $liquidator->email }}</div>
                                @endif
                                @if(!$liquidator->phone && !$liquidator->email)
                                    <span class="text-muted">—</span>
                                @endif
                            </div>
                        </td>
                        <td class="text-end">
                            <div class="d-flex gap-1 justify-content-end">
                                <button class="btn btn-sm btn-light rounded-pill px-3 edit-btn"
                                    data-bs-toggle="modal" data-bs-target="#modalEditar"
                                    data-id="{{ $liquidator->id }}"
                                    data-name="{{ $liquidator->name }}"
                                    data-company="{{ $liquidator->insurance_company_id }}"
                                    data-phone="{{ $liquidator->phone }}"
                                    data-email="{{ $liquidator->email }}"
                                    title="Editar">
                                    <i class="bi bi-pencil text-primary"></i>
                                </button>
                                <form method="POST" action="{{ route('liquidators.destroy', $liquidator) }}"
                                    onsubmit="return confirm('¿Eliminar a {{ addslashes($liquidator->name) }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light rounded-pill px-3" title="Eliminar">
                                        <i class="bi bi-trash text-danger"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-5">
                            <i class="bi bi-person-badge fs-1 d-block mb-3 opacity-25"></i>
                            No hay liquidadores registrados.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Nuevo --}}
<div class="modal fade" id="modalNuevo" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="outfit fw-bold mb-0">Nuevo Liquidador</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('liquidators.store') }}" method="POST">
                @csrf
                <div class="modal-body pt-3">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Nombre Completo <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Compañía de Seguros <span class="text-danger">*</span></label>
                        <select name="insurance_company_id" class="form-select" required>
                            <option value="">Seleccione...</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}">{{ $company->name }}</option>
                            @endforeach
                        </select>
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
                        <i class="bi bi-person-check-fill"></i> Guardar
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
                <h5 class="outfit fw-bold mb-0">Editar Liquidador</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editForm" method="POST">
                @csrf @method('PUT')
                <div class="modal-body pt-3">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Nombre Completo <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Compañía de Seguros <span class="text-danger">*</span></label>
                        <select name="insurance_company_id" id="edit_company" class="form-select" required>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}">{{ $company->name }}</option>
                            @endforeach
                        </select>
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
        document.getElementById('editForm').action = `/liquidators/${this.dataset.id}`;
        document.getElementById('edit_name').value    = this.dataset.name;
        document.getElementById('edit_company').value = this.dataset.company;
        document.getElementById('edit_phone').value   = this.dataset.phone || '';
        document.getElementById('edit_email').value   = this.dataset.email || '';
    });
});
</script>
@endsection
