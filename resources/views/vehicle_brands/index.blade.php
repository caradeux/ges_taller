@extends('layouts.app')

@section('title', 'Marcas y Modelos')

@section('content')
<div class="animate-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="outfit fw-bold mb-1">Marcas y Modelos</h2>
            <p class="text-muted mb-0">Catálogo de vehículos para normalizar datos</p>
        </div>
        <button class="btn-primary-premium" data-bs-toggle="modal" data-bs-target="#modalNuevaMarca">
            <i class="bi bi-plus-circle-fill"></i> Nueva Marca
        </button>
    </div>

    <div class="row g-4">
        @forelse($brands as $brand)
        <div class="col-md-6 col-xl-4">
            <div class="card h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h5 class="outfit fw-bold mb-0">{{ $brand->name }}</h5>
                        <div class="d-flex gap-1">
                            <button class="btn btn-sm btn-light rounded-pill px-2"
                                data-bs-toggle="modal"
                                data-bs-target="#modalEditMarca{{ $brand->id }}"
                                title="Editar marca">
                                <i class="bi bi-pencil text-primary small"></i>
                            </button>
                            <form method="POST" action="{{ route('vehicle-brands.destroy', $brand) }}"
                                onsubmit="return confirm('¿Eliminar marca {{ addslashes($brand->name) }} y todos sus modelos?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light rounded-pill px-2" title="Eliminar marca">
                                    <i class="bi bi-trash text-danger small"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Models list --}}
                    <div class="mb-3" style="max-height:200px;overflow-y:auto;">
                        @forelse($brand->models as $model)
                        <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
                            <span class="small">{{ $model->name }}</span>
                            <form method="POST" action="{{ route('vehicle-brands.models.destroy', [$brand, $model]) }}">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-link p-0 text-danger" title="Eliminar modelo"
                                    onclick="return confirm('¿Eliminar {{ addslashes($model->name) }}?')">
                                    <i class="bi bi-x-circle"></i>
                                </button>
                            </form>
                        </div>
                        @empty
                        <p class="text-muted small mb-0">Sin modelos aún.</p>
                        @endforelse
                    </div>

                    {{-- Add model --}}
                    <form method="POST" action="{{ route('vehicle-brands.models.store', $brand) }}" class="d-flex gap-2">
                        @csrf
                        <input type="text" name="name" class="form-control form-control-sm" placeholder="Agregar modelo..." required>
                        <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill px-3 flex-shrink-0">
                            <i class="bi bi-plus-lg"></i>
                        </button>
                    </form>
                </div>
                <div class="card-footer bg-transparent border-top-0 px-4 pb-3">
                    <small class="text-muted">{{ $brand->models_count }} modelo(s)</small>
                </div>
            </div>
        </div>

        {{-- Edit brand modal --}}
        <div class="modal fade" id="modalEditMarca{{ $brand->id }}" tabindex="-1">
            <div class="modal-dialog modal-sm">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header border-0 pb-0">
                        <h6 class="outfit fw-bold mb-0">Editar Marca</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ route('vehicle-brands.update', $brand) }}">
                            @csrf @method('PUT')
                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Nombre</label>
                                <input type="text" name="name" class="form-control"
                                    value="{{ $brand->name }}" required>
                            </div>
                            <div class="d-flex gap-2 justify-content-end">
                                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3"
                                    data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center text-muted py-5">
                    <i class="bi bi-car-front fs-1 mb-3 d-block opacity-25"></i>
                    No hay marcas registradas. Agrega una para comenzar.
                </div>
            </div>
        </div>
        @endforelse
    </div>
</div>

{{-- Modal Nueva Marca --}}
<div class="modal fade" id="modalNuevaMarca" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h6 class="outfit fw-bold mb-0">Nueva Marca</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('vehicle-brands.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Nombre de la marca</label>
                        <input type="text" name="name" class="form-control" placeholder="Ej: Toyota" required autofocus>
                    </div>
                    <div class="d-flex gap-2 justify-content-end">
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3">Crear</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
