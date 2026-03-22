@extends('layouts.app')

@section('title', 'OT ' . ($workOrder->folio ? '#'.$workOrder->folio : '— Sin Folio'))

@section('styles')
<style>
    .ot-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        border-radius: var(--radius-lg);
        padding: 1.75rem 2rem;
        color: white;
        margin-bottom: 1.5rem;
        position: relative;
        overflow: hidden;
    }
    .ot-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(37,99,235,0.15), transparent 70%);
        border-radius: 50%;
    }
    .ot-hero .ot-folio {
        font-size: 2rem;
        font-weight: 800;
        letter-spacing: -0.02em;
        line-height: 1;
    }
    .ot-hero .ot-folio small { font-size: 0.85rem; font-weight: 400; opacity: 0.6; }
    .ot-hero .ot-meta { font-size: 0.82rem; color: #94a3b8; margin-top: 0.5rem; }
    .ot-hero .ot-meta i { margin-right: 4px; }

    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 50px;
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.02em;
    }

    .action-bar {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        align-items: center;
    }

    .info-card-header {
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 1rem;
        padding-bottom: 0.6rem;
        border-bottom: 1px solid var(--border-light);
    }
    .info-card-header i { font-size: 0.9rem; }

    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 0.35rem 0;
        font-size: 0.84rem;
    }
    .detail-row .dt { color: var(--text-muted); font-size: 0.78rem; }
    .detail-row .dd { font-weight: 600; color: var(--text-primary); }

    .inv-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 4px 16px;
    }
    .inv-item {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 3px 0;
        font-size: 0.8rem;
    }
    .inv-check { color: #16a34a; font-weight: 700; }
    .inv-cross { color: #dc2626; font-weight: 700; }

    .timeline-item {
        display: flex;
        gap: 12px;
        position: relative;
    }
    .timeline-item:not(:last-child)::after {
        content: '';
        position: absolute;
        left: 15px;
        top: 36px;
        bottom: -12px;
        width: 2px;
        background: var(--border-light);
    }
    .timeline-dot {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        flex-shrink: 0;
        z-index: 1;
    }

    .triple-totals {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        margin-bottom: 12px;
    }
    .triple-total-card {
        text-align: center;
        padding: 12px;
        border-radius: var(--radius);
        background: var(--bg-main);
    }
    .triple-total-card .label { font-size: 0.7rem; font-weight: 600; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.05em; }
    .triple-total-card .value { font-size: 1.15rem; font-weight: 800; margin-top: 2px; }

    .total-final-bar {
        background: linear-gradient(135deg, #0f172a, #1e293b);
        color: white;
        border-radius: var(--radius);
        padding: 16px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .total-final-bar .tf-label { font-size: 0.85rem; font-weight: 600; opacity: 0.8; }
    .total-final-bar .tf-iva { font-size: 0.78rem; opacity: 0.5; }
    .total-final-bar .tf-amount { font-size: 1.6rem; font-weight: 800; letter-spacing: -0.02em; }
</style>
@endsection

@section('content')
<div class="animate-in">

    {{-- ═══ HERO HEADER ═══ --}}
    <div class="ot-hero">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <a href="{{ route('work-orders.index') }}"
                    class="d-inline-flex align-items-center gap-1 text-decoration-none mb-2"
                    style="font-size:0.78rem;font-weight:500;color:#64748b;">
                    <i class="bi bi-arrow-left"></i> Ordenes de Trabajo
                </a>
                <div class="ot-folio">
                    {{ $workOrder->folio ? 'OT #'.$workOrder->folio : 'OT — Sin Folio' }}
                    @if($workOrder->invoice_number)
                        <span style="font-size:0.65em; opacity:0.8; margin-left:0.5rem;">
                            <i class="bi bi-receipt"></i> Factura N° {{ $workOrder->invoice_number }}
                        </span>
                    @endif
                </div>
                <div class="ot-meta">
                    <i class="bi bi-calendar3"></i> {{ \Carbon\Carbon::parse($workOrder->date)->isoFormat('D [de] MMMM [de] YYYY') }}
                    @if($workOrder->vehicle)
                    &nbsp;&middot;&nbsp;
                    <i class="bi bi-car-front"></i> {{ strtoupper($workOrder->vehicle->license_plate) }} — {{ $workOrder->vehicle->brand }} {{ $workOrder->vehicle->model }}
                    @endif
                </div>
            </div>
            <div class="d-flex flex-column align-items-end gap-2">
                <span class="status-pill status-{{ $workOrder->status }}">
                    <i class="bi bi-circle-fill" style="font-size:0.5rem;"></i>
                    {{ $workOrder->status_label }}
                </span>
                @if($workOrder->tags->count())
                <div class="d-flex gap-1 flex-wrap justify-content-end">
                    @foreach($workOrder->tags as $tag)
                    <span class="badge rounded-pill" style="background:{{ $tag->color }};font-size:0.68rem;">{{ $tag->name }}</span>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ═══ ACTION BAR ═══ --}}
    <div class="action-bar mb-4">
        @if($workOrder->status == 'intake')
            <form action="{{ route('work-orders.status', $workOrder) }}" method="POST">
                @csrf <input type="hidden" name="status" value="budget_sent">
                <button type="submit" class="btn-info-app"><i class="bi bi-send"></i> Enviar Presupuesto</button>
            </form>
        @elseif($workOrder->status == 'budget_sent')
            <form action="{{ route('work-orders.status', $workOrder) }}" method="POST">
                @csrf <input type="hidden" name="status" value="approved">
                <button type="submit" class="btn-success-app"><i class="bi bi-check-lg"></i> Aprobar</button>
            </form>
        @elseif($workOrder->status == 'approved')
            <form action="{{ route('work-orders.status', $workOrder) }}" method="POST" class="d-inline">
                @csrf <input type="hidden" name="status" value="waiting_parts">
                <button type="submit" class="btn-accent-app"><i class="bi bi-box-seam"></i> Esperando Repuestos</button>
            </form>
            <form action="{{ route('work-orders.status', $workOrder) }}" method="POST" class="d-inline">
                @csrf <input type="hidden" name="status" value="in_repair">
                <button type="submit" class="btn-primary-premium"><i class="bi bi-wrench"></i> Iniciar Reparacion</button>
            </form>
        @elseif($workOrder->status == 'waiting_parts')
            <form action="{{ route('work-orders.status', $workOrder) }}" method="POST">
                @csrf <input type="hidden" name="status" value="in_repair">
                <button type="submit" class="btn-primary-premium"><i class="bi bi-wrench"></i> Iniciar Reparacion</button>
            </form>
        @elseif($workOrder->status == 'in_repair')
            <form action="{{ route('work-orders.status', $workOrder) }}" method="POST">
                @csrf <input type="hidden" name="status" value="completed">
                <button type="submit" class="btn-success-app"><i class="bi bi-check-circle"></i> Completar</button>
            </form>
        @elseif($workOrder->status == 'completed')
            <form action="{{ route('work-orders.status', $workOrder) }}" method="POST">
                @csrf <input type="hidden" name="status" value="delivered">
                <button type="submit" class="btn-accent-app"><i class="bi bi-truck"></i> Entregar</button>
            </form>
        @elseif($workOrder->status == 'delivered')
            <button type="button" class="btn-primary-premium" data-bs-toggle="modal" data-bs-target="#modalInvoice">
                <i class="bi bi-receipt"></i> Facturar
            </button>
        @endif

        @if($workOrder->status != 'invoiced')
        <a href="{{ route('work-orders.edit', $workOrder) }}" class="btn-app-secondary"><i class="bi bi-pencil"></i> Editar</a>
        @endif
        <a href="{{ route('work-orders.intake-pdf', $workOrder) }}" class="btn-app-secondary"><i class="bi bi-clipboard-check"></i> Acta Ingreso</a>
        @if($workOrder->client?->phone)
        <a href="{{ \App\Helpers\WhatsAppHelper::buildUrl($workOrder->client->phone, \App\Helpers\WhatsAppHelper::buildStatusMessage($workOrder, $workOrder->status)) }}"
            target="_blank" class="btn btn-sm text-white" style="background:#25D366;border:none;border-radius:var(--radius-sm);padding:0.45rem 0.9rem;font-size:0.82rem;font-weight:600;">
            <i class="bi bi-whatsapp"></i> WhatsApp
        </a>
        @endif
        @if($workOrder->folio)
        <a href="{{ route('work-orders.pdf', $workOrder) }}" class="btn-accent-app"><i class="bi bi-file-earmark-pdf"></i> PDF OT</a>
        @endif
    </div>

    {{-- ═══ INFO CARDS ═══ --}}
    <div class="row g-3 mb-4">
        {{-- Cliente --}}
        <div class="col-md-4">
            <div class="card p-4 h-100">
                <div class="info-card-header">
                    <i class="bi bi-person-fill" style="color:var(--primary);"></i> Cliente
                </div>
                <div class="detail-row"><span class="dt">Nombre</span>
                    <a href="{{ route('clients.show', $workOrder->client) }}" class="dd text-decoration-none" style="color:var(--primary);">{{ $workOrder->client->name }}</a>
                </div>
                <div class="detail-row"><span class="dt">RUT</span><span class="dd">{{ $workOrder->client->rut_dni ?? '—' }}</span></div>
                <div class="detail-row"><span class="dt">Telefono</span><span class="dd">{{ $workOrder->client->phone ?? '—' }}</span></div>
                @if($workOrder->client->email)
                <div class="detail-row"><span class="dt">Email</span><span class="dd" style="font-size:0.8rem;">{{ $workOrder->client->email }}</span></div>
                @endif
                @if($workOrder->conductor_name)
                <div class="detail-row" style="margin-top:6px;padding-top:6px;border-top:1px dashed var(--border-light);">
                    <span class="dt">Conductor</span><span class="dd">{{ $workOrder->conductor_name }}</span>
                </div>
                @endif
            </div>
        </div>

        {{-- Vehiculo --}}
        <div class="col-md-4">
            <div class="card p-4 h-100">
                <div class="info-card-header">
                    <i class="bi bi-car-front-fill" style="color:var(--primary);"></i> Vehiculo
                </div>
                <div class="mb-2">
                    <span class="plate-badge" style="font-size:1.05rem;">{{ strtoupper($workOrder->vehicle->license_plate) }}</span>
                </div>
                <div class="detail-row"><span class="dt">Marca / Modelo</span><span class="dd">{{ $workOrder->vehicle->brand }} {{ $workOrder->vehicle->model }}</span></div>
                <div class="detail-row"><span class="dt">Ano</span><span class="dd">{{ $workOrder->vehicle->year ?? '—' }}</span></div>
                @if($workOrder->vehicle->color)
                <div class="detail-row"><span class="dt">Color</span><span class="dd">{{ $workOrder->vehicle->color }}</span></div>
                @endif
                <div class="detail-row"><span class="dt">KM</span><span class="dd">{{ number_format($workOrder->vehicle_inventory['km_ingreso'] ?? $workOrder->vehicle->odometer ?? 0, 0, ',', '.') }}</span></div>
                @if($workOrder->vehicle->vin_chassis)
                <div class="detail-row"><span class="dt">VIN</span><span class="dd" style="font-family:monospace;font-size:0.78rem;letter-spacing:0.5px;">{{ $workOrder->vehicle->vin_chassis }}</span></div>
                @endif
            </div>
        </div>

        {{-- Seguro --}}
        <div class="col-md-4">
            <div class="card p-4 h-100">
                <div class="info-card-header">
                    <i class="bi bi-shield-fill-check" style="color:var(--info);"></i> Seguro / Siniestro
                </div>
                @if($workOrder->insurance_company_id)
                <div class="detail-row"><span class="dt">Compania</span><span class="dd">{{ $workOrder->insuranceCompany->name }}</span></div>
                <div class="detail-row"><span class="dt">Liquidador</span><span class="dd">{{ $workOrder->liquidator?->name ?? 'No asignado' }}</span></div>
                @if($workOrder->claim_number)
                <div class="detail-row"><span class="dt">N Siniestro</span><span class="dd" style="color:var(--accent);">{{ $workOrder->claim_number }}</span></div>
                @endif
                @if($workOrder->intake_number)
                <div class="detail-row"><span class="dt">N Ingreso</span><span class="dd">{{ $workOrder->intake_number }}</span></div>
                @endif
                @else
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="status-badge" style="background:var(--border-light);color:var(--text-secondary);">Particular — sin seguro</span>
                </div>
                @endif
                @if($workOrder->deductible_amount > 0)
                <div class="detail-row" style="margin-top:6px;padding-top:6px;border-top:1px dashed var(--border-light);">
                    <span class="dt">Deducible</span>
                    <span class="dd" style="color:var(--accent);">${{ number_format($workOrder->deductible_amount, 0, ',', '.') }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ═══ INVENTARIO VEHICULAR ═══ --}}
    @if($workOrder->vehicle_inventory && count(array_filter($workOrder->vehicle_inventory)))
    <div class="card p-4 mb-4">
        <div class="info-card-header">
            <i class="bi bi-clipboard-check" style="color:var(--warning);"></i> Inventario de Ingreso
        </div>
        <div class="inv-grid">
            @foreach(\App\Models\WorkOrder::INVENTORY_ITEMS as $key => $label)
            <div class="inv-item">
                @if(!empty($workOrder->vehicle_inventory[$key]))
                    <i class="bi bi-check-circle-fill inv-check"></i>
                @else
                    <i class="bi bi-x-circle inv-cross"></i>
                @endif
                <span>{{ $label }}</span>
            </div>
            @endforeach
        </div>
        <div class="d-flex gap-4 mt-3 pt-3" style="border-top:1px solid var(--border-light);font-size:0.82rem;">
            <div><span class="text-muted">Combustible:</span> <strong>{{ $workOrder->vehicle_inventory['combustible'] ?? '—' }}</strong></div>
            <div><span class="text-muted">KM:</span> <strong>{{ number_format($workOrder->vehicle_inventory['km_ingreso'] ?? 0, 0, ',', '.') }}</strong></div>
            <div><span class="text-muted">Llaves:</span> <strong>{{ $workOrder->vehicle_inventory['llaves_count'] ?? '—' }}</strong></div>
        </div>
        @if($workOrder->objects_declaration)
        <div class="mt-2 pt-2" style="border-top:1px dashed var(--border-light);font-size:0.82rem;">
            <span class="text-muted">Declaracion:</span> {{ $workOrder->objects_declaration }}
        </div>
        @endif
    </div>
    @endif

    {{-- ═══ ITEMS TABLE ═══ --}}
    <div class="card mb-4">
        <div class="d-flex justify-content-between align-items-center p-4" style="border-bottom:1px solid var(--border-light);">
            <div>
                <h5 class="fw-bold mb-0 ls-tight"><i class="bi bi-tools me-2" style="color:var(--primary);"></i>Detalle de Trabajos</h5>
                <p class="text-xs mb-0 mt-1" style="color:var(--text-muted);">{{ $workOrder->items->count() }} item(s) &middot; {{ $workOrder->items->where('is_approved', true)->count() }} aprobados</p>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th style="width:120px;">Tipo UN</th>
                        <th>Descripcion</th>
                        <th class="text-center" style="width:75px;">Aprobado</th>
                        <th class="text-end" style="width:115px;">M. Taller</th>
                        <th class="text-end" style="width:115px;">M. Autorizado</th>
                        <th class="text-end" style="width:115px;">Costo Real</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($workOrder->items as $item)
                    <tr style="{{ !$item->is_approved ? 'opacity:0.5;' : '' }}">
                        <td>
                            <span style="display:inline-block;background:var(--primary-light);color:var(--primary);border-radius:4px;padding:2px 8px;font-size:0.72rem;font-weight:700;">
                                {{ $item->unType->code }}
                            </span>
                        </td>
                        <td class="text-sm">
                            {{ $item->description }}
                            @if($item->is_salvage)
                            <span style="display:inline-block;background:var(--danger-light);color:var(--danger);border-radius:4px;padding:1px 6px;font-size:0.65rem;font-weight:700;margin-left:4px;">SALVAR</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($workOrder->status != 'invoiced')
                            <input type="checkbox" class="form-check-input toggle-approval"
                                data-url="{{ route('work-orders.toggle-approval', [$workOrder, $item]) }}"
                                {{ $item->is_approved ? 'checked' : '' }}>
                            @else
                                @if($item->is_approved) <i class="bi bi-check-circle-fill" style="color:var(--success);"></i>
                                @else <i class="bi bi-dash" style="color:var(--text-muted);"></i> @endif
                            @endif
                        </td>
                        <td class="text-end text-sm" style="font-weight:600;font-variant-numeric:tabular-nums;">${{ number_format($item->price_workshop, 0, ',', '.') }}</td>
                        <td class="text-end text-sm" style="font-weight:600;font-variant-numeric:tabular-nums;color:var(--primary);">${{ number_format($item->price_authorized, 0, ',', '.') }}</td>
                        <td class="text-end text-sm" style="font-weight:600;font-variant-numeric:tabular-nums;">${{ number_format($item->price_real, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Totals --}}
        <div class="p-4" style="border-top:1px solid var(--border-light);">
            @if($workOrder->notes)
            <div class="mb-3 p-3" style="background:var(--bg-main);border-radius:var(--radius);font-size:0.84rem;">
                <strong style="color:var(--text-muted);font-size:0.72rem;text-transform:uppercase;">Observaciones</strong>
                <p class="mb-0 mt-1" style="color:var(--text-secondary);">{{ $workOrder->notes }}</p>
            </div>
            @endif

            <div class="triple-totals">
                <div class="triple-total-card">
                    <div class="label">Neto Taller</div>
                    <div class="value" style="color:var(--text-primary);">${{ number_format($workOrder->total_workshop, 0, ',', '.') }}</div>
                </div>
                <div class="triple-total-card" style="background:var(--primary-light);">
                    <div class="label" style="color:var(--primary);">Neto Autorizado</div>
                    <div class="value" style="color:var(--primary);" id="totalAuthorized">${{ number_format($workOrder->total_authorized, 0, ',', '.') }}</div>
                </div>
                <div class="triple-total-card">
                    <div class="label">Costo Real</div>
                    <div class="value" style="color:var(--text-primary);">${{ number_format($workOrder->total_real_cost, 0, ',', '.') }}</div>
                </div>
            </div>

            @php
                $profit = $workOrder->total_authorized - $workOrder->total_real_cost;
                $margin = $workOrder->total_authorized > 0 ? round(($profit / $workOrder->total_authorized) * 100, 1) : 0;
            @endphp
            @if($workOrder->total_real_cost > 0)
            <div class="d-flex justify-content-end mb-3 gap-3" style="font-size:0.82rem;">
                <span class="text-muted">Rentabilidad:</span>
                <strong style="color:{{ $profit >= 0 ? 'var(--success)' : 'var(--danger)' }};">
                    ${{ number_format($profit, 0, ',', '.') }} ({{ $margin }}%)
                </strong>
            </div>
            @endif

            <div class="total-final-bar">
                <div>
                    <div class="tf-label">Total a Cobrar</div>
                    <div class="tf-iva">IVA 19%: $<span id="taxAmount">{{ number_format($workOrder->tax_amount, 0, ',', '.') }}</span></div>
                </div>
                <div class="tf-amount" id="totalAmount">${{ number_format($workOrder->total_amount, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    {{-- ═══ REPUESTOS ═══ --}}
    @php $partsItems = $workOrder->items->filter(fn($it) => $it->unType && $it->unType->category == 'parts'); @endphp
    @if($partsItems->count())
    <div class="card mb-4">
        <div class="p-4" style="border-bottom:1px solid var(--border-light);">
            <h5 class="fw-bold mb-0 ls-tight"><i class="bi bi-box-seam me-2" style="color:var(--accent);"></i>Repuestos y Pedidos</h5>
        </div>
        <div class="p-4">
            @foreach($partsItems as $partItem)
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <span style="display:inline-block;background:var(--accent-light);color:var(--accent);border-radius:4px;padding:2px 8px;font-size:0.72rem;font-weight:700;">{{ $partItem->unType->code }}</span>
                        <span class="fw-600 text-sm ms-2" style="font-weight:600;">{{ $partItem->description }}</span>
                    </div>
                    @if($workOrder->status != 'invoiced')
                    <button type="button" class="btn-primary-premium" style="padding:0.4rem 0.8rem;font-size:0.78rem;"
                        data-bs-toggle="modal" data-bs-target="#modalPartOrder"
                        onclick="document.getElementById('partOrderItemId').value='{{ $partItem->id }}'">
                        <i class="bi bi-plus-lg"></i> Registrar Pedido
                    </button>
                    @endif
                </div>
                @if($partItem->partOrders && $partItem->partOrders->count())
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead><tr><th>Proveedor</th><th>N Pieza</th><th class="text-end">Costo</th><th class="text-center">Estado</th><th>Pedido</th><th>Recepcion</th><th></th></tr></thead>
                        <tbody>
                        @foreach($partItem->partOrders as $po)
                        <tr>
                            <td class="text-sm">{{ $po->supplier ?? '—' }}</td>
                            <td class="text-sm" style="font-family:monospace;">{{ $po->part_number ?? '—' }}</td>
                            <td class="text-end text-sm fw-600" style="font-variant-numeric:tabular-nums;">${{ number_format($po->cost, 0, ',', '.') }}</td>
                            <td class="text-center">
                                <span class="status-badge status-{{ $po->received_at ? 'approved' : ($po->ordered_at ? 'intake' : 'budget_sent') }}">{{ $po->status_label }}</span>
                            </td>
                            <td class="text-sm">{{ $po->ordered_at?->format('d/m/Y') ?? '—' }}</td>
                            <td class="text-sm">{{ $po->received_at?->format('d/m/Y') ?? '—' }}</td>
                            <td class="text-end">
                                @if(!$po->received_at && $po->ordered_at)
                                <form action="{{ route('part-orders.received', $po) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-success rounded-pill" title="Marcar recibido"
                                        data-confirm="¿Marcar repuesto como recibido?">
                                        <i class="bi bi-check-lg"></i> Recibido
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-sm mb-0" style="color:var(--text-muted);">Sin pedidos registrados.</p>
                @endif
            </div>
            @if(!$loop->last)<hr style="border-color:var(--border-light);">@endif
            @endforeach
        </div>
    </div>
    @endif

    {{-- ═══ TIMELINE ═══ --}}
    @if($workOrder->events->count())
    {{-- Historial del Vehículo --}}
    @if($vehicleHistory->count())
    <div class="card mb-4">
        <div class="p-4" style="border-bottom:1px solid var(--border-light);">
            <h5 class="fw-bold mb-0 ls-tight">
                <i class="bi bi-car-front me-2" style="color:var(--accent);"></i>
                Historial del Vehículo
                <span class="badge bg-light text-dark border ms-2" style="font-size:0.7rem;">{{ $vehicleHistory->count() }} OT{{ $vehicleHistory->count() > 1 ? 's' : '' }} anterior{{ $vehicleHistory->count() > 1 ? 'es' : '' }}</span>
            </h5>
        </div>
        <div class="p-4">
            <div class="table-responsive">
                <table class="table table-sm mb-0" style="font-size:0.82rem;">
                    <thead>
                        <tr>
                            <th>Folio</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th>Aseguradora</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($vehicleHistory as $prevOT)
                        <tr>
                            <td>
                                <a href="{{ route('work-orders.show', $prevOT) }}" class="fw-bold" style="color:var(--primary);">
                                    {{ $prevOT->folio ? '#'.$prevOT->folio : '—' }}
                                </a>
                            </td>
                            <td class="text-muted">{{ \Carbon\Carbon::parse($prevOT->date)->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge bg-{{ $prevOT->status === 'invoiced' ? 'success' : 'secondary' }}" style="font-size:0.68rem;">
                                    {{ $prevOT->status_label }}
                                </span>
                                @if($prevOT->invoice_number)
                                    <span class="text-muted" style="font-size:0.7rem;">F° {{ $prevOT->invoice_number }}</span>
                                @endif
                            </td>
                            <td class="text-muted">{{ $prevOT->insuranceCompany->name ?? 'Particular' }}</td>
                            <td class="text-end fw-semibold">${{ number_format($prevOT->total_amount, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <div class="card mb-4">
        <div class="p-4" style="border-bottom:1px solid var(--border-light);">
            <h5 class="fw-bold mb-0 ls-tight"><i class="bi bi-clock-history me-2" style="color:var(--primary);"></i>Historial</h5>
        </div>
        <div class="p-4">
            @foreach($workOrder->events->sortByDesc('occurred_at') as $event)
            <div class="timeline-item mb-3">
                <div class="timeline-dot" style="background:var(--primary-light);color:var(--primary);">
                    <i class="bi {{ $event->event_type_icon }}"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="fw-600 text-sm" style="font-weight:600;">{{ $event->event_type_label }}</span>
                            <span class="text-xs ms-2" style="color:var(--text-muted);">{{ $event->user->name ?? 'Sistema' }}</span>
                        </div>
                        <span class="text-xs" style="color:var(--text-muted);white-space:nowrap;">{{ $event->occurred_at->format('d/m/Y H:i') }}</span>
                    </div>
                    @if($event->description)
                    <p class="text-sm mb-0 mt-1" style="color:var(--text-secondary);">{{ $event->description }}</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>

{{-- Modal Pedido Repuesto --}}
<div class="modal fade" id="modalPartOrder" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0" style="border-radius:var(--radius-lg);box-shadow:var(--shadow-lg);">
            <div class="modal-header border-0 pb-0 pt-4 px-4">
                <h6 class="fw-bold"><i class="bi bi-box-seam me-2" style="color:var(--primary);"></i>Registrar Pedido de Repuesto</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('part-orders.store', $workOrder) }}" method="POST">
                @csrf
                <div class="modal-body px-4 py-3">
                    <input type="hidden" name="work_order_item_id" id="partOrderItemId">
                    <div class="mb-3">
                        <label class="form-label">Proveedor <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="supplier" required>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label">N de Pieza</label>
                            <input type="text" class="form-control" name="part_number">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Costo</label>
                            <input type="number" class="form-control" name="cost" min="0" step="1">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripcion <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="description" required>
                    </div>
                    <div>
                        <label class="form-label">Fecha Pedido</label>
                        <input type="date" class="form-control" name="ordered_at" value="{{ date('Y-m-d') }}">
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0 gap-2">
                    <button type="button" class="btn-app-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn-primary-premium"><i class="bi bi-check-lg"></i> Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal: Facturar con N° de factura --}}
<div class="modal fade" id="modalInvoice" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0" style="border-radius:var(--radius-lg);box-shadow:var(--shadow-lg);">
            <form action="{{ route('work-orders.status', $workOrder) }}" method="POST">
                @csrf
                <input type="hidden" name="status" value="invoiced">
                <div class="modal-header border-0 pb-0 pt-4 px-4">
                    <h6 class="fw-bold mb-0 ls-tight">
                        <i class="bi bi-receipt me-2" style="color:var(--primary);"></i>Facturar OT #{{ $workOrder->folio }}
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body px-4 py-3">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">N° de Factura <span class="text-danger">*</span></label>
                        <input type="text" name="invoice_number" class="form-control" required autofocus
                            placeholder="Ej: 001234">
                        <small class="text-muted">Ingrese el número de la factura emitida</small>
                    </div>
                    <div class="p-3 rounded" style="background:var(--primary-light);">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted small">Total a facturar</span>
                            <span class="fw-bold" style="color:var(--primary);">
                                ${{ number_format($workOrder->total_amount, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0 gap-2">
                    <button type="button" class="btn-app-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn-primary-premium">
                        <i class="bi bi-check-lg"></i> Confirmar Factura
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
const CLP = v => '$' + Number(v).toLocaleString('es-CL', {maximumFractionDigits: 0});

// WhatsApp notification after status change
@if(session('whatsapp_url'))
(function() {
    const waUrl = @json(session('whatsapp_url'));
    const toast = document.createElement('div');
    toast.innerHTML = `
        <div style="position:fixed;bottom:20px;right:20px;z-index:99999;background:white;border-radius:12px;
            box-shadow:0 8px 30px rgba(0,0,0,0.15);padding:16px 20px;max-width:340px;
            animation:slideInRight 0.35s ease both;border-left:4px solid #25D366;">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;">
                <i class="bi bi-whatsapp" style="font-size:1.3rem;color:#25D366;"></i>
                <strong style="font-size:0.88rem;">¿Notificar al cliente?</strong>
            </div>
            <p style="font-size:0.78rem;color:#64748b;margin:0 0 10px;">Enviar mensaje de WhatsApp informando el cambio de estado.</p>
            <div style="display:flex;gap:8px;">
                <a href="${waUrl}" target="_blank"
                    style="background:#25D366;color:white;border:none;border-radius:8px;padding:6px 16px;
                    font-size:0.82rem;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:4px;">
                    <i class="bi bi-whatsapp"></i> Enviar WhatsApp
                </a>
                <button onclick="this.closest('div[style*=fixed]').remove()"
                    style="background:#f1f5f9;border:none;border-radius:8px;padding:6px 14px;
                    font-size:0.82rem;color:#64748b;cursor:pointer;">
                    Omitir
                </button>
            </div>
        </div>
    `;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 15000);
})();
@endif

document.querySelectorAll('.toggle-approval').forEach(cb => {
    cb.addEventListener('change', function() {
        const row = this.closest('tr');
        fetch(this.dataset.url, {
            method: 'POST',
            headers: {'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'Content-Type': 'application/json'}
        })
        .then(r => r.json())
        .then(data => {
            row.style.opacity = data.is_approved ? '1' : '0.5';
            document.getElementById('totalAuthorized').textContent = CLP(data.total_authorized);
            document.getElementById('taxAmount').textContent = Number(data.tax_amount).toLocaleString('es-CL', {maximumFractionDigits: 0});
            document.getElementById('totalAmount').textContent = CLP(data.total_amount);
        });
    });
});
</script>
@endsection
