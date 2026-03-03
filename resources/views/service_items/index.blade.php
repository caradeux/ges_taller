@extends('layouts.app')

@section('title', 'Catálogo de Servicios')

@section('content')
<div class="animate-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="outfit fw-bold mb-1">Catálogo de Servicios</h2>
            <p class="text-muted mb-0">Ítems frecuentes para autocompletar presupuestos</p>
        </div>
        <a href="{{ route('service-items.create') }}" class="btn-primary-premium">
            <i class="bi bi-plus-circle-fill"></i> Nuevo Ítem
        </a>
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
                        <option value="repuesto"  {{ request('type') === 'repuesto'  ? 'selected' : '' }}>Repuesto</option>
                        <option value="mano_obra" {{ request('type') === 'mano_obra' ? 'selected' : '' }}>Mano de Obra</option>
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
                            @if($item->type === 'repuesto')
                                <span class="badge bg-primary-subtle text-primary rounded-pill px-3">Repuesto</span>
                            @else
                                <span class="badge bg-warning-subtle text-warning rounded-pill px-3">Mano de Obra</span>
                            @endif
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
                                    onsubmit="return confirm('¿Eliminar {{ addslashes($item->description) }}?')">
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
@endsection
