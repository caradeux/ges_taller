@extends('layouts.app')

@section('title', 'Órdenes de Trabajo')

@section('styles')
<style>
    .ot-stats-bar {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 10px;
        margin-bottom: 1.25rem;
    }
    .ot-stat {
        text-align: center;
        padding: 12px 8px;
        border-radius: var(--radius);
        background: var(--card-bg);
        border: 1px solid var(--border-light);
        transition: var(--transition);
        cursor: pointer;
        text-decoration: none;
    }
    .ot-stat:hover { border-color: var(--primary-border); background: var(--primary-light); }
    .ot-stat.active { border-color: var(--primary); background: var(--primary-light); box-shadow: 0 0 0 2px rgba(30,64,175,0.1); }
    .ot-stat .count { font-size: 1.3rem; font-weight: 800; color: var(--text-primary); line-height: 1; }
    .ot-stat .label { font-size: 0.65rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); margin-top: 4px; }

    .ot-row { transition: background 0.15s; }
    .ot-row:hover { background: #f5f7ff !important; }
    .ot-row td { vertical-align: middle; }

    .ot-client-name { font-weight: 600; font-size: 0.88rem; color: var(--text-primary); }
    .ot-client-rut { font-size: 0.74rem; color: var(--text-muted); }

    .ot-amount { font-weight: 700; font-variant-numeric: tabular-nums; letter-spacing: -0.01em; }
</style>
@endsection

@section('content')
<div class="animate-in">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h2 class="page-title">Ordenes de Trabajo</h2>
            <p class="page-subtitle">Gestion completa de OTs del taller automotriz.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('work-orders.followup') }}" class="btn-app-secondary">
                <i class="bi bi-clipboard-check"></i> Seguimiento
            </a>
            <a href="{{ route('work-orders.create') }}" class="btn-primary-premium">
                <i class="bi bi-plus-lg"></i> Nueva OT
            </a>
        </div>
    </div>

    {{-- Quick Status Stats --}}
    @php
        $statusCounts = $workOrders->getCollection()->groupBy('status')->map->count();
    @endphp
    <div class="ot-stats-bar">
        <a href="{{ route('work-orders.index') }}" class="ot-stat {{ !request('status') ? 'active' : '' }}">
            <div class="count">{{ $workOrders->total() }}</div>
            <div class="label">Todas</div>
        </a>
        @foreach([
            'intake' => ['Ingreso', '#d97706'],
            'budget_sent' => ['Enviadas', '#0284c7'],
            'approved' => ['Aprobadas', '#16a34a'],
            'waiting_parts' => ['Esp. Repuestos', '#ea580c'],
            'in_repair' => ['En Reparacion', '#7c3aed'],
            'completed' => ['Completadas', '#0d9488'],
            'delivered' => ['Entregadas', '#6d28d9'],
            'invoiced' => ['Facturadas', '#065f46'],
        ] as $st => $meta)
        <a href="{{ route('work-orders.index', ['status' => $st]) }}" class="ot-stat {{ request('status') == $st ? 'active' : '' }}">
            <div class="count" style="color:{{ $meta[1] }};">{{ $statusCounts[$st] ?? 0 }}</div>
            <div class="label">{{ $meta[0] }}</div>
        </a>
        @endforeach
    </div>

    <div class="card">
        {{-- Filter Bar --}}
        <div class="filter-bar">
            <form action="{{ route('work-orders.index') }}" method="GET" class="d-flex gap-3 align-items-center flex-wrap">
                <div class="input-icon-wrap flex-grow-1" style="max-width:400px;">
                    <i class="bi bi-search"></i>
                    <input type="text" name="search" class="form-control"
                        placeholder="Buscar por folio, cliente, patente, aseguradora..."
                        value="{{ request('search') }}">
                </div>
                @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <select name="tag" class="form-select" style="max-width:180px;" onchange="this.form.submit()">
                    <option value="">Todas las etiquetas</option>
                    @foreach($tags as $tag)
                    <option value="{{ $tag->id }}" {{ request('tag') == $tag->id ? 'selected' : '' }}>{{ $tag->name }}</option>
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

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th style="width:90px;">Folio</th>
                        <th>Cliente</th>
                        <th>Vehiculo</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Etiquetas</th>
                        <th class="text-end">Total</th>
                        <th class="text-end" style="width:60px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($workOrders as $wo)
                    <tr class="ot-row">
                        <td>
                            <a href="{{ route('work-orders.show', $wo) }}" class="text-decoration-none"
                                style="font-weight:700;color:{{ $wo->folio ? 'var(--primary)' : 'var(--text-muted)' }};">
                                {{ $wo->folio ? '#'.$wo->folio : '—' }}
                            </a>
                        </td>
                        <td>
                            <div class="ot-client-name">{{ $wo->client->name }}</div>
                            <div class="ot-client-rut">{{ $wo->client->rut_dni }}</div>
                        </td>
                        <td>
                            <span class="plate-badge">{{ strtoupper($wo->vehicle->license_plate) }}</span>
                            <span class="text-xs ms-1" style="color:var(--text-muted);">{{ $wo->vehicle->brand }} {{ $wo->vehicle->model }}</span>
                        </td>
                        <td class="text-sm" style="color:var(--text-secondary);">
                            {{ \Carbon\Carbon::parse($wo->date)->format('d/m/Y') }}
                        </td>
                        <td>
                            <span class="status-badge status-{{ $wo->status }}">{{ $wo->status_label }}</span>
                        </td>
                        <td>
                            @foreach($wo->tags as $tag)
                            <span class="badge rounded-pill" style="background:{{ $tag->color }};font-size:0.65rem;">{{ $tag->name }}</span>
                            @endforeach
                        </td>
                        <td class="text-end ot-amount">${{ number_format($wo->total_amount, 0, ',', '.') }}</td>
                        <td class="text-end">
                            <div class="dropdown">
                                <button class="btn btn-sm border-0 bg-transparent" style="color:var(--text-muted);" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="{{ route('work-orders.show', $wo) }}"><i class="bi bi-eye me-2"></i>Ver Detalle</a></li>
                                    @if($wo->status !== 'invoiced')
                                    <li><a class="dropdown-item" href="{{ route('work-orders.edit', $wo) }}"><i class="bi bi-pencil me-2"></i>Editar</a></li>
                                    @endif
                                    <li><hr class="dropdown-divider my-1"></li>
                                    <li>
                                        <form action="{{ route('work-orders.destroy', $wo) }}" method="POST" onsubmit="return confirm('Eliminar OT {{ $wo->folio_display }}?')">
                                            @csrf @method('DELETE')
                                            <button class="dropdown-item text-danger"><i class="bi bi-trash me-2"></i>Eliminar</button>
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
                                <div class="empty-state-icon"><i class="bi bi-tools"></i></div>
                                <p>No se encontraron ordenes de trabajo.</p>
                                <a href="{{ route('work-orders.create') }}" class="btn-primary-premium mt-2">
                                    <i class="bi bi-plus-lg"></i> Crear primera OT
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($workOrders->hasPages())
        <div class="table-footer">{{ $workOrders->links() }}</div>
        @endif
    </div>
</div>
@endsection
