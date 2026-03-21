@extends('layouts.app')

@section('title', 'Ficha de Cliente')

@section('content')
    <div class="animate-in">
        <div class="mb-4 d-flex justify-content-between align-items-start">
            <div>
                <a href="{{ route('clients.index') }}" class="text-decoration-none text-secondary small fw-medium">
                    <i class="bi bi-arrow-left"></i> Volver al listado
                </a>
                <h2 class="page-title mt-2 mb-0">{{ $client->name }}</h2>
                <p class="page-subtitle mb-0">
                    <code style="font-size:0.85rem;font-weight:600;color:var(--text-primary);background:var(--bg-secondary);padding:0.2rem 0.5rem;border-radius:4px;">{{ $client->rut_dni }}</code>
                </p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('clients.edit', $client) }}" class="btn-app-secondary btn-sm">
                    <i class="bi bi-pencil"></i> Editar
                </a>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card p-4">
                    <h6 class="text-secondary small fw-bold text-uppercase mb-3">Información de Contacto</h6>
                    <div class="mb-3">
                        <p class="text-secondary small mb-1">Email</p>
                        <p class="fw-semibold mb-0">{{ $client->email ?? 'No registrado' }}</p>
                    </div>
                    <div class="mb-3">
                        <p class="text-secondary small mb-1">Teléfono</p>
                        <p class="fw-semibold mb-0">{{ $client->phone ?? 'No registrado' }}</p>
                    </div>
                    <div>
                        <p class="text-secondary small mb-1">Dirección</p>
                        <p class="fw-semibold mb-0">{{ $client->address ?? 'No registrada' }}</p>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="text-secondary small fw-bold text-uppercase mb-0">Vehículos del Cliente</h6>
                        <a href="{{ route('vehicles.create') }}" class="btn-primary-premium btn-sm">
                            <i class="bi bi-plus-lg"></i> Agregar Vehículo
                        </a>
                    </div>
                    @forelse($client->vehicles as $vehicle)
                        <div class="d-flex align-items-center justify-content-between p-3 rounded-3 bg-light mb-2">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-3 bg-white d-flex align-items-center justify-content-center shadow-sm"
                                    style="width: 44px; height: 44px;">
                                    <i class="bi bi-car-front text-primary"></i>
                                </div>
                                <div>
                                    <span class="plate-badge mb-1">{{ strtoupper($vehicle->license_plate) }}</span>
                                    <p class="text-secondary small mb-0">{{ $vehicle->brand }} {{ $vehicle->model }}
                                        {{ $vehicle->year ? '(' . $vehicle->year . ')' : '' }}</p>
                                </div>
                            </div>
                            <a href="{{ route('vehicles.show', $vehicle) }}"
                                class="btn btn-sm btn-light border-0 text-secondary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </div>
                    @empty
                        <p class="text-secondary text-center py-3 mb-0">Este cliente no tiene vehículos registrados.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="card">
            <div class="p-4 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="text-secondary small fw-bold text-uppercase mb-0">Historial de Órdenes de Trabajo</h6>
                <span class="badge bg-light text-secondary border">{{ $client->workOrders->count() }} total</span>
            </div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Folio</th>
                            <th>Vehículo</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th class="text-end">Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($client->workOrders->sortByDesc('date') as $q)
                            <tr>
                                <td><span class="fw-bold text-dark">#{{ $q->folio }}</span></td>
                                <td class="small">
                                    @if($q->vehicle)
                                        {{ strtoupper($q->vehicle->license_plate) }} — {{ $q->vehicle->brand }}
                                        {{ $q->vehicle->model }}
                                    @else
                                        N/A
                                    @endif
                                </td>
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
                                <td colspan="6" class="text-center py-4 text-secondary small">
                                    No hay órdenes de trabajo para este cliente.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
