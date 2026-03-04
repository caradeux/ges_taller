@extends('layouts.app')

@section('title', 'Tipos de UN')

@section('content')
<div class="animate-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="outfit fw-bold mb-1">Tipos de UN</h2>
            <p class="text-muted mb-0">Define los tipos de acción disponibles al crear cotizaciones.</p>
        </div>
        <button class="btn-primary-premium" data-bs-toggle="modal" data-bs-target="#modalNuevo">
            <i class="bi bi-plus-lg"></i> Nuevo Tipo
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4" style="border-radius:1rem;">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm mb-4" style="border-radius:1rem;">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
        </div>
    @endif

    {{-- Leyenda de categorías --}}
    <div class="card mb-4 p-3">
        <p class="small text-muted mb-2 fw-semibold">Cada tipo de UN pertenece a una <strong>categoría de columna</strong> del presupuesto PDF:</p>
        <div class="d-flex flex-wrap gap-2">
            @foreach($categories as $key => $label)
            <span class="badge rounded-pill px-3 py-2"
                style="background:{{ ['repair'=>'#dbeafe','paint'=>'#fce7f3','dm'=>'#dcfce7','parts'=>'#fef3c7','other'=>'#f3f4f6'][$key] }};
                       color:{{ ['repair'=>'#1d4ed8','paint'=>'#9d174d','dm'=>'#15803d','parts'=>'#92400e','other'=>'#374151'][$key] }};">
                {{ $label }}
            </span>
            @endforeach
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th style="width:60px;">Orden</th>
                        <th style="width:90px;">Código (UN)</th>
                        <th>Nombre</th>
                        <th>Categoría columna</th>
                        <th style="width:90px;">Estado</th>
                        <th style="width:80px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($unTypes as $ut)
                    <tr>
                        <td class="text-center text-muted">{{ $ut->sort_order }}</td>
                        <td><span class="badge bg-light text-dark border fw-bold px-2">{{ $ut->code }}</span></td>
                        <td>{{ $ut->name }}</td>
                        <td>
                            @php
                                $colors = ['repair'=>['#dbeafe','#1d4ed8'],'paint'=>['#fce7f3','#9d174d'],'dm'=>['#dcfce7','#15803d'],'parts'=>['#fef3c7','#92400e'],'other'=>['#f3f4f6','#374151']];
                                [$bg,$fg] = $colors[$ut->category] ?? ['#f3f4f6','#374151'];
                            @endphp
                            <span class="badge rounded-pill px-3" style="background:{{ $bg }};color:{{ $fg }};">
                                {{ $ut->category_label }}
                            </span>
                        </td>
                        <td>
                            @if($ut->active)
                                <span class="badge bg-success rounded-pill">Activo</span>
                            @else
                                <span class="badge bg-secondary rounded-pill">Inactivo</span>
                            @endif
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light rounded-pill px-2" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                    <li>
                                        <button class="dropdown-item edit-btn"
                                            data-bs-toggle="modal" data-bs-target="#modalEditar"
                                            data-id="{{ $ut->id }}"
                                            data-code="{{ $ut->code }}"
                                            data-name="{{ $ut->name }}"
                                            data-category="{{ $ut->category }}"
                                            data-sort="{{ $ut->sort_order }}"
                                            data-active="{{ $ut->active ? '1' : '0' }}">
                                            <i class="bi bi-pencil me-2 text-primary"></i>Editar
                                        </button>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('un-types.destroy', $ut) }}"
                                            onsubmit="return confirm('¿Eliminar tipo {{ addslashes($ut->code) }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="bi bi-trash me-2"></i>Eliminar
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">
                            <i class="bi bi-list-ul fs-1 d-block mb-2 opacity-25"></i>
                            No hay tipos de UN registrados.
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
    <div class="modal-dialog modal-sm">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="outfit fw-bold mb-0">Nuevo Tipo de UN</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('un-types.store') }}" method="POST">
                @csrf
                <div class="modal-body pt-3">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Código <span class="text-danger">*</span></label>
                        <input type="text" name="code" class="form-control text-uppercase" required maxlength="20"
                            placeholder="Ej: REP, D/M, PREP...">
                        <div class="form-text">Se guarda en mayúsculas. Ej: REP, D/M, PINT, LACQ</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Nombre <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required placeholder="Ej: Preparación">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Categoría columna <span class="text-danger">*</span></label>
                        <select name="category" class="form-select" required>
                            <option value="">Seleccione...</option>
                            @foreach($categories as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        <div class="form-text">Determina en qué columna aparece el monto en el PDF.</div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-semibold small">Orden de aparición</label>
                        <input type="number" name="sort_order" class="form-control" min="1" max="999" placeholder="99">
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn-primary-premium">
                        <i class="bi bi-check-lg"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Editar --}}
<div class="modal fade" id="modalEditar" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="outfit fw-bold mb-0">Editar Tipo de UN</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editForm" method="POST">
                @csrf @method('PUT')
                <div class="modal-body pt-3">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Código <span class="text-danger">*</span></label>
                        <input type="text" name="code" id="edit_code" class="form-control text-uppercase" required maxlength="20">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Nombre <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Categoría columna <span class="text-danger">*</span></label>
                        <select name="category" id="edit_category" class="form-select" required>
                            @foreach($categories as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Orden</label>
                        <input type="number" name="sort_order" id="edit_sort" class="form-control" min="1" max="999">
                    </div>
                    <div class="form-check">
                        <input type="hidden" name="active" value="0">
                        <input class="form-check-input" type="checkbox" name="active" id="edit_active" value="1">
                        <label class="form-check-label small fw-semibold" for="edit_active">Activo</label>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
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
    btn.addEventListener('click', function() {
        document.getElementById('editForm').action = `/un-types/${this.dataset.id}`;
        document.getElementById('edit_code').value     = this.dataset.code;
        document.getElementById('edit_name').value     = this.dataset.name;
        document.getElementById('edit_category').value = this.dataset.category;
        document.getElementById('edit_sort').value     = this.dataset.sort;
        document.getElementById('edit_active').checked = this.dataset.active === '1';
    });
});
</script>
@endsection
