@extends('layouts.app')

@section('title', 'Cotizaciones')

@section('content')
<div class="animate-in">

    {{-- ─── Header ─────────────────────────────────────────── --}}
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h2 class="page-title">Cotizaciones</h2>
            <p class="page-subtitle">Gestión de cotizaciones para clientes y compañías de seguros.</p>
        </div>
        <a href="{{ route('quotations.create') }}" class="btn-primary-premium">
            <i class="bi bi-plus-lg"></i> Nueva Cotización
        </a>
    </div>

    <div class="card">

        {{-- ─── Filter Bar ─────────────────────────────────── --}}
        <div class="filter-bar">
            <form action="{{ route('quotations.index') }}" method="GET"
                class="d-flex gap-3 align-items-center flex-wrap">
                <div class="input-icon-wrap flex-grow-1" style="max-width:420px;">
                    <i class="bi bi-search"></i>
                    <input type="text" name="search" class="form-control"
                        placeholder="Buscar por folio, cliente, patente o aseguradora…"
                        value="{{ request('search') }}">
                </div>
                <select name="status" class="form-select" style="max-width:200px;"
                    onchange="this.form.submit()">
                    <option value="">Todos los estados</option>
                    <option value="draft"    {{ request('status') == 'draft'    ? 'selected' : '' }}>Borrador</option>
                    <option value="sent"     {{ request('status') == 'sent'     ? 'selected' : '' }}>Enviado / Pendiente</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Aprobado</option>
                    <option value="finished" {{ request('status') == 'finished' ? 'selected' : '' }}>Terminado</option>
                    <option value="invoiced" {{ request('status') == 'invoiced' ? 'selected' : '' }}>Facturado</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rechazado</option>
                </select>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn-primary-premium" style="padding:0.5625rem 1rem;">
                        <i class="bi bi-search"></i> Filtrar
                    </button>
                    @if(request()->anyFilled(['search', 'status']))
                        <a href="{{ route('quotations.index') }}" class="btn-app-secondary" title="Limpiar filtros">
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
                        <th class="text-end">Total</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($quotations as $q)
                        <tr>
                            <td>
                                <a href="{{ route('quotations.show', $q) }}"
                                    class="fw-700 text-decoration-none ls-tight"
                                    style="font-weight:700;color:var(--primary);">
                                    #{{ $q->folio }}
                                </a>
                            </td>
                            <td class="fw-500" style="font-weight:500;">{{ $q->client->name }}</td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="plate-badge">{{ strtoupper($q->vehicle->license_plate) }}</span>
                                    <span class="text-xs mt-1" style="color:var(--text-muted);">
                                        {{ $q->vehicle->brand }} {{ $q->vehicle->model }}
                                    </span>
                                </div>
                            </td>
                            <td class="text-sm" style="color:var(--text-secondary);">
                                {{ \Carbon\Carbon::parse($q->date)->format('d/m/Y') }}
                            </td>
                            <td>
                                <span class="status-badge status-{{ $q->status }}">
                                    {{ $q->status_label }}
                                </span>
                            </td>
                            <td class="text-end fw-700 ls-tight"
                                style="font-weight:700;color:var(--text-primary);">
                                ${{ number_format($q->total_amount, 0, ',', '.') }}
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
                                            <a class="dropdown-item" href="{{ route('quotations.show', $q) }}">
                                                <i class="bi bi-eye me-2"></i> Ver Detalle
                                            </a>
                                        </li>
                                        @if(!in_array($q->status, ['invoiced', 'rejected']))
                                        <li>
                                            <a class="dropdown-item" href="{{ route('quotations.edit', $q) }}">
                                                <i class="bi bi-pencil me-2"></i> Editar
                                            </a>
                                        </li>
                                        @endif
                                        <li><hr class="dropdown-divider my-1"></li>
                                        <li>
                                            <form action="{{ route('quotations.destroy', $q) }}" method="POST"
                                                onsubmit="return confirm('¿Eliminar la cotización #{{ $q->folio }}?')">
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
                            <td colspan="7">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="bi bi-receipt"></i>
                                    </div>
                                    <p>No se encontraron cotizaciones registradas.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ─── Pagination ───────────────────────────────────── --}}
        @if($quotations->hasPages())
            <div class="table-footer">
                {{ $quotations->links() }}
            </div>
        @endif

    </div>
</div>
@endsection
