<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #000; margin: 12px; }

        /* ─── Header ─── */
        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 0; }
        .header-table td { vertical-align: top; padding: 4px; }
        .company-name { font-size: 16px; font-weight: bold; color: #1a3c6e; }
        .company-info { font-size: 9px; color: #444; line-height: 1.5; }
        .ot-number { font-size: 22px; font-weight: bold; color: #1a3c6e; text-align: right; }
        .ot-label { font-size: 10px; color: #666; text-align: right; }

        /* ─── Title Bar ─── */
        .title-bar {
            background: #1a3c6e; color: white; text-align: center;
            font-size: 14px; font-weight: bold; padding: 6px 0;
            letter-spacing: 2px; margin: 6px 0;
        }

        /* ─── Data Tables ─── */
        .data-table { width: 100%; border-collapse: collapse; margin-bottom: 6px; }
        .data-table td { border: 1px solid #999; padding: 3px 6px; font-size: 9.5px; vertical-align: middle; }
        .data-table .label { background: #e8ecf1; font-weight: bold; color: #333; width: 120px; font-size: 9px; text-transform: uppercase; }
        .data-table .value { color: #000; min-width: 100px; }
        .data-table .section-header { background: #1a3c6e; color: white; font-weight: bold; text-align: center; font-size: 9px; text-transform: uppercase; letter-spacing: 1px; }

        /* ─── Dates bar ─── */
        .dates-bar { width: 100%; border-collapse: collapse; margin-bottom: 6px; }
        .dates-bar td { border: 1px solid #999; padding: 4px 8px; font-size: 10px; text-align: center; }
        .dates-bar .dt-label { background: #e8ecf1; font-weight: bold; font-size: 9px; text-transform: uppercase; width: 25%; }
        .dates-bar .dt-value { font-weight: bold; color: #1a3c6e; }

        /* ─── Items Table ─── */
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 6px; }
        .items-table th {
            background: #1a3c6e; color: white; padding: 5px 6px;
            font-size: 8.5px; text-transform: uppercase; letter-spacing: 0.5px;
            text-align: center; border: 1px solid #1a3c6e;
        }
        .items-table td { border: 1px solid #999; padding: 3px 6px; font-size: 9.5px; text-align: center; }
        .items-table td.desc { text-align: left; }
        .items-table .check { font-size: 12px; color: #1a3c6e; }
        .items-table tr:nth-child(even) td { background: #f5f7fa; }

        /* ─── Inventory ─── */
        .inv-table { width: 100%; border-collapse: collapse; margin-bottom: 6px; }
        .inv-table td { border: 1px solid #999; padding: 3px 6px; font-size: 9px; }
        .inv-table .inv-label { font-weight: bold; width: 140px; background: #f0f2f5; }
        .inv-table .inv-val { text-align: center; width: 50px; font-weight: bold; }
        .inv-yes { color: #16a34a; }
        .inv-no { color: #dc2626; }

        /* ─── Totals ─── */
        .totals-table { width: 300px; border-collapse: collapse; margin-left: auto; margin-bottom: 8px; }
        .totals-table td { border: 1px solid #999; padding: 4px 8px; font-size: 10px; }
        .totals-table .tot-label { background: #e8ecf1; font-weight: bold; text-align: right; }
        .totals-table .tot-value { text-align: right; font-weight: bold; font-variant-numeric: tabular-nums; }
        .totals-table .grand { background: #1a3c6e; color: white; font-size: 12px; }

        /* ─── Signatures ─── */
        .sig-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .sig-table td { text-align: center; padding-top: 40px; width: 33%; vertical-align: bottom; }
        .sig-line { border-top: 1px solid #000; display: inline-block; width: 160px; padding-top: 4px; font-size: 9px; font-weight: bold; }

        /* ─── Footer ─── */
        .legal { margin-top: 12px; padding: 6px 8px; background: #f5f7fa; border: 1px solid #ddd; font-size: 8px; color: #555; line-height: 1.4; }

        .section-title { font-size: 9px; font-weight: bold; text-transform: uppercase; color: #1a3c6e; margin: 6px 0 3px; letter-spacing: 0.5px; }
    </style>
</head>
<body>

    {{-- ═══ HEADER ═══ --}}
    <table class="header-table">
        <tr>
            <td style="width:65%;">
                <div class="company-name">{{ $company->name ?? 'Mi Taller' }}</div>
                <div class="company-info">
                    @if($company->rut)RUT: {{ $company->rut }}<br>@endif
                    @if($company->address){{ $company->address }}<br>@endif
                    @if($company->phone || $company->email)
                        @if($company->phone)Contacto: {{ $company->phone }}@endif
                        @if($company->phone && $company->email) / @endif
                        @if($company->email){{ $company->email }}@endif
                    @endif
                </div>
            </td>
            <td style="width:35%;">
                <div class="ot-number">OT N&deg; {{ $workOrder->folio ?? '—' }}</div>
            </td>
        </tr>
    </table>

    <div class="title-bar">ORDEN DE TRABAJO</div>

    {{-- ═══ FECHAS ═══ --}}
    <table class="dates-bar">
        <tr>
            <td class="dt-label">Ingreso</td>
            <td class="dt-value">{{ \Carbon\Carbon::parse($workOrder->date)->format('d-m-Y') }}</td>
            <td class="dt-label">Inicio Rep.</td>
            <td class="dt-value">{{ $workOrder->repair_start_date ? \Carbon\Carbon::parse($workOrder->repair_start_date)->format('d-m-Y') : '' }}</td>
            <td class="dt-label">Salida</td>
            <td class="dt-value">{{ $workOrder->exit_date ? \Carbon\Carbon::parse($workOrder->exit_date)->format('d-m-Y') : '' }}</td>
        </tr>
    </table>

    {{-- ═══ DATOS CLIENTE + SEGURO ═══ --}}
    <table class="data-table">
        <tr>
            <td class="label">Nombre Cliente</td>
            <td class="value" colspan="3">{{ $workOrder->client->name ?? '' }}</td>
            <td class="label">Telefono</td>
            <td class="value">{{ $workOrder->client->phone ?? '' }}</td>
        </tr>
        <tr>
            <td class="label">RUT</td>
            <td class="value">{{ $workOrder->client->rut_dni ?? '' }}</td>
            <td class="label">Email</td>
            <td class="value" colspan="3">{{ $workOrder->client->email ?? '' }}</td>
        </tr>
        <tr>
            <td class="label">Direccion</td>
            <td class="value" colspan="5">{{ $workOrder->client->address ?? '' }}</td>
        </tr>
        <tr>
            <td class="label">Compania</td>
            <td class="value">{{ $workOrder->insuranceCompany->name ?? 'Particular' }}</td>
            <td class="label">Liquidador</td>
            <td class="value">{{ $workOrder->liquidator->name ?? '' }}</td>
            <td class="label">N&deg; Siniestro</td>
            <td class="value">{{ $workOrder->claim_number ?? '' }}</td>
        </tr>
    </table>

    {{-- ═══ DATOS VEHICULO ═══ --}}
    <table class="data-table">
        <tr>
            <td class="section-header" colspan="8">VEHICULO</td>
        </tr>
        <tr>
            <td class="label">Marca</td>
            <td class="value">{{ $workOrder->vehicle->brand ?? '' }}</td>
            <td class="label">Modelo</td>
            <td class="value">{{ $workOrder->vehicle->model ?? '' }}</td>
            <td class="label">Ano</td>
            <td class="value">{{ $workOrder->vehicle->year ?? '' }}</td>
            <td class="label">Color</td>
            <td class="value">{{ $workOrder->vehicle->color ?? '' }}</td>
        </tr>
        <tr>
            <td class="label">Patente</td>
            <td class="value" style="font-size:12px;font-weight:bold;color:#1a3c6e;">{{ strtoupper($workOrder->vehicle->license_plate ?? '') }}</td>
            <td class="label">N&deg; Chasis</td>
            <td class="value" colspan="3" style="font-family:monospace;font-size:9px;">{{ $workOrder->vehicle->vin_chassis ?? '' }}</td>
            <td class="label">KM</td>
            <td class="value">{{ number_format($workOrder->vehicle_inventory['km_ingreso'] ?? $workOrder->vehicle->odometer ?? 0, 0, ',', '.') }}</td>
        </tr>
    </table>

    {{-- ═══ ITEMS DE TRABAJO ═══ --}}
    @php
        $approvedItems = $workOrder->items->where('is_approved', true);
        $catLabels = ['repair'=>'Reparacion','paint'=>'Pintura','dm'=>'D/M','parts'=>'Repuesto','other'=>'Otros'];
    @endphp

    <table class="items-table">
        <thead>
            <tr>
                <th style="width:70px;">Unidad</th>
                <th>Descripcion</th>
                <th style="width:75px;">M. Taller</th>
                <th style="width:75px;">M. Autorizado</th>
                <th style="width:75px;">Costo Real</th>
            </tr>
        </thead>
        <tbody>
            @foreach($approvedItems as $item)
            <tr>
                <td>
                    <strong>{{ $item->unType->code ?? '' }}</strong>
                </td>
                <td class="desc">{{ $item->description }}</td>
                <td>${{ number_format($item->price_workshop, 0, ',', '.') }}</td>
                <td>${{ number_format($item->price_authorized, 0, ',', '.') }}</td>
                <td>${{ number_format($item->price_real, 0, ',', '.') }}</td>
            </tr>
            @endforeach

            {{-- Subtotals by category --}}
            @php $grouped = $approvedItems->groupBy(fn($it) => $it->unType->category ?? 'other'); @endphp
            @foreach($catLabels as $cat => $label)
                @if($grouped->has($cat))
                <tr>
                    <td colspan="2" style="text-align:right;background:#e8ecf1;font-weight:bold;font-size:8.5px;">
                        Subtotal {{ $label }}
                    </td>
                    <td style="background:#e8ecf1;font-weight:bold;">${{ number_format($grouped[$cat]->sum('price_workshop'), 0, ',', '.') }}</td>
                    <td style="background:#e8ecf1;font-weight:bold;">${{ number_format($grouped[$cat]->sum('price_authorized'), 0, ',', '.') }}</td>
                    <td style="background:#e8ecf1;font-weight:bold;">${{ number_format($grouped[$cat]->sum('price_real'), 0, ',', '.') }}</td>
                </tr>
                @endif
            @endforeach
        </tbody>
    </table>

    {{-- ═══ TOTALES ═══ --}}
    <table class="totals-table">
        <tr>
            <td class="tot-label">Neto Autorizado</td>
            <td class="tot-value">${{ number_format($workOrder->total_authorized, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="tot-label">IVA 19%</td>
            <td class="tot-value">${{ number_format($workOrder->tax_amount, 0, ',', '.') }}</td>
        </tr>
        @if($workOrder->deductible_amount > 0)
        <tr>
            <td class="tot-label">Deducible</td>
            <td class="tot-value">${{ number_format($workOrder->deductible_amount, 0, ',', '.') }}</td>
        </tr>
        @endif
        <tr>
            <td class="tot-label grand">TOTAL</td>
            <td class="tot-value grand">${{ number_format($workOrder->total_amount, 0, ',', '.') }}</td>
        </tr>
    </table>

    {{-- ═══ INVENTARIO VEHICULAR ═══ --}}
    @if($workOrder->vehicle_inventory)
    @php $inv = $workOrder->vehicle_inventory; @endphp
    <div class="section-title">Inventario del Vehiculo al Ingreso</div>
    <table class="inv-table">
        @php $invItems = \App\Models\WorkOrder::INVENTORY_ITEMS; $chunks = array_chunk($invItems, 2, true); @endphp
        @foreach($chunks as $chunk)
        <tr>
            @foreach($chunk as $key => $label)
            <td class="inv-label">{{ $label }}</td>
            <td class="inv-val {{ !empty($inv[$key]) ? 'inv-yes' : 'inv-no' }}">
                {{ !empty($inv[$key]) ? 'SI' : 'NO' }}
            </td>
            @endforeach
            @if(count($chunk) < 2)
            <td class="inv-label"></td><td class="inv-val"></td>
            @endif
        </tr>
        @endforeach
        <tr>
            <td class="inv-label">Combustible</td>
            <td class="inv-val">{{ $inv['combustible'] ?? '—' }}</td>
            <td class="inv-label">KM</td>
            <td class="inv-val">{{ number_format($inv['km_ingreso'] ?? 0, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="inv-label">Llaves</td>
            <td class="inv-val">{{ $inv['llaves_count'] ?? '—' }}</td>
            <td class="inv-label">Conductor</td>
            <td class="inv-val" style="text-align:left;">{{ $workOrder->conductor_name ?? '' }}</td>
        </tr>
    </table>
    @endif

    {{-- ═══ DECLARACION DE OBJETOS ═══ --}}
    @if($workOrder->objects_declaration)
    <div class="section-title">Declaracion de Objetos</div>
    <div style="border:1px solid #999;padding:4px 8px;font-size:9.5px;margin-bottom:8px;">
        {{ $workOrder->objects_declaration }}
    </div>
    @endif

    {{-- ═══ OBSERVACIONES ═══ --}}
    @if($workOrder->notes)
    <div class="section-title">Observaciones</div>
    <div style="border:1px solid #999;padding:4px 8px;font-size:9.5px;margin-bottom:8px;">
        {{ $workOrder->notes }}
    </div>
    @endif

    {{-- ═══ FIRMAS ═══ --}}
    <table class="sig-table">
        <tr>
            <td><div class="sig-line">Firma Cliente</div></td>
            <td><div class="sig-line">Firma Taller</div></td>
            <td><div class="sig-line">Firma Liquidador</div></td>
        </tr>
    </table>

    {{-- ═══ LEGAL ═══ --}}
    <div class="legal">
        <strong>IMPORTANTE:</strong> Autorizo a {{ $company->name ?? 'el taller' }} a realizar los trabajos senalados en esta orden de trabajo,
        a trasladar el vehiculo unicamente con fines asociados a la reparacion.
        La empresa no se responsabiliza por perdidas de objetos no declarados.
        @if($company->quotation_validity_days)
        <br>Validez del presupuesto: {{ $company->quotation_validity_days }} dias desde la fecha de emision.
        @endif
    </div>

</body>
</html>
