@extends('layouts.app')

@section('title', 'Compañías de Seguros')

@section('content')
<div class="animate-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="outfit fw-bold mb-1">Compañías de Seguros</h2>
            <p class="text-muted mb-0">Gestión de aseguradoras para presupuestos</p>
        </div>
        <button class="btn-primary-premium" data-bs-toggle="modal" data-bs-target="#modalNuevo">
            <i class="bi bi-building-fill-add"></i> Nueva Compañía
        </button>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Compañía</th>
                        <th>Liquidadores</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($companies as $company)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center"
                                    style="width:36px;height:36px;background:#eff6ff;flex-shrink:0;">
                                    <i class="bi bi-building-fill text-primary" style="font-size:1rem;"></i>
                                </div>
                                <span class="fw-semibold">{{ $company->name }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-primary-subtle text-primary rounded-pill px-3">
                                {{ $company->liquidators_count }}
                                {{ $company->liquidators_count === 1 ? 'liquidador' : 'liquidadores' }}
                            </span>
                        </td>
                        <td class="text-end">
                            <div class="d-flex gap-1 justify-content-end">
                                <button class="btn btn-sm btn-light rounded-pill px-3 edit-btn"
                                    data-bs-toggle="modal" data-bs-target="#modalEditar"
                                    data-id="{{ $company->id }}"
                                    data-name="{{ $company->name }}"
                                    title="Editar">
                                    <i class="bi bi-pencil text-primary"></i>
                                </button>
                                <form method="POST" action="{{ route('insurance-companies.destroy', $company) }}"
                                    onsubmit="return confirm('¿Eliminar {{ addslashes($company->name) }}?')">
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
                        <td colspan="3" class="text-center text-muted py-5">
                            <i class="bi bi-building fs-1 d-block mb-3 opacity-25"></i>
                            No hay compañías registradas.
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
                <h5 class="outfit fw-bold mb-0">Nueva Compañía</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('insurance-companies.store') }}" method="POST">
                @csrf
                <div class="modal-body pt-3">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Nombre de la Compañía <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="Ej: Cardif, BCI, Mapfre..." required autofocus>
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
                <h5 class="outfit fw-bold mb-0">Editar Compañía</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editForm" method="POST">
                @csrf @method('PUT')
                <div class="modal-body pt-3">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Nombre de la Compañía <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
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
        document.getElementById('editForm').action = `/insurance-companies/${this.dataset.id}`;
        document.getElementById('edit_name').value = this.dataset.name;
    });
});
</script>
@endsection
