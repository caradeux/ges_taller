@extends('layouts.app')

@section('title', 'Órdenes de Trabajo')

@section('content')
<div class="animate-in">

    {{-- ─── Header ─────────────────────────────────────────── --}}
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h2 class="page-title">Órdenes de Trabajo</h2>
            <p class="page-subtitle">Gestión de órdenes de trabajo del taller.</p>
        </div>
        <a href="{{ route('work-orders.create') }}" class="btn-primary-premium">
            <i class="bi bi-plus-lg"></i> Nueva OT
        </a>
    </div>

    <div class="card">

        {{-- ─── Filter Bar ─────────────────────────────────── --}}
        <div class="filter-bar">
            <form action="{{ route('work-orders.index') }}" method="GET"
                class="d-flex gap-3 align-items-center flex-wrap">
                <div class="input-icon-wrap flex-grow-1" style="max-width:420px;">
                    <i class="bi bi-search"></i>
                    <input type="text" name="search" class="form-control"
                        placeholder="Buscar por folio, cliente, patente o aseguradora…"
                        value="{{ request('search') }}">
                </div>
                <select name="status" class="form-select" style="max-width:220px;"
                    onchange="this.form.submit()">
                    <option value="">Todos los estados</option>
                    <option value="intake"        {{ request('status') == 'intake'        ? 'selected' : '' }}>Ingreso</option>
                    <option value="budget_sent"   {{ request('status') == 'budget_sent'   ? 'selected' : '' }}>Presupuesto Enviado</option>
                    <option value="approved"      {{ request('status') == 'approved'      ? 'selected' : '' }}>Aprobado</option>
                    <option value="waiting_parts" {{ request('status') == 'waiting_parts' ? 'selected' : '' }}>Esperando Repuestos</option>
                    <option value="in_repair"     {{ request('status') == 'in_repair'     ? 'selected' : '' }}>En Reparación</option>
                    <option value="completed"     {{ request('status') == 'completed'     ? 'selected' : '' }}>Completado</option>
                    <option value="delivered"     {{ request('status') == 'delivered'     ? 'selected' : '' }}>Entregado</option>
                    <option value="invoiced"      {{ request('status') == 'invoiced'      ? 'selected' : '' }}>Facturado</option>
                </select>
                <select name="tag" class="form-select" style="max-width:200px;"
                    onchange="this.form.submit()">
                    <option value="">Todos los tags</option>
                    @foreach($tags as $tag)
                        <option value="{{ $tag->id }}" {{ request('tag') == $tag->id ? 'selected' : '' }}>
                            {{ $tag->name }}
                        </option>
                    @endforeach
                </select>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn-primary-premium" style="padding:0.5625rem 1rem;">
                        <i class="bi bi-search"></i> Filtrar
                    </button>
                    @if(request()->anyFilled(['search', 'status', 'tag']))
                        <a href="{{ route('work-orders.index') }}" class="btn-app-secondary" title="Limpiar filtros">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- ─── Table ───────────────────────────────────────── --}}
        <div class="table-responsive">
            <table class="table mb-0 text-nowrap">
                <thead>
                    <tr>
                        <th>Folio</th>
                        <th>Cliente</th>
                        <th>Vehículo</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Tags</th>
                        <th class="text-end">Total Autorizado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($workOrders as $wo)
                        <tr>
                            <td>
                                <a href="{{ route('work-orders.show', $wo) }}"
                                    class="fw-700 text-decoration-none ls-tight"
                                    style="font-weight:700;color:{{ $wo->folio ? 'var(--primary)' : 'var(--text-muted)' }};">
                                    {{ $wo->folio ? '#'.$wo->folio : 'Sin Folio' }}
                                </a>
                            </td>
                            <td class="fw-500" style="font-weight:500;">{{ $wo->client->name }}</td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="plate-badge">{{ strtoupper($wo->vehicle->license_plate) }}</span>
                                    <span class="text-xs mt-1" style="color:var(--text-muted);">
                                        {{ $wo->vehicle->brand }} {{ $wo->vehicle->model }}
                                    </span>
                                </div>
                            </td>
                            <td class="text-sm" style="color:var(--text-secondary);">
                                {{ \Carbon\Carbon::parse($wo->date)->format('d/m/Y') }}
                            </td>
                            <td>
                                <span class="status-badge status-{{ $wo->status }}">
                                    {{ $wo->status_label }}
                                </span>
                            </td>
                            <td>
                                @foreach($wo->tags as $tag)
                                    <span class="badge rounded-pill" style="background-color:{{ $tag->color }};color:#fff;">
                                        {{ $tag->name }}
                                    </span>
                                @endforeach
                            </td>
                            <td class="text-end fw-700 ls-tight"
                                style="font-weight:700;color:var(--text-primary);">
                                ${{ number_format($wo->total_authorized, 0, ',', '.') }}
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
                                            <a class="dropdown-item" href="{{ route('work-orders.show', $wo) }}">
                                                <i class="bi bi-eye me-2"></i> Ver Detalle
                                            </a>
                                        </li>
                                        @if($wo->status !== 'invoiced')
                                        <li>
                                            <a class="dropdown-item" href="{{ route('work-orders.edit', $wo) }}">
                                                <i class="bi bi-pencil me-2"></i> Editar
                                            </a>
                                        </li>
                                        @endif
                                        <li><hr class="dropdown-divider my-1"></li>
                                        <li>
                                            <form action="{{ route('work-orders.destroy', $wo) }}" method="POST"
                                                onsubmit="return confirm('¿Eliminar la orden de trabajo ({{ $wo->folio_display }})?')">
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
                            <td colspan="8">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="bi bi-wrench-adjustable"></i>
                                    </div>
                                    <p>No se encontraron órdenes de trabajo registradas.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ─── Pagination ───────────────────────────────────── --}}
        @if($workOrders->hasPages())
            <div class="table-footer">
                {{ $workOrders->links() }}
            </div>
        @endif

    </div>
</div>
@endsection
