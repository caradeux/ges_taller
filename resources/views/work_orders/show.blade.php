@extends('layouts.app')

@section('title', 'OT ' . ($workOrder->folio ? '#'.$workOrder->folio : '— Sin Folio'))

@section('content')
<div class="animate-in">

    {{-- ─── Page Header ────────────────────────────────────── --}}
    <div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-3">
        <div>
            <a href="{{ route('work-orders.index') }}"
                class="d-inline-flex align-items-center gap-1 text-decoration-none mb-2"
                style="font-size:0.78rem;font-weight:600;color:var(--text-muted);">
                <i class="bi bi-arrow-left"></i> Ordenes de Trabajo
            </a>
            <div class="d-flex align-items-center gap-3">
                <h2 class="page-title mb-0">
                    OT {{ $workOrder->folio ? '#'.$workOrder->folio : '— Sin Folio' }}
                </h2>
                <span class="status-badge status-{{ $workOrder->status }}">
                    {{ $workOrder->status_label }}
                </span>
            </div>
            <p class="page-subtitle mt-1">
                {{ \Carbon\Carbon::parse($workOrder->date)->isoFormat('D [de] MMMM [de] YYYY') }}
            </p>
        </div>

        {{-- Action Buttons --}}
        <div class="d-flex gap-2 flex-wrap align-items-start pt-1">
            {{-- Status transitions --}}
            @if($workOrder->status == 'intake')
                <form action="{{ route('work-orders.status', $workOrder) }}" method="POST">
                    @csrf <input type="hidden" name="status" value="budget_sent">
                    <button type="submit" class="btn-info-app">
                        <i class="bi bi-send"></i> Enviar Presupuesto
                    </button>
                </form>
            @elseif($workOrder->status == 'budget_sent')
                <form action="{{ route('work-orders.status', $workOrder) }}" method="POST">
                    @csrf <input type="hidden" name="status" value="approved">
                    <button type="submit" class="btn-success-app">
                        <i class="bi bi-check-lg"></i> Aprobar
                    </button>
                </form>
            @elseif($workOrder->status == 'approved')
                <form action="{{ route('work-orders.status', $workOrder) }}" method="POST">
                    @csrf <input type="hidden" name="status" value="waiting_parts">
                    <button type="submit" class="btn-warning-app">
                        <i class="bi bi-box-seam"></i> Esperando Repuestos
                    </button>
                </form>
                <form action="{{ route('work-orders.status', $workOrder) }}" method="POST">
                    @csrf <input type="hidden" name="status" value="in_repair">
                    <button type="submit" class="btn-primary-premium">
                        <i class="bi bi-wrench-adjustable"></i> Iniciar Reparacion
                    </button>
                </form>
            @elseif($workOrder->status == 'waiting_parts')
                <form action="{{ route('work-orders.status', $workOrder) }}" method="POST">
                    @csrf <input type="hidden" name="status" value="in_repair">
                    <button type="submit" class="btn-primary-premium">
                        <i class="bi bi-wrench-adjustable"></i> Iniciar Reparacion
                    </button>
                </form>
            @elseif($workOrder->status == 'in_repair')
                <form action="{{ route('work-orders.status', $workOrder) }}" method="POST">
                    @csrf <input type="hidden" name="status" value="completed">
                    <button type="submit" class="btn-success-app">
                        <i class="bi bi-check-circle"></i> Completar
                    </button>
                </form>
            @elseif($workOrder->status == 'completed')
                <form action="{{ route('work-orders.status', $workOrder) }}" method="POST">
                    @csrf <input type="hidden" name="status" value="delivered">
                    <button type="submit" class="btn-accent-app">
                        <i class="bi bi-truck"></i> Entregar
                    </button>
                </form>
            @elseif($workOrder->status == 'delivered')
                <form action="{{ route('work-orders.status', $workOrder) }}" method="POST">
                    @csrf <input type="hidden" name="status" value="invoiced">
                    <button type="submit" class="btn-accent-app">
                        <i class="bi bi-receipt"></i> Facturar
                    </button>
                </form>
            @endif

            @if($workOrder->status != 'invoiced')
                <a href="{{ route('work-orders.edit', $workOrder) }}" class="btn-app-secondary">
                    <i class="bi bi-pencil"></i> Editar
                </a>
            @endif
            @if($workOrder->folio)
            <a href="{{ route('work-orders.pdf', $workOrder) }}" class="btn-accent-app">
                <i class="bi bi-file-earmark-pdf"></i> Descargar PDF
            </a>
            <a href="{{ route('work-orders.invoice-pdf', $workOrder) }}" class="btn-app-secondary">
                <i class="bi bi-file-earmark-text"></i> Descargar Factura
            </a>
            @endif
        </div>
    </div>

    {{-- ─── Tags ──────────────────────────────────────────── --}}
    @if($workOrder->tags && $workOrder->tags->count())
    <div class="mb-4 d-flex flex-wrap gap-2">
        @foreach($workOrder->tags as $tag)
            <span class="status-badge" style="background:{{ $tag->color ?? 'var(--primary-light)' }};color:{{ $tag->text_color ?? 'var(--primary)' }};">
                {{ $tag->name }}
            </span>
        @endforeach
    </div>
    @endif

    {{-- ─── Info Cards ─────────────────────────────────────── --}}
    <div class="row g-3 mb-4">

        {{-- Cliente --}}
        <div class="col-md-4">
            <div class="card p-4 h-100">
                <div class="info-section-label">
                    <i class="bi bi-person-fill" style="color:var(--primary);"></i>
                    Cliente
                </div>
                <div class="info-row">
                    <div class="info-label">Nombre</div>
                    <div class="info-value">
                        <a href="{{ route('clients.show', $workOrder->client) }}"
                            class="text-decoration-none" style="color:var(--primary);">
                            {{ $workOrder->client->name }}
                        </a>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">RUT</div>
                    <div class="info-value">{{ $workOrder->client->rut_dni ?? '—' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Telefono</div>
                    <div class="info-value">{{ $workOrder->client->phone ?? '—' }}</div>
                </div>
                @if($workOrder->client->email)
                <div class="info-row">
                    <div class="info-label">Email</div>
                    <div class="info-value text-sm">{{ $workOrder->client->email }}</div>
                </div>
                @endif
            </div>
        </div>

        {{-- Vehiculo --}}
        <div class="col-md-4">
            <div class="card p-4 h-100">
                <div class="info-section-label">
                    <i class="bi bi-car-front-fill" style="color:var(--primary);"></i>
                    Vehiculo
                </div>
                <div class="mb-3">
                    <span class="plate-badge" style="font-size:1rem;">
                        {{ strtoupper($workOrder->vehicle->license_plate) }}
                    </span>
                </div>
                <div class="row g-2">
                    <div class="col-12">
                        <div class="info-label">Marca / Modelo</div>
                        <div class="info-value fw-700" style="font-weight:700;">
                            {{ $workOrder->vehicle->brand }} {{ $workOrder->vehicle->model }}
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="info-label">Ano</div>
                        <div class="info-value">{{ $workOrder->vehicle->year ?? '—' }}</div>
                    </div>
                    @if($workOrder->vehicle->color)
                    <div class="col-6">
                        <div class="info-label">Color</div>
                        <div class="info-value">{{ $workOrder->vehicle->color }}</div>
                    </div>
                    @endif
                    <div class="col-6">
                        <div class="info-label">Kilometraje</div>
                        <div class="info-value">
                            {{ number_format($workOrder->vehicle->odometer ?? 0, 0, ',', '.') }} km
                        </div>
                    </div>
                    @if($workOrder->vehicle->vin_chassis)
                    <div class="col-12">
                        <div class="info-label">Nro Chasis (VIN)</div>
                        <div class="info-value text-sm" style="font-family:monospace;letter-spacing:1px;">
                            {{ $workOrder->vehicle->vin_chassis }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Seguro --}}
        <div class="col-md-4">
            <div class="card p-4 h-100">
                <div class="info-section-label">
                    <i class="bi bi-shield-check" style="color:var(--info);"></i>
                    Seguro / Siniestro
                </div>
                @if($workOrder->insurance_company_id)
                    <div class="info-row">
                        <div class="info-label">Compania</div>
                        <div class="info-value fw-600" style="font-weight:600;">
                            {{ $workOrder->insuranceCompany->name }}
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Liquidador</div>
                        <div class="info-value">{{ $workOrder->liquidator?->name ?? 'No asignado' }}</div>
                    </div>
                    @if($workOrder->claim_number)
                    <div class="info-row">
                        <div class="info-label">Nro Siniestro</div>
                        <div class="info-value fw-700" style="font-weight:700;">
                            {{ $workOrder->claim_number }}
                        </div>
                    </div>
                    @endif
                    @if($workOrder->intake_number)
                    <div class="info-row">
                        <div class="info-label">Nro de Ingreso</div>
                        <div class="info-value">{{ $workOrder->intake_number }}</div>
                    </div>
                    @endif
                @else
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="status-badge" style="background:var(--border-light);color:var(--text-secondary);">
                            Particular — sin seguro
                        </span>
                    </div>
                    @if($workOrder->claim_number)
                    <div class="info-row">
                        <div class="info-label">Nro Siniestro</div>
                        <div class="info-value">{{ $workOrder->claim_number }}</div>
                    </div>
                    @endif
                @endif
                @if($workOrder->deductible_amount > 0)
                <div class="info-row">
                    <div class="info-label">Deducible</div>
                    <div class="info-value fw-700 text-accent" style="font-weight:700;">
                        ${{ number_format($workOrder->deductible_amount, 0, ',', '.') }}
                    </div>
                </div>
                @endif
            </div>
        </div>

    </div>

    {{-- ─── Items Table ─────────────────────────────────────── --}}
    <div class="card mb-4">

        <div class="d-flex justify-content-between align-items-center p-4"
            style="border-bottom:1px solid var(--border-light);">
            <div>
                <h5 class="fw-bold mb-0 ls-tight">Detalle de Trabajos y Repuestos</h5>
                <p class="text-xs mb-0 mt-1" style="color:var(--text-muted);">
                    {{ $workOrder->items->count() }} item(s)
                </p>
            </div>
            <span class="status-badge status-{{ $workOrder->status }}">{{ $workOrder->status_label }}</span>
        </div>

        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th style="width:130px;">Tipo UN</th>
                        <th>Descripcion</th>
                        <th class="text-center" style="width:80px;">Aprobado</th>
                        <th class="text-end" style="width:120px;">Monto Taller</th>
                        <th class="text-end" style="width:130px;">Monto Autorizado</th>
                        <th class="text-end" style="width:120px;">Costo Real</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($workOrder->items as $item)
                    <tr>
                        <td>
                            <span style="display:inline-block;background:var(--primary-light);color:var(--primary);
                                border-radius:4px;padding:2px 8px;font-size:0.72rem;font-weight:700;">
                                {{ $item->unType->code }}
                            </span>
                            <span class="text-xs ms-1" style="color:var(--text-muted);">
                                {{ $item->unType->name }}
                            </span>
                        </td>
                        <td class="text-sm">{{ $item->description }}</td>
                        <td class="text-center">
                            @if($workOrder->status != 'invoiced')
                                <input type="checkbox" class="form-check-input toggle-approval"
                                    data-url="{{ route('work-orders.toggle-approval', [$workOrder, $item]) }}"
                                    {{ $item->is_approved ? 'checked' : '' }}>
                            @else
                                @if($item->is_approved)
                                    <i class="bi bi-check-circle-fill" style="color:var(--success);"></i>
                                @else
                                    <i class="bi bi-dash" style="color:var(--text-muted);"></i>
                                @endif
                            @endif
                        </td>
                        <td class="text-end fw-600 text-sm" style="font-weight:600;font-variant-numeric:tabular-nums;">
                            ${{ number_format($item->shop_amount ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="text-end fw-600 text-sm" style="font-weight:600;font-variant-numeric:tabular-nums;">
                            ${{ number_format($item->authorized_amount ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="text-end fw-600 text-sm" style="font-weight:600;font-variant-numeric:tabular-nums;">
                            ${{ number_format($item->real_cost ?? 0, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    @php
                        $catLabels = ['repair'=>'Reparacion','paint'=>'Pintura','dm'=>'D/M','parts'=>'Repuesto','other'=>'Otros'];
                        $grouped   = $workOrder->items->groupBy(fn($it) => $it->unType->category);
                    @endphp
                    @foreach($catLabels as $cat => $label)
                        @if($grouped->has($cat))
                        <tr>
                            <td colspan="2" class="text-end text-xs fw-600" style="color:var(--text-muted);font-weight:600;background:#f8f9fb;">
                                Subtotal {{ $label }}
                            </td>
                            <td style="background:#f8f9fb;"></td>
                            <td class="text-end text-sm fw-600" style="font-weight:600;background:#f8f9fb;font-variant-numeric:tabular-nums;">
                                ${{ number_format($grouped[$cat]->sum('shop_amount'), 0, ',', '.') }}
                            </td>
                            <td class="text-end text-sm fw-600" style="font-weight:600;background:#f8f9fb;font-variant-numeric:tabular-nums;">
                                ${{ number_format($grouped[$cat]->sum('authorized_amount'), 0, ',', '.') }}
                            </td>
                            <td class="text-end text-sm fw-600" style="font-weight:600;background:#f8f9fb;font-variant-numeric:tabular-nums;">
                                ${{ number_format($grouped[$cat]->sum('real_cost'), 0, ',', '.') }}
                            </td>
                        </tr>
                        @endif
                    @endforeach
                </tfoot>
            </table>
        </div>

        {{-- Totals + Notes --}}
        <div class="p-4" style="border-top:1px solid var(--border-light);">
            <div class="row align-items-start g-4">

                {{-- Notes --}}
                <div class="col-md-5">
                    @if($workOrder->notes)
                    <div class="info-label mb-1">Observaciones</div>
                    <p class="text-sm mb-0" style="color:var(--text-secondary);line-height:1.6;">
                        {{ $workOrder->notes }}
                    </p>
                    @endif
                </div>

                {{-- Totals --}}
                <div class="col-md-7 d-flex justify-content-end">
                    <div class="totals-panel" style="min-width:420px;">
                        <div class="totals-row">
                            <span>Neto Taller</span>
                            <span>${{ number_format($workOrder->total_shop ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="totals-row">
                            <span>Neto Autorizado</span>
                            <span id="totalAuthorized">${{ number_format($workOrder->total_authorized ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="totals-row">
                            <span>Neto Costo Real</span>
                            <span>${{ number_format($workOrder->total_real_cost ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="totals-row">
                            <span>IVA (19%)</span>
                            <span id="taxAmount">${{ number_format($workOrder->tax_amount ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="totals-grand">
                            <span class="fw-700 outfit" style="font-size:0.95rem;">Total</span>
                            <span class="fw-800 outfit ls-tight" id="totalAmount"
                                style="font-size:1.5rem;color:var(--primary);">
                                ${{ number_format($workOrder->total_amount ?? 0, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

    {{-- ─── Seccion Repuestos ──────────────────────────────── --}}
    @php
        $partsItems = $workOrder->items->filter(fn($it) => $it->unType->category == 'parts');
    @endphp
    @if($partsItems->count())
    <div class="card mb-4">
        <div class="p-4" style="border-bottom:1px solid var(--border-light);">
            <h5 class="fw-bold mb-0 ls-tight">
                <i class="bi bi-box-seam me-2" style="color:var(--primary);"></i>
                Repuestos y Pedidos
            </h5>
        </div>
        <div class="p-4">
            @foreach($partsItems as $partItem)
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <span style="display:inline-block;background:var(--primary-light);color:var(--primary);
                            border-radius:4px;padding:2px 8px;font-size:0.72rem;font-weight:700;">
                            {{ $partItem->unType->code }}
                        </span>
                        <span class="fw-600 text-sm ms-2" style="font-weight:600;">{{ $partItem->description }}</span>
                    </div>
                    @if($workOrder->status != 'invoiced')
                    <button type="button" class="btn-primary-premium btn-sm"
                        data-bs-toggle="modal" data-bs-target="#modalPartOrder"
                        onclick="document.getElementById('partOrderItemId').value='{{ $partItem->id }}'">
                        <i class="bi bi-plus-lg"></i> Registrar Pedido
                    </button>
                    @endif
                </div>

                @if($partItem->partOrders && $partItem->partOrders->count())
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Proveedor</th>
                                <th>Nro Pieza</th>
                                <th>Descripcion</th>
                                <th class="text-end">Costo</th>
                                <th class="text-center">Estado</th>
                                <th>Fecha Pedido</th>
                                <th>Fecha Recepcion</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($partItem->partOrders as $po)
                            <tr>
                                <td class="text-sm">{{ $po->supplier ?? '—' }}</td>
                                <td class="text-sm" style="font-family:monospace;">{{ $po->part_number ?? '—' }}</td>
                                <td class="text-sm">{{ $po->description ?? '—' }}</td>
                                <td class="text-end text-sm fw-600" style="font-weight:600;font-variant-numeric:tabular-nums;">
                                    ${{ number_format($po->cost ?? 0, 0, ',', '.') }}
                                </td>
                                <td class="text-center">
                                    @if($po->status == 'received')
                                        <span class="status-badge" style="background:var(--success-light, #e6f9ee);color:var(--success);">Recibido</span>
                                    @elseif($po->status == 'ordered')
                                        <span class="status-badge" style="background:var(--warning-light, #fff8e6);color:var(--warning, #d4a017);">Pedido</span>
                                    @else
                                        <span class="status-badge" style="background:var(--border-light);color:var(--text-muted);">Pendiente</span>
                                    @endif
                                </td>
                                <td class="text-sm">{{ $po->ordered_at ? \Carbon\Carbon::parse($po->ordered_at)->format('d/m/Y') : '—' }}</td>
                                <td class="text-sm">{{ $po->received_at ? \Carbon\Carbon::parse($po->received_at)->format('d/m/Y') : '—' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-sm mb-0" style="color:var(--text-muted);">Sin pedidos registrados.</p>
                @endif
            </div>
            @if(!$loop->last)
                <hr style="border-color:var(--border-light);">
            @endif
            @endforeach
        </div>
    </div>
    @endif

    {{-- ─── Timeline / Historial ───────────────────────────── --}}
    @if($workOrder->events && $workOrder->events->count())
    <div class="card mb-4">
        <div class="p-4" style="border-bottom:1px solid var(--border-light);">
            <h5 class="fw-bold mb-0 ls-tight">
                <i class="bi bi-clock-history me-2" style="color:var(--primary);"></i>
                Historial de Eventos
            </h5>
        </div>
        <div class="p-4">
            <div class="timeline">
                @foreach($workOrder->events->sortByDesc('occurred_at') as $event)
                <div class="d-flex gap-3 mb-4">
                    <div class="d-flex flex-column align-items-center" style="min-width:32px;">
                        <div class="d-flex align-items-center justify-content-center rounded-circle"
                            style="width:32px;height:32px;background:var(--primary-light);color:var(--primary);font-size:0.85rem;">
                            <i class="bi {{ $event->event_type_icon }}"></i>
                        </div>
                        @if(!$loop->last)
                        <div style="width:2px;flex:1;background:var(--border-light);margin-top:4px;"></div>
                        @endif
                    </div>
                    <div class="flex-grow-1 pb-2">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <span class="fw-600 text-sm" style="font-weight:600;">{{ $event->event_type_label }}</span>
                                <span class="text-xs ms-2" style="color:var(--text-muted);">
                                    {{ $event->user->name ?? 'Sistema' }}
                                </span>
                            </div>
                            <span class="text-xs" style="color:var(--text-muted);white-space:nowrap;">
                                {{ \Carbon\Carbon::parse($event->occurred_at)->format('d/m/Y H:i') }}
                            </span>
                        </div>
                        @if($event->description)
                        <p class="text-sm mb-0 mt-1" style="color:var(--text-secondary);line-height:1.5;">
                            {{ $event->description }}
                        </p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

</div>

{{-- ─── Modal Pedido Repuesto ──────────────────────────── --}}
<div class="modal fade" id="modalPartOrder" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0" style="border-radius:var(--radius-lg);">
            <div class="modal-header border-0">
                <h6 class="fw-bold">Registrar Pedido de Repuesto</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('part-orders.store', $workOrder) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="work_order_item_id" id="partOrderItemId">

                    <div class="mb-3">
                        <label for="poSupplier" class="form-label fw-600" style="font-weight:600;">Proveedor</label>
                        <input type="text" class="form-control" id="poSupplier" name="supplier" required>
                    </div>

                    <div class="mb-3">
                        <label for="poPartNumber" class="form-label fw-600" style="font-weight:600;">Nro de Pieza</label>
                        <input type="text" class="form-control" id="poPartNumber" name="part_number">
                    </div>

                    <div class="mb-3">
                        <label for="poDescription" class="form-label fw-600" style="font-weight:600;">Descripcion</label>
                        <input type="text" class="form-control" id="poDescription" name="description">
                    </div>

                    <div class="row g-3">
                        <div class="col-6">
                            <label for="poCost" class="form-label fw-600" style="font-weight:600;">Costo</label>
                            <input type="number" class="form-control" id="poCost" name="cost" min="0" step="1">
                        </div>
                        <div class="col-6">
                            <label for="poOrderedAt" class="form-label fw-600" style="font-weight:600;">Fecha Pedido</label>
                            <input type="date" class="form-control" id="poOrderedAt" name="ordered_at" value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn-app-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn-primary-premium">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Toggle aprobacion AJAX
    const CSRF = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    function CLP(value) {
        return '$' + Number(value).toLocaleString('es-CL', {maximumFractionDigits: 0});
    }

    document.querySelectorAll('.toggle-approval').forEach(cb => {
        cb.addEventListener('change', function() {
            fetch(this.dataset.url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(r => r.json())
            .then(data => {
                document.getElementById('totalAuthorized').textContent = CLP(data.total_authorized);
                document.getElementById('taxAmount').textContent = CLP(data.tax_amount);
                document.getElementById('totalAmount').textContent = CLP(data.total_amount);
            });
        });
    });
</script>
@endsection
