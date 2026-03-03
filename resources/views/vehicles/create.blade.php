@extends('layouts.app')

@section('title', 'Nuevo Vehículo')

@section('content')
    <div class="animate-in" style="max-width: 800px; margin: 0 auto;">
        <div class="mb-4">
            <a href="{{ route('vehicles.index') }}" class="text-decoration-none text-secondary small fw-medium">
                <i class="bi bi-arrow-left"></i> Volver al listado
            </a>
            <h2 class="fw-bold mt-2">Registrar Nuevo Vehículo</h2>
            <p class="text-secondary small">Asocia un vehículo a un cliente existente para gestionar sus presupuestos.</p>
        </div>

        <div class="card p-4">
            <form action="{{ route('vehicles.store') }}" method="POST">
                @csrf
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">Dueño (Cliente) <span
                                class="text-danger">*</span></label>
                        <select name="client_id" class="form-select @error('client_id') is-invalid @enderror" required>
                            <option value="">Selecciona un cliente...</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                    {{ $client->name }} ({{ $client->rut_dni }})
                                </option>
                            @endforeach
                        </select>
                        @error('client_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">Patente <span class="text-danger">*</span></label>
                        <input type="text" name="license_plate"
                            class="form-control @error('license_plate') is-invalid @enderror"
                            value="{{ old('license_plate') }}" placeholder="Ej: AB CD 12 o ABCD12" required>
                        <div class="form-text tiny text-secondary">Formato chileno estándar.</div>
                        @error('license_plate')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">Marca <span class="text-danger">*</span></label>
                        <input type="text" name="brand" class="form-control @error('brand') is-invalid @enderror"
                            value="{{ old('brand') }}" placeholder="Ej: Toyota" required>
                        @error('brand')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">Modelo <span class="text-danger">*</span></label>
                        <input type="text" name="model" class="form-control @error('model') is-invalid @enderror"
                            value="{{ old('model') }}" placeholder="Ej: Hilux" required>
                        @error('model')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold small">Año</label>
                        <input type="number" name="year" class="form-control @error('year') is-invalid @enderror"
                            value="{{ old('year', date('Y')) }}" min="1950" max="{{ date('Y') + 1 }}">
                        @error('year')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold small">Color</label>
                        <input type="text" name="color" class="form-control @error('color') is-invalid @enderror"
                            value="{{ old('color') }}" placeholder="Ej: Gris Metálico">
                        @error('color')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold small">KM Actual</label>
                        <input type="number" name="odometer" class="form-control @error('odometer') is-invalid @enderror"
                            value="{{ old('odometer', 0) }}" min="0">
                        @error('odometer')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold small">Nº de Chasis / VIN</label>
                        <input type="text" name="vin_chassis" class="form-control font-monospace @error('vin_chassis') is-invalid @enderror"
                            value="{{ old('vin_chassis') }}" placeholder="Ej: KNAHU812AE7047400">
                        @error('vin_chassis')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 pt-3 border-top mt-4 d-flex justify-content-end gap-2">
                        <a href="{{ route('vehicles.index') }}"
                            class="btn btn-light px-4 rounded-pill fw-medium">Cancelar</a>
                        <button type="submit" class="btn-primary-premium">
                            <i class="bi bi-save"></i> Guardar Vehículo
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection