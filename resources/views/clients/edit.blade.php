@extends('layouts.app')

@section('title', 'Editar Cliente')

@section('content')
    <div class="animate-in" style="max-width: 800px; margin: 0 auto;">
        <div class="mb-4">
            <a href="{{ route('clients.index') }}" class="text-decoration-none text-secondary small fw-medium">
                <i class="bi bi-arrow-left"></i> Volver al listado
            </a>
            <h2 class="fw-bold mt-2">Editar Cliente</h2>
            <p class="text-secondary small">Modifica los datos del cliente <strong>{{ $client->name }}</strong>.</p>
        </div>

        <div class="card p-4">
            <form action="{{ route('clients.update', $client) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">RUT / DNI <span class="text-danger">*</span></label>
                        <input type="text" name="rut_dni" class="form-control @error('rut_dni') is-invalid @enderror"
                            value="{{ old('rut_dni', $client->rut_dni) }}" placeholder="Ej: 12.345.678-K" required>
                        @error('rut_dni')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">Nombre Completo <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $client->name) }}" placeholder="Ej: Juan Pérez" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">Correo Electrónico</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $client->email) }}" placeholder="juan@ejemplo.com">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">Teléfono de Contacto</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">+56</span>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                value="{{ old('phone', $client->phone) }}" placeholder="9 1234 5678">
                        </div>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold small">Dirección</label>
                        <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="2"
                            placeholder="Ej: Av. Providencia 1234, Depto 502, Santiago">{{ old('address', $client->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 pt-3 border-top mt-4 d-flex justify-content-end gap-2">
                        <a href="{{ route('clients.index') }}"
                            class="btn btn-light px-4 rounded-pill fw-medium">Cancelar</a>
                        <button type="submit" class="btn-primary-premium">
                            <i class="bi bi-save"></i> Guardar Cambios
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
