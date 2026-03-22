@extends('layouts.app')

@section('title', 'Catálogo de Servicios')

@section('content')
<div class="animate-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="outfit fw-bold mb-1">Catálogo de Servicios</h2>
            <p class="text-muted mb-0">Ítems frecuentes para autocompletar presupuestos</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn-app-secondary" data-bs-toggle="modal" data-bs-target="#modalNewType">
                <i class="bi bi-tag"></i> Nuevo Tipo
            </button>
            <a href="{{ route('service-items.create') }}" class="btn-primary-premium">
                <i class="bi bi-plus-circle-fill"></i> Nuevo Ítem
            </a>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card mb-4">
        <div class="card-body py-3 px-4">
            <form method="GET" class="d-flex gap-3 align-items-end flex-wrap">
                <div class="flex-grow-1" style="min-width:200px;">
                    <label class="form-label fw-semibold small mb-1">Buscar</label>
                    <input type="text" name="search" class="form-control" placeholder="Código o descripción..."
                        value="{{ request('search') }}">
                </div>
                <div style="min-width:160px;">
                    <label class="form-label fw-semibold small mb-1">Tipo</label>
                    <select name="type" class="form-select">
                        <option value="">Todos</option>
                        @foreach($types as $t)
                        <option value="{{ $t->slug }}" {{ request('type') === $t->slug ? 'selected' : '' }}>{{ $t->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary rounded-pill px-4">Filtrar</button>
                @if(request('search') || request('type'))
                    <a href="{{ route('service-items.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Limpiar</a>
                @endif
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Descripción</th>
                        <th>Tipo</th>
                        <th class="text-end">Precio Base</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                    <tr class="{{ !$item->active ? 'opacity-50' : '' }}">
                        <td><span class="badge bg-light text-muted border font-monospace">{{ $item->code ?? '—' }}</span></td>
                        <td class="fw-semibold">{{ $item->description }}</td>
                        <td>
                            <span class="badge bg-light text-dark border rounded-pill px-3">{{ $types->firstWhere('slug', $item->type)?->name ?? $item->type }}</span>
                        </td>
                        <td class="text-end fw-semibold">$ {{ number_format($item->default_price, 0, ',', '.') }}</td>
                        <td>
                            @if($item->active)
                                <span class="badge bg-success rounded-pill px-3">Activo</span>
                            @else
                                <span class="badge bg-secondary rounded-pill px-3">Inactivo</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="d-flex gap-1 justify-content-end">
                                <a href="{{ route('service-items.edit', $item) }}"
                                    class="btn btn-sm btn-light rounded-pill px-3" title="Editar">
                                    <i class="bi bi-pencil text-primary"></i>
                                </a>
                                <form method="POST" action="{{ route('service-items.destroy', $item) }}"
                                    >
                                    @csrf @method('DELETE')
                                    <button data-confirm="¿Estás seguro de eliminar este registro?" type="submit" class="btn btn-sm btn-light rounded-pill px-3" title="Eliminar">
                                        <i class="bi bi-trash text-danger"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">No hay ítems en el catálogo.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($items->hasPages())
        <div class="card-footer bg-white border-top-0 px-4 py-3">
            {{ $items->links() }}
        </div>
        @endif
    </div>
</div>

{{-- Modal: Nuevo Tipo --}}
<div class="modal fade" id="modalNewType" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0" style="border-radius:var(--radius-lg);box-shadow:var(--shadow-lg);">
            <form action="{{ route('service-items.store-type') }}" method="POST">
                @csrf
                <div class="modal-header border-0 pb-0 pt-4 px-4">
                    <h6 class="fw-bold mb-0 ls-tight">
                        <i class="bi bi-tag me-2" style="color:var(--primary);"></i>Nuevo Tipo
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body px-4 py-3">
                    <div class="mb-2">
                        <label class="form-label">Nombre del tipo <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required autofocus
                            placeholder="Ej: Insumo, Herramienta, Accesorio...">
                    </div>
                    @if($types->count())
                    <div class="mt-2">
                        <small class="text-muted">Tipos existentes:</small>
                        <div class="d-flex flex-wrap gap-1 mt-1">
                            @foreach($types as $t)
                            <span class="badge bg-light text-dark border" style="font-size:0.72rem;">{{ $t->name }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0 gap-2">
                    <button type="button" class="btn-app-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn-primary-premium">
                        <i class="bi bi-check-lg"></i> Crear Tipo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
