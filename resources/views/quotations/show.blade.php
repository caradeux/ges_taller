@extends('layouts.app')

@section('title', 'Cotización ' . ($quotation->folio ? '#'.$quotation->folio : '— Borrador'))

@section('content')
<div class="animate-in">

    {{-- ─── Page Header ────────────────────────────────────── --}}
    <div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-3">
        <div>
            <a href="{{ route('quotations.index') }}"
                class="d-inline-flex align-items-center gap-1 text-decoration-none mb-2"
                style="font-size:0.78rem;font-weight:600;color:var(--text-muted);">
                <i class="bi bi-arrow-left"></i> Cotizaciones
            </a>
            <div class="d-flex align-items-center gap-3">
                <h2 class="page-title mb-0">
                    Cotización {{ $quotation->folio ? '#'.$quotation->folio : '— Borrador' }}
                </h2>
                <span class="status-badge status-{{ $quotation->status }}">
                    {{ $quotation->status_label }}
                </span>
            </div>
            <p class="page-subtitle mt-1">
                {{ \Carbon\Carbon::parse($quotation->date)->isoFormat('D [de] MMMM [de] YYYY') }}
            </p>
        </div>

        {{-- Action Buttons --}}
        <div class="d-flex gap-2 flex-wrap align-items-start pt-1">
            {{-- Status transitions --}}
            @if($quotation->status == 'draft')
                <form action="{{ route('quotations.status', $quotation) }}" method="POST">
                    @csrf <input type="hidden" name="status" value="sent">
                    <button type="submit" class="btn-info-app">
                        <i class="bi bi-send"></i> Marcar Enviada
                    </button>
                </form>
            @elseif($quotation->status == 'sent')
                <form action="{{ route('quotations.status', $quotation) }}" method="POST">
                    @csrf <input type="hidden" name="status" value="approved">
                    <button type="submit" class="btn-success-app">
                        <i class="bi bi-check-lg"></i> Aprobar
                    </button>
                </form>
                <form action="{{ route('quotations.status', $quotation) }}" method="POST">
                    @csrf <input type="hidden" name="status" value="rejected">
                    <button type="submit" class="btn-danger-app">
                        <i class="bi bi-x-lg"></i> Rechazar
                    </button>
                </form>
            @elseif($quotation->status == 'approved')
                <form action="{{ route('quotations.status', $quotation) }}" method="POST">
                    @csrf <input type="hidden" name="status" value="finished">
                    <button type="submit" class="btn-primary-premium">
                        <i class="bi bi-flag-fill"></i> Terminar Reparación
                    </button>
                </form>
            @elseif($quotation->status == 'finished')
                <form action="{{ route('quotations.status', $quotation) }}" method="POST">
                    @csrf <input type="hidden" name="status" value="invoiced">
                    <button type="submit" class="btn-accent-app">
                        <i class="bi bi-receipt"></i> Facturar
                    </button>
                </form>
            @endif

            @if(!in_array($quotation->status, ['invoiced', 'rejected']))
                <a href="{{ route('quotations.edit', $quotation) }}" class="btn-app-secondary">
                    <i class="bi bi-pencil"></i> Editar
                </a>
            @endif
            @if($quotation->folio)
            <a href="{{ route('quotations.pdf', $quotation) }}" class="btn-accent-app">
                <i class="bi bi-file-earmark-pdf"></i> Descargar PDF
            </a>
            @endif
        </div>
    </div>

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
                        <a href="{{ route('clients.show', $quotation->client) }}"
                            class="text-decoration-none" style="color:var(--primary);">
                            {{ $quotation->client->name }}
                        </a>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">RUT</div>
                    <div class="info-value">{{ $quotation->client->rut_dni ?? '—' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Teléfono</div>
                    <div class="info-value">{{ $quotation->client->phone ?? '—' }}</div>
                </div>
                @if($quotation->client->email)
                <div class="info-row">
                    <div class="info-label">Email</div>
                    <div class="info-value text-sm">{{ $quotation->client->email }}</div>
                </div>
                @endif
            </div>
        </div>

        {{-- Vehículo --}}
        <div class="col-md-4">
            <div class="card p-4 h-100">
                <div class="info-section-label">
                    <i class="bi bi-car-front-fill" style="color:var(--primary);"></i>
                    Vehículo
                </div>
                <div class="mb-3">
                    <span class="plate-badge" style="font-size:1rem;">
                        {{ strtoupper($quotation->vehicle->license_plate) }}
                    </span>
                </div>
                <div class="row g-2">
                    <div class="col-12">
                        <div class="info-label">Marca / Modelo</div>
                        <div class="info-value fw-700" style="font-weight:700;">
                            {{ $quotation->vehicle->brand }} {{ $quotation->vehicle->model }}
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="info-label">Año</div>
                        <div class="info-value">{{ $quotation->vehicle->year ?? '—' }}</div>
                    </div>
                    @if($quotation->vehicle->color)
                    <div class="col-6">
                        <div class="info-label">Color</div>
                        <div class="info-value">{{ $quotation->vehicle->color }}</div>
                    </div>
                    @endif
                    <div class="col-6">
                        <div class="info-label">Kilometraje</div>
                        <div class="info-value">
                            {{ number_format($quotation->vehicle->odometer ?? 0, 0, ',', '.') }} km
                        </div>
                    </div>
                    @if($quotation->vehicle->vin_chassis)
                    <div class="col-12">
                        <div class="info-label">Nº Chasis (VIN)</div>
                        <div class="info-value text-sm" style="font-family:monospace;letter-spacing:1px;">
                            {{ $quotation->vehicle->vin_chassis }}
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
                @if($quotation->insurance_company_id)
                    <div class="info-row">
                        <div class="info-label">Compañía</div>
                        <div class="info-value fw-600" style="font-weight:600;">
                            {{ $quotation->insuranceCompany->name }}
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Liquidador</div>
                        <div class="info-value">{{ $quotation->liquidator?->name ?? 'No asignado' }}</div>
                    </div>
                    @if($quotation->claim_number)
                    <div class="info-row">
                        <div class="info-label">Nº Siniestro</div>
                        <div class="info-value fw-700" style="font-weight:700;">
                            {{ $quotation->claim_number }}
                        </div>
                    </div>
                    @endif
                    @if($quotation->intake_number)
                    <div class="info-row">
                        <div class="info-label">Nº de Ingreso</div>
                        <div class="info-value">{{ $quotation->intake_number }}</div>
                    </div>
                    @endif
                @else
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="status-badge" style="background:var(--border-light);color:var(--text-secondary);">
                            Particular — sin seguro
                        </span>
                    </div>
                    @if($quotation->claim_number)
                    <div class="info-row">
                        <div class="info-label">Nº Siniestro</div>
                        <div class="info-value">{{ $quotation->claim_number }}</div>
                    </div>
                    @endif
                @endif
                @if($quotation->deductible_amount > 0)
                <div class="info-row">
                    <div class="info-label">Deducible</div>
                    <div class="info-value fw-700 text-accent" style="font-weight:700;">
                        ${{ number_format($quotation->deductible_amount, 0, ',', '.') }}
                    </div>
                </div>
                @endif
            </div>
        </div>

    </div>

    {{-- ─── Items Table ─────────────────────────────────────── --}}
    <div class="card">

        <div class="d-flex justify-content-between align-items-center p-4"
            style="border-bottom:1px solid var(--border-light);">
            <div>
                <h5 class="fw-bold mb-0 ls-tight">Detalle de Trabajos y Repuestos</h5>
                <p class="text-xs mb-0 mt-1" style="color:var(--text-muted);">
                    {{ $quotation->items->count() }} ítem(s)
                </p>
            </div>
            <span class="status-badge status-{{ $quotation->status }}">{{ $quotation->status_label }}</span>
        </div>

        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th style="width:140px;">Tipo UN</th>
                        <th>Descripción</th>
                        <th class="text-center" style="width:80px;">Salvar</th>
                        <th class="text-end" style="width:130px;">Precio</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($quotation->items as $item)
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
                            @if($item->is_salvage)
                                <span style="display:inline-block;background:var(--danger-light);color:var(--danger);
                                    border-radius:4px;padding:2px 8px;font-size:0.68rem;font-weight:700;">
                                    SALVAR
                                </span>
                            @endif
                        </td>
                        <td class="text-end fw-600 text-sm" style="font-weight:600;font-variant-numeric:tabular-nums;">
                            ${{ number_format($item->price, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    @php
                        $catLabels = ['repair'=>'Reparación','paint'=>'Pintura','dm'=>'D/M','parts'=>'Repuesto','other'=>'Otros'];
                        $grouped   = $quotation->items->groupBy(fn($it) => $it->unType->category);
                    @endphp
                    @foreach($catLabels as $cat => $label)
                        @if($grouped->has($cat))
                        <tr>
                            <td colspan="2" class="text-end text-xs fw-600" style="color:var(--text-muted);font-weight:600;background:#f8f9fb;">
                                Subtotal {{ $label }}
                            </td>
                            <td style="background:#f8f9fb;"></td>
                            <td class="text-end text-sm fw-600" style="font-weight:600;background:#f8f9fb;font-variant-numeric:tabular-nums;">
                                ${{ number_format($grouped[$cat]->sum('price'), 0, ',', '.') }}
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
                <div class="col-md-6">
                    @if($quotation->notes)
                    <div class="info-label mb-1">Observaciones</div>
                    <p class="text-sm mb-0" style="color:var(--text-secondary);line-height:1.6;">
                        {{ $quotation->notes }}
                    </p>
                    @endif
                </div>

                {{-- Totals --}}
                <div class="col-md-6 d-flex justify-content-end">
                    <div class="totals-panel" style="min-width:270px;">
                        <div class="totals-row">
                            <span>Neto</span>
                            <span>${{ number_format($quotation->total_amount / 1.19, 0, ',', '.') }}</span>
                        </div>
                        <div class="totals-row">
                            <span>IVA (19%)</span>
                            <span>${{ number_format($quotation->tax_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="totals-grand">
                            <span class="fw-700 outfit" style="font-size:0.95rem;">Total</span>
                            <span class="fw-800 outfit ls-tight"
                                style="font-size:1.5rem;color:var(--primary);">
                                ${{ number_format($quotation->total_amount, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

</div>
@endsection
