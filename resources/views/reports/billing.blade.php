@extends('layouts.app')

@section('title', 'Reporte de Facturación')

@section('styles')
<style>
    .kpi-card {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
    .kpi-card .kpi-value {
        font-size: 1.75rem;
        font-weight: 700;
        font-family: 'Outfit', sans-serif;
        line-height: 1.1;
        margin: 0.25rem 0;
    }
    .kpi-card .kpi-label {
        font-size: 0.75rem;
        color: var(--text-muted);
        font-weight: 500;
    }
    .preset-btn {
        font-size: 0.72rem;
        padding: 0.3rem 0.8rem;
        border-radius: 99px;
    }
    .report-section {
        background: white;
        border-radius: var(--radius);
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .report-section-title {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--text-muted);
        margin-bottom: 1rem;
    }
    .invoice-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 2px 10px;
        border-radius: 99px;
        font-size: 0.78rem;
        font-weight: 600;
    }
    .invoice-badge.has-invoice {
        background: #f0fdf4;
        color: #16a34a;
        border: 1px solid #bbf7d0;
    }
    .invoice-badge.no-invoice {
        background: #fef3c7;
        color: #d97706;
        border: 1px solid #fde68a;
    }
</style>
@endsection

@section('content')
<div class="animate-in">
    <div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-3">
        <div>
            <h2 class="outfit fw-bold mb-1">Reporte de Facturación</h2>
            <p class="text-muted mb-0">Control de OTs y sus facturas asociadas.</p>
        </div>

        <form method="GET" class="d-flex gap-2 align-items-end flex-wrap">
            <div>
                <label class="form-label" style="font-size:0.78rem;">Desde</label>
                <input type="date" name="from" class="form-control form-control-sm" value="{{ $from }}">
            </div>
            <div>
                <label class="form-label" style="font-size:0.78rem;">Hasta</label>
                <input type="date" name="to" class="form-control form-control-sm" value="{{ $to }}">
            </div>
            <div>
                <label class="form-label" style="font-size:0.78rem;">Estado</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">Todos</option>
                    <option value="invoiced" {{ $statusFilter === 'invoiced' ? 'selected' : '' }}>Facturadas</option>
                    <option value="delivered" {{ $statusFilter === 'delivered' ? 'selected' : '' }}>Entregadas (sin factura)</option>
                    <option value="completed" {{ $statusFilter === 'completed' ? 'selected' : '' }}>Completadas</option>
                    <option value="approved" {{ $statusFilter === 'approved' ? 'selected' : '' }}>Aprobadas</option>
                </select>
            </div>
            @if(auth()->user()->role === 'admin')
            <div>
                <label class="form-label" style="font-size:0.78rem;">Sucursal</label>
                <select name="branch_id" class="form-select form-select-sm">
                    <option value="">Todas</option>
                    @foreach($branches as $b)
                    <option value="{{ $b->id }}" {{ $branchId == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            <button type="submit" class="btn-primary-premium" style="padding:0.4rem 1rem;">
                <i class="bi bi-funnel"></i> Aplicar
            </button>
        </form>
    </div>

    {{-- KPIs --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="kpi-card" style="border-left:4px solid var(--primary);">
                <div class="kpi-label">Total OTs</div>
                <div class="kpi-value" style="color:var(--primary);">{{ $summary['total'] }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="kpi-card" style="border-left:4px solid #16a34a;">
                <div class="kpi-label">Facturadas</div>
                <div class="kpi-value" style="color:#16a34a;">{{ $summary['invoiced'] }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="kpi-card" style="border-left:4px solid #d97706;">
                <div class="kpi-label">Pendientes de Factura</div>
                <div class="kpi-value" style="color:#d97706;">{{ $summary['pending'] }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="kpi-card" style="border-left:4px solid var(--accent);">
                <div class="kpi-label">Total Facturado</div>
                <div class="kpi-value" style="color:var(--accent);">${{ number_format($summary['total_invoiced'], 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    {{-- Resumen montos --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="kpi-card">
                <div class="kpi-label">Monto Taller (Neto)</div>
                <div class="kpi-value" style="font-size:1.3rem;">${{ number_format($summary['total_workshop'], 0, ',', '.') }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="kpi-card">
                <div class="kpi-label">Monto Autorizado (Neto)</div>
                <div class="kpi-value" style="font-size:1.3rem;">${{ number_format($summary['total_authorized'], 0, ',', '.') }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="kpi-card">
                <div class="kpi-label">Monto Pendiente por Facturar</div>
                <div class="kpi-value" style="font-size:1.3rem; color:#d97706;">${{ number_format($summary['total_pending'], 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    {{-- Tabla detalle --}}
    <div class="report-section">
        <p class="report-section-title"><i class="bi bi-table"></i> Detalle OT — Factura</p>

        <div class="table-responsive">
            <table class="table table-sm mb-0" style="font-size:0.84rem;">
                <thead>
                    <tr>
                        <th>Folio</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Vehículo</th>
                        <th>Aseguradora</th>
                        <th>Estado</th>
                        <th>N° Factura</th>
                        <th class="text-end">M. Taller</th>
                        <th class="text-end">M. Autorizado</th>
                        <th class="text-end">Total c/IVA</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($workOrders as $wo)
                    <tr>
                        <td class="fw-bold">
                            <a href="{{ route('work-orders.show', $wo) }}" style="color:var(--primary);">
                                {{ $wo->folio ? '#'.$wo->folio : '—' }}
                            </a>
                        </td>
                        <td class="text-muted">{{ \Carbon\Carbon::parse($wo->date)->format('d/m/Y') }}</td>
                        <td>{{ $wo->client->name ?? '—' }}</td>
                        <td>
                            <span class="badge bg-dark text-white" style="font-size:0.72rem;">{{ $wo->vehicle->license_plate ?? '—' }}</span>
                            <span class="text-muted small">{{ $wo->vehicle->brand ?? '' }} {{ $wo->vehicle->model ?? '' }}</span>
                        </td>
                        <td class="text-muted">{{ $wo->insuranceCompany->name ?? 'Particular' }}</td>
                        <td>
                            <span class="badge bg-{{ $wo->status === 'invoiced' ? 'success' : ($wo->status === 'delivered' ? 'info' : 'secondary') }}" style="font-size:0.72rem;">
                                {{ $wo->status_label }}
                            </span>
                        </td>
                        <td>
                            @if($wo->invoice_number)
                                <span class="invoice-badge has-invoice">
                                    <i class="bi bi-check-circle-fill"></i> {{ $wo->invoice_number }}
                                </span>
                            @else
                                <span class="invoice-badge no-invoice">
                                    <i class="bi bi-clock"></i> Pendiente
                                </span>
                            @endif
                        </td>
                        <td class="text-end">${{ number_format($wo->total_workshop, 0, ',', '.') }}</td>
                        <td class="text-end fw-semibold" style="color:var(--primary);">${{ number_format($wo->total_authorized, 0, ',', '.') }}</td>
                        <td class="text-end fw-bold">${{ number_format($wo->total_amount, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center text-secondary py-4">
                            Sin datos en el periodo seleccionado.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                @if($workOrders->count())
                <tfoot style="background:#f8fafc;">
                    <tr class="fw-bold">
                        <td colspan="7" class="text-end">TOTALES</td>
                        <td class="text-end">${{ number_format($workOrders->sum('total_workshop'), 0, ',', '.') }}</td>
                        <td class="text-end" style="color:var(--primary);">${{ number_format($workOrders->sum('total_authorized'), 0, ',', '.') }}</td>
                        <td class="text-end">${{ number_format($workOrders->sum('total_amount'), 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection
