@extends('layouts.app')

@section('title', 'Historial de Vehículo')

@section('content')
    <div class="animate-in">
        <div class="mb-4 d-flex justify-content-between align-items-start">
            <div>
                <a href="{{ route('vehicles.index') }}" class="text-decoration-none text-secondary small fw-medium">
                    <i class="bi bi-arrow-left"></i> Volver al listado
                </a>
                <h2 class="page-title mt-2 mb-1">
                    <span class="plate-badge" style="font-size:1.1rem;">{{ strtoupper($vehicle->license_plate) }}</span>
                </h2>
                <p class="page-subtitle mb-0">{{ $vehicle->brand }} {{ $vehicle->model }}
                    {{ $vehicle->year ? '— ' . $vehicle->year : '' }}</p>
            </div>
            <a href="{{ route('vehicles.edit', $vehicle) }}" class="btn-app-secondary btn-sm">
                <i class="bi bi-pencil"></i> Editar
            </a>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card p-4">
                    <h6 class="text-secondary small fw-bold text-uppercase mb-3">Datos del Vehículo</h6>
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="info-row">
                                <span class="info-label">Marca</span>
                                <span class="info-value">{{ $vehicle->brand }}</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="info-row">
                                <span class="info-label">Modelo</span>
                                <span class="info-value">{{ $vehicle->model }}</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="info-row">
                                <span class="info-label">Año</span>
                                <span class="info-value">{{ $vehicle->year ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="info-row">
                                <span class="info-label">Color</span>
                                <span class="info-value">{{ $vehicle->color ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="info-row">
                                <span class="info-label">Odómetro</span>
                                <span class="info-value fw-semibold text-primary">
                                    {{ $vehicle->odometer ? number_format($vehicle->odometer, 0, ',', '.') . ' km' : '0 km' }}</span>
                            </div>
                        </div>
                        @if($vehicle->vin_chassis)
                            <div class="col-12">
                                <div class="info-row">
                                    <span class="info-label">VIN / Chasis</span>
                                    <code class="info-value" style="font-size:0.85rem;">{{ $vehicle->vin_chassis }}</code>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card p-4">
                    <h6 class="text-secondary small fw-bold text-uppercase mb-3">Propietario</h6>
                    @if($vehicle->client)
                        <div class="d-flex align-items-center gap-3 mb-3 p-3 bg-light rounded-3">
                            <div class="rounded-circle bg-white d-flex align-items-center justify-content-center shadow-sm"
                                style="width: 48px; height: 48px;">
                                <i class="bi bi-person text-primary fs-5"></i>
                            </div>
                            <div>
                                <p class="fw-bold mb-0">{{ $vehicle->client->name }}</p>
                                <p class="text-secondary small mb-0">{{ $vehicle->client->rut_dni }}</p>
                            </div>
                            <a href="{{ route('clients.show', $vehicle->client) }}"
                                class="btn btn-sm btn-light border-0 text-secondary ms-auto">
                                <i class="bi bi-eye"></i> Ver Ficha
                            </a>
                        </div>
                        <div class="row g-3 text-center">
                            <div class="col-4">
                                <p class="text-secondary small mb-1">Email</p>
                                <p class="fw-semibold small mb-0">{{ $vehicle->client->email ?? 'N/A' }}</p>
                            </div>
                            <div class="col-4">
                                <p class="text-secondary small mb-1">Teléfono</p>
                                <p class="fw-semibold small mb-0">{{ $vehicle->client->phone ?? 'N/A' }}</p>
                            </div>
                            <div class="col-4">
                                <p class="text-secondary small mb-1">Órdenes de Trabajo</p>
                                <p class="fw-semibold small mb-0">{{ $vehicle->workOrders->count() }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="card">
            <div class="p-4 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="text-secondary small fw-bold text-uppercase mb-0">Historial de OTs</h6>
                <a href="{{ route('work-orders.create') }}" class="btn-primary-premium btn-sm">
                    <i class="bi bi-plus-lg"></i> Nueva OT
                </a>
            </div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Folio</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th class="text-end">Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($vehicle->workOrders->sortByDesc('date') as $q)
                            <tr>
                                <td><span class="fw-bold text-dark">#{{ $q->folio }}</span></td>
                                <td class="text-secondary small">{{ \Carbon\Carbon::parse($q->date)->format('d/m/Y') }}</td>
                                <td>
                                    <span class="status-badge status-{{ $q->status }}">
                                        {{ $q->status_label }}
                                    </span>
                                </td>
                                <td class="text-end fw-bold text-dark">
                                    ${{ number_format($q->total_amount, 0, ',', '.') }}
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('work-orders.show', $q) }}"
                                        class="btn btn-sm btn-light border-0 text-secondary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-secondary small">
                                    No hay órdenes de trabajo para este vehículo.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
