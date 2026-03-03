@extends('layouts.app')

@section('title', 'Gestión de Vehículos')

@section('content')
    <div class="animate-in">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">Vehículos</h2>
                <p class="text-secondary small mb-0">Control de la flota de vehículos registrados y sus propietarios.</p>
            </div>
            <a href="{{ route('vehicles.create') }}" class="btn-primary-premium">
                <i class="bi bi-car-front-fill"></i> Nuevo Vehículo
            </a>
        </div>

        <div class="card">
            <div class="p-4 border-bottom bg-light d-flex gap-3 align-items-center"
                style="border-radius: 1rem 1rem 0 0;">
                <form action="{{ route('vehicles.index') }}" method="GET" class="d-flex gap-3 w-100 align-items-center">
                    <div class="input-group" style="max-width: 380px;">
                        <span class="input-group-text bg-white border-end-0"><i
                                class="bi bi-search text-secondary"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0"
                            placeholder="Buscar por patente, marca, modelo o dueño..." value="{{ request('search') }}">
                    </div>
                    <button type="submit" class="btn btn-secondary btn-sm px-3">Buscar</button>
                    @if(request('search'))
                        <a href="{{ route('vehicles.index') }}" class="btn btn-outline-secondary btn-sm"
                            title="Limpiar">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </form>
            </div>

            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Patente</th>
                            <th>Marca / Modelo</th>
                            <th>Año / Color</th>
                            <th>Propietario</th>
                            <th>Odómetro</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($vehicles as $vehicle)
                            <tr>
                                <td>
                                    <span class="badge bg-white text-dark border border-dark fw-bold px-3 py-2"
                                        style="font-family: monospace; letter-spacing: 1px; font-size: 0.9rem; border-width: 2px !important; border-radius: 4px;">
                                        {{ strtoupper($vehicle->license_plate) }}
                                    </span>
                                </td>
                                <td class="fw-medium">
                                    <div class="d-flex flex-column">
                                        <span class="text-dark">{{ $vehicle->brand }}</span>
                                        <span class="text-secondary small">{{ $vehicle->model }}</span>
                                    </div>
                                </td>
                                <td class="text-secondary small">
                                    {{ $vehicle->year ?? 'N/A' }} / {{ $vehicle->color ?? 'N/A' }}
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                                            style="width: 32px; height: 32px;">
                                            <i class="bi bi-person text-primary"></i>
                                        </div>
                                        <span class="small fw-medium">{{ $vehicle->client->name }}</span>
                                    </div>
                                </td>
                                <td class="text-secondary small">
                                    {{ $vehicle->odometer ? number_format($vehicle->odometer, 0, ',', '.') . ' km' : '0 km' }}
                                </td>
                                <td class="text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light border-0" type="button" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm"
                                            style="border-radius: 0.75rem;">
                                            <li><a class="dropdown-item" href="{{ route('vehicles.show', $vehicle) }}"><i
                                                        class="bi bi-eye me-2"></i> Ver Historial</a></li>
                                            <li><a class="dropdown-item" href="{{ route('vehicles.edit', $vehicle) }}"><i
                                                        class="bi bi-pencil me-2"></i> Editar</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <form action="{{ route('vehicles.destroy', $vehicle) }}" method="POST"
                                                    onsubmit="return confirm('¿Estás seguro de eliminar este vehículo?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger"><i
                                                            class="bi bi-trash me-2"></i> Eliminar</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="bi bi-car-front fs-1 text-light"></i>
                                    <p class="text-secondary mt-2">No hay vehículos registrados aún.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($vehicles->hasPages())
                <div class="p-3 border-top bg-light" style="border-radius: 0 0 1rem 1rem;">
                    {{ $vehicles->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection