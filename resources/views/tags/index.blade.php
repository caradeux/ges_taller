@extends('layouts.app')

@section('title', 'Etiquetas')

@section('content')
<div class="animate-in">

    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h2 class="page-title">Etiquetas</h2>
            <p class="page-subtitle">Etiquetas para clasificar órdenes de trabajo.</p>
        </div>
        <button type="button" class="btn-primary-premium" data-bs-toggle="modal" data-bs-target="#modalNewTag">
            <i class="bi bi-plus-lg"></i> Nueva Etiqueta
        </button>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Color</th>
                        <th class="text-center">OTs Asociadas</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tags as $tag)
                    <tr>
                        <td>
                            <span class="badge" style="background-color:{{ $tag->color }};font-size:0.82rem;">
                                {{ $tag->name }}
                            </span>
                        </td>
                        <td>
                            <span style="display:inline-block;width:24px;height:24px;border-radius:6px;background:{{ $tag->color }};border:1px solid var(--border);"></span>
                            <span class="text-muted ms-1" style="font-size:0.8rem;">{{ $tag->color }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-light text-dark">{{ $tag->work_orders_count }}</span>
                        </td>
                        <td class="text-end">
                            <button type="button" class="btn btn-sm btn-outline-primary edit-tag"
                                data-id="{{ $tag->id }}" data-name="{{ $tag->name }}" data-color="{{ $tag->color }}"
                                data-bs-toggle="modal" data-bs-target="#modalEditTag">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form action="{{ route('tags.destroy', $tag) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('¿Eliminar la etiqueta {{ $tag->name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4">
                            <div class="empty-state">
                                <div class="empty-state-icon"><i class="bi bi-tags"></i></div>
                                <p>No hay etiquetas registradas.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal: Nueva Etiqueta --}}
<div class="modal fade" id="modalNewTag" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content border-0" style="border-radius:var(--radius-lg);box-shadow:var(--shadow-lg);">
            <div class="modal-header border-0 pb-0 pt-4 px-4">
                <h6 class="fw-bold mb-0"><i class="bi bi-tag-fill me-2" style="color:var(--primary);"></i>Nueva Etiqueta</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('tags.store') }}" method="POST">
                @csrf
                <div class="modal-body px-4 py-3">
                    <div class="mb-3">
                        <label class="form-label">Nombre <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required placeholder="Ej: Urgente">
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Color</label>
                        <input type="color" name="color" class="form-control form-control-color" value="#6c757d">
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0 gap-2">
                    <button type="button" class="btn-app-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn-primary-premium"><i class="bi bi-check-lg"></i> Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal: Editar Etiqueta --}}
<div class="modal fade" id="modalEditTag" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content border-0" style="border-radius:var(--radius-lg);box-shadow:var(--shadow-lg);">
            <div class="modal-header border-0 pb-0 pt-4 px-4">
                <h6 class="fw-bold mb-0"><i class="bi bi-pencil-fill me-2" style="color:var(--primary);"></i>Editar Etiqueta</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditTag" method="POST">
                @csrf @method('PUT')
                <div class="modal-body px-4 py-3">
                    <div class="mb-3">
                        <label class="form-label">Nombre <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="editTagName" class="form-control" required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Color</label>
                        <input type="color" name="color" id="editTagColor" class="form-control form-control-color">
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0 gap-2">
                    <button type="button" class="btn-app-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn-primary-premium"><i class="bi bi-check-lg"></i> Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.querySelectorAll('.edit-tag').forEach(btn => {
    btn.addEventListener('click', () => {
        document.getElementById('formEditTag').action = '/tags/' + btn.dataset.id;
        document.getElementById('editTagName').value = btn.dataset.name;
        document.getElementById('editTagColor').value = btn.dataset.color;
    });
});
</script>
@endsection
