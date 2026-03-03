@extends('layouts.app')

@section('title', 'Editar Ítem')

@section('content')
<div class="animate-in" style="max-width:600px;">
    <div class="mb-4">
        <a href="{{ route('service-items.index') }}" class="text-muted text-decoration-none">
            <i class="bi bi-arrow-left me-1"></i>Volver al Catálogo
        </a>
        <h2 class="outfit fw-bold mt-2 mb-0">Editar Ítem</h2>
    </div>

    <div class="card">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('service-items.update', $serviceItem) }}">
                @csrf @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-semibold">Código <span class="text-muted fw-normal">(opcional)</span></label>
                    <input type="text" name="code" class="form-control font-monospace @error('code') is-invalid @enderror"
                        value="{{ old('code', $serviceItem->code) }}" placeholder="Ej: SRV-001">
                    @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Descripción</label>
                    <input type="text" name="description" class="form-control @error('description') is-invalid @enderror"
                        value="{{ old('description', $serviceItem->description) }}" required>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-sm-6">
                        <label class="form-label fw-semibold">Tipo</label>
                        <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                            <option value="mano_obra" {{ old('type', $serviceItem->type) === 'mano_obra' ? 'selected' : '' }}>Mano de Obra</option>
                            <option value="repuesto"  {{ old('type', $serviceItem->type) === 'repuesto'  ? 'selected' : '' }}>Repuesto</option>
                        </select>
                        @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label fw-semibold">Precio base (CLP)</label>
                        <input type="number" name="default_price" class="form-control @error('default_price') is-invalid @enderror"
                            value="{{ old('default_price', $serviceItem->default_price) }}" min="0" step="1" required>
                        @error('default_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="active" id="active" value="1"
                            {{ old('active', $serviceItem->active) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="active">Activo</label>
                    </div>
                </div>

                <div class="d-flex gap-2 justify-content-end">
                    <a href="{{ route('service-items.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Cancelar</a>
                    <button type="submit" class="btn-primary-premium">
                        <i class="bi bi-check-lg"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
