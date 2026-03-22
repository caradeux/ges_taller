@extends('layouts.app')

@section('title', 'Partes y Piezas')

@section('content')
<div class="animate-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="outfit fw-bold mb-1">Partes y Piezas</h2>
            <p class="text-muted mb-0">Catálogo de componentes vehiculares para órdenes de trabajo</p>
        </div>
        <button class="btn-primary-premium" data-bs-toggle="modal" data-bs-target="#modalNewPart">
            <i class="bi bi-plus-circle-fill"></i> Nueva Pieza
        </button>
    </div>

    {{-- Filters --}}
    <div class="card mb-4">
        <div class="card-body py-3 px-4">
            <form method="GET" class="d-flex gap-3 align-items-end flex-wrap">
                <div class="flex-grow-1" style="min-width:200px;">
                    <label class="form-label fw-semibold small mb-1">Buscar</label>
                    <input type="text" name="search" class="form-control" placeholder="Nombre de la pieza..."
                        value="{{ request('search') }}">
                </div>
                <div style="min-width:180px;">
                    <label class="form-label fw-semibold small mb-1">Categoría</label>
                    <select name="category" class="form-select">
                        <option value="">Todas</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary rounded-pill px-4">Filtrar</button>
                @if(request('search') || request('category'))
                    <a href="{{ route('parts.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Limpiar</a>
                @endif
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Pieza</th>
                        <th>Categoría</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($parts as $part)
                    <tr>
                        <td class="fw-semibold">{{ $part->name }}</td>
                        <td>
                            <span class="badge bg-light text-dark border">{{ $part->category }}</span>
                        </td>
                        <td>
                            @if($part->active)
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-secondary">Inactivo</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                data-bs-target="#modalEdit{{ $part->id }}">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form action="{{ route('parts.destroy', $part) }}" method="POST" class="d-inline"
                                >
                                @csrf @method('DELETE')
                                <button data-confirm="¿Estás seguro de eliminar este registro?" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash3"></i></button>
                            </form>
                        </td>
                    </tr>

                    {{-- Edit Modal --}}
                    <div class="modal fade" id="modalEdit{{ $part->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <form action="{{ route('parts.update', $part) }}" method="POST" class="modal-content">
                                @csrf @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title">Editar Pieza</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Nombre</label>
                                        <input type="text" name="name" class="form-control" value="{{ $part->name }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Categoría</label>
                                        <input type="text" name="category" class="form-control" value="{{ $part->category }}" required
                                            list="categorySuggestions">
                                    </div>
                                    <div class="form-check form-switch">
                                        <input type="checkbox" name="active" value="1" class="form-check-input"
                                            {{ $part->active ? 'checked' : '' }}>
                                        <label class="form-check-label">Activo</label>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-primary">Guardar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-secondary py-4">
                            No hay piezas registradas. Agrega una para comenzar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{ $parts->links() }}
</div>

{{-- New Part Modal --}}
<div class="modal fade" id="modalNewPart" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('parts.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Nueva Pieza</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="name" class="form-control" required placeholder="Ej: Parachoques Delantero">
                </div>
                <div class="mb-3">
                    <label class="form-label">Categoría</label>
                    <input type="text" name="category" class="form-control" required placeholder="Ej: Carrocería"
                        list="categorySuggestions">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Crear</button>
            </div>
        </form>
    </div>
</div>

<datalist id="categorySuggestions">
    @foreach($categories as $cat)
    <option value="{{ $cat }}">
    @endforeach
</datalist>
@endsection
