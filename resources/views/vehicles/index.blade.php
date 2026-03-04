@extends('layouts.app')

@section('title', 'Vehículos')

@section('content')
<div class="animate-in">

    {{-- ─── Header ─────────────────────────────────────────── --}}
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h2 class="page-title">Vehículos</h2>
            <p class="page-subtitle">Control de la flota de vehículos registrados y sus propietarios.</p>
        </div>
        <a href="{{ route('vehicles.create') }}" class="btn-primary-premium">
            <i class="bi bi-car-front-fill"></i> Nuevo Vehículo
        </a>
    </div>

    <div class="card">

        {{-- ─── Filter Bar ─────────────────────────────────── --}}
        <div class="filter-bar">
            <form action="{{ route('vehicles.index') }}" method="GET"
                class="d-flex gap-3 align-items-center flex-wrap">
                <div class="input-icon-wrap flex-grow-1" style="max-width:400px;">
                    <i class="bi bi-search"></i>
                    <input type="text" name="search" class="form-control"
                        placeholder="Buscar por patente, marca, modelo o dueño…"
                        value="{{ request('search') }}">
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn-primary-premium" style="padding:0.5625rem 1rem;">
                        <i class="bi bi-search"></i> Buscar
                    </button>
                    @if(request('search'))
                        <a href="{{ route('vehicles.index') }}" class="btn-app-secondary" title="Limpiar búsqueda">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- ─── Table ───────────────────────────────────────── --}}
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Patente</th>
                        <th>Marca / Modelo</th>
                        <th>Año</th>
                        <th>Propietario</th>
                        <th>Odómetro</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vehicles as $vehicle)
                        <tr>
                            <td>
                                <span class="plate-badge">
                                    {{ strtoupper($vehicle->license_plate) }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-600 text-sm" style="font-weight:600;">
                                        {{ $vehicle->brand }}
                                    </span>
                                    <span class="text-xs" style="color:var(--text-muted);">
                                        {{ $vehicle->model }}
                                        @if($vehicle->color) · {{ $vehicle->color }}@endif
                                    </span>
                                </div>
                            </td>
                            <td class="text-sm" style="color:var(--text-secondary);">
                                {{ $vehicle->year ?? '—' }}
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-700 text-white flex-shrink-0"
                                        style="width:30px;height:30px;background:linear-gradient(135deg,var(--primary) 0%,#3b82f6 100%);font-size:0.72rem;">
                                        {{ strtoupper(mb_substr($vehicle->client->name, 0, 1)) }}
                                    </div>
                                    <span class="text-sm fw-500" style="font-weight:500;">
                                        {{ $vehicle->client->name }}
                                    </span>
                                </div>
                            </td>
                            <td class="text-sm" style="color:var(--text-secondary);font-variant-numeric:tabular-nums;">
                                {{ $vehicle->odometer ? number_format($vehicle->odometer, 0, ',', '.') . ' km' : '0 km' }}
                            </td>
                            <td class="text-end">
                                <div class="dropdown">
                                    <button class="btn btn-sm border-0 bg-transparent"
                                        style="color:var(--text-muted);"
                                        type="button" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('vehicles.show', $vehicle) }}">
                                                <i class="bi bi-clock-history me-2"></i> Ver Historial
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('vehicles.edit', $vehicle) }}">
                                                <i class="bi bi-pencil me-2"></i> Editar
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider my-1"></li>
                                        <li>
                                            <form action="{{ route('vehicles.destroy', $vehicle) }}" method="POST"
                                                onsubmit="return confirm('¿Estás seguro de eliminar este vehículo?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="bi bi-trash me-2"></i> Eliminar
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="bi bi-car-front"></i>
                                    </div>
                                    <p>No hay vehículos registrados aún.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ─── Pagination ───────────────────────────────────── --}}
        @if($vehicles->hasPages())
            <div class="table-footer">
                {{ $vehicles->links() }}
            </div>
        @endif

    </div>
</div>
@endsection
