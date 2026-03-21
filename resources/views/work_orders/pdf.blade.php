<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 20px;
            line-height: 1.4;
        }

        /* ─── Header ─── */
        .header {
            text-align: center;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 3px solid #1a3c6e;
        }
        .company-name {
            font-size: 20px;
            font-weight: bold;
            color: #1a3c6e;
            margin: 0;
        }
        .company-info {
            font-size: 10px;
            color: #666;
            margin: 3px 0 0;
        }

        /* ─── OT Bar ─── */
        .ot-bar {
            background: #1a3c6e;
            color: #fff;
            padding: 8px 14px;
            margin-bottom: 15px;
            font-size: 11px;
        }
        .ot-bar strong {
            font-size: 13px;
        }

        /* ─── Info blocks ─── */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .info-table td {
            padding: 4px 8px;
            font-size: 11px;
            vertical-align: top;
        }
        .info-table .label {
            font-weight: bold;
            color: #555;
            width: 130px;
        }

        /* ─── Section title ─── */
        .section-title {
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            color: #1a3c6e;
            border-bottom: 1px solid #ccc;
            padding-bottom: 4px;
            margin: 15px 0 8px;
        }

        /* ─── Items table ─── */
        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table.items th,
        table.items td {
            padding: 6px 8px;
            border: 1px solid #ddd;
            font-size: 11px;
        }
        table.items th {
            background: #1a3c6e;
            color: #fff;
            text-align: left;
            font-weight: 700;
            font-size: 10px;
            text-transform: uppercase;
        }
        table.items td.text-right {
            text-align: right;
        }
        table.items tr:nth-child(even) {
            background: #f8f9fb;
        }
        table.items .salvage {
            color: #b45309;
            font-weight: bold;
        }

        /* ─── Subtotals ─── */
        table.subtotals {
            width: 50%;
            margin-left: 50%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        table.subtotals td {
            padding: 4px 8px;
            font-size: 11px;
            border-bottom: 1px solid #eee;
        }
        table.subtotals .label {
            text-align: left;
            color: #555;
            font-weight: 600;
        }
        table.subtotals .value {
            text-align: right;
            font-variant-numeric: tabular-nums;
        }

        /* ─── Totals ─── */
        table.totals {
            width: 50%;
            margin-left: 50%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.totals td {
            padding: 5px 8px;
            font-size: 11px;
            border-bottom: 1px solid #ddd;
        }
        table.totals .label {
            text-align: left;
            font-weight: 600;
            color: #333;
        }
        table.totals .value {
            text-align: right;
            font-variant-numeric: tabular-nums;
        }
        table.totals tr.grand td {
            font-size: 13px;
            font-weight: bold;
            color: #1a3c6e;
            border-top: 2px solid #1a3c6e;
            border-bottom: 2px solid #1a3c6e;
        }

        /* ─── Notes ─── */
        .notes-box {
            background: #f8f9fb;
            border: 1px solid #ddd;
            padding: 8px 12px;
            font-size: 11px;
            margin-bottom: 20px;
        }

        /* ─── Signatures ─── */
        .signatures {
            width: 100%;
            margin-top: 50px;
        }
        .signatures td {
            width: 33%;
            text-align: center;
            padding-top: 50px;
            font-size: 10px;
            vertical-align: bottom;
        }
        .signatures .line {
            border-top: 1px solid #333;
            display: inline-block;
            width: 150px;
            margin-bottom: 4px;
        }

        /* ─── Footer ─── */
        .footer {
            margin-top: 30px;
            font-size: 9px;
            color: #999;
            text-align: center;
            border-top: 1px solid #ddd;
            padding-top: 8px;
        }
    </style>
</head>
<body>

    {{-- ─── Header ─── --}}
    <div class="header">
        <p class="company-name">{{ $company->name }}</p>
        <p class="company-info">
            RUT: {{ $company->rut }}
            @if($company->address) | {{ $company->address }} @endif
            @if($company->phone) | Tel: {{ $company->phone }} @endif
            @if($company->email) | {{ $company->email }} @endif
        </p>
    </div>

    {{-- ─── OT Bar ─── --}}
    <div class="ot-bar">
        <strong>ORDEN DE TRABAJO N&deg; {{ $workOrder->folio_display }}</strong>
        &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
        Fecha: {{ \Carbon\Carbon::parse($workOrder->date)->format('d/m/Y') }}
        @if($company->address)
            &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
            {{ $company->address }}
        @endif
    </div>

    {{-- ─── Datos Cliente y Vehiculo ─── --}}
    <div class="section-title">Datos del Cliente</div>
    <table class="info-table">
        <tr>
            <td class="label">Cliente:</td>
            <td>{{ $workOrder->client->name }}</td>
            <td class="label">RUT:</td>
            <td>{{ $workOrder->client->rut_dni }}</td>
        </tr>
        @if($workOrder->client->phone || $workOrder->client->email)
        <tr>
            <td class="label">Telefono:</td>
            <td>{{ $workOrder->client->phone ?? '—' }}</td>
            <td class="label">Email:</td>
            <td>{{ $workOrder->client->email ?? '—' }}</td>
        </tr>
        @endif
    </table>

    <div class="section-title">Datos del Vehiculo</div>
    <table class="info-table">
        <tr>
            <td class="label">Marca / Modelo:</td>
            <td>{{ $workOrder->vehicle->brand }} {{ $workOrder->vehicle->model }}</td>
            <td class="label">Ano:</td>
            <td>{{ $workOrder->vehicle->year ?? '—' }}</td>
        </tr>
        <tr>
            <td class="label">Patente:</td>
            <td>{{ $workOrder->vehicle->license_plate }}</td>
            <td class="label">Color:</td>
            <td>{{ $workOrder->vehicle->color ?? '—' }}</td>
        </tr>
        <tr>
            <td class="label">VIN / Chasis:</td>
            <td colspan="3">{{ $workOrder->vehicle->vin_chassis ?? '—' }}</td>
        </tr>
    </table>

    @if($workOrder->insuranceCompany || $workOrder->liquidator)
    <div class="section-title">Seguro y Liquidacion</div>
    <table class="info-table">
        <tr>
            <td class="label">Aseguradora:</td>
            <td>{{ $workOrder->insuranceCompany->name ?? '—' }}</td>
            <td class="label">Liquidador:</td>
            <td>{{ $workOrder->liquidator->name ?? '—' }}</td>
        </tr>
        @if($workOrder->claim_number)
        <tr>
            <td class="label">N. Siniestro:</td>
            <td>{{ $workOrder->claim_number }}</td>
            <td class="label">Deducible:</td>
            <td>${{ number_format($workOrder->deductible_amount ?? 0, 0, ',', '.') }}</td>
        </tr>
        @endif
    </table>
    @endif

    {{-- ─── Items aprobados ─── --}}
    @php
        $approvedItems = $workOrder->items->where('is_approved', true);
        $subtotalsByUn = $approvedItems->groupBy(fn($item) => $item->unType->code ?? 'Otro')->map(function($group) {
            return $group->sum('price_authorized');
        });
        $netoAutorizado = $approvedItems->sum('price_authorized');
        $iva = round($netoAutorizado * 0.19);
        $total = $netoAutorizado + $iva;
    @endphp

    <div class="section-title">Detalle de Trabajos (Aprobados)</div>
    <table class="items">
        <thead>
            <tr>
                <th style="width:80px;">UN</th>
                <th>Descripcion</th>
                <th style="width:100px;text-align:right;">M. Taller</th>
                <th style="width:100px;text-align:right;">M. Autorizado</th>
                <th style="width:100px;text-align:right;">Costo Real</th>
            </tr>
        </thead>
        <tbody>
            @foreach($approvedItems as $item)
            <tr>
                <td>
                    {{ $item->unType->code ?? '—' }}
                    @if($item->is_salvage) <span class="salvage">(S)</span> @endif
                </td>
                <td>{{ $item->description }}</td>
                <td class="text-right">${{ number_format($item->price_workshop, 0, ',', '.') }}</td>
                <td class="text-right">${{ number_format($item->price_authorized, 0, ',', '.') }}</td>
                <td class="text-right">${{ number_format($item->price_real, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- ─── Subtotales por UN ─── --}}
    @if($subtotalsByUn->count() > 1)
    <table class="subtotals">
        @foreach($subtotalsByUn as $code => $subtotal)
        <tr>
            <td class="label">Subtotal {{ $code }}:</td>
            <td class="value">${{ number_format($subtotal, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </table>
    @endif

    {{-- ─── Totales ─── --}}
    <table class="totals">
        <tr>
            <td class="label">Neto Autorizado</td>
            <td class="value">${{ number_format($netoAutorizado, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="label">IVA 19%</td>
            <td class="value">${{ number_format($iva, 0, ',', '.') }}</td>
        </tr>
        <tr class="grand">
            <td class="label">TOTAL</td>
            <td class="value">${{ number_format($total, 0, ',', '.') }}</td>
        </tr>
    </table>

    {{-- ─── Observaciones ─── --}}
    @if($workOrder->notes)
    <div class="section-title">Observaciones</div>
    <div class="notes-box">
        {{ $workOrder->notes }}
    </div>
    @endif

    {{-- ─── Firmas ─── --}}
    <table class="signatures">
        <tr>
            <td>
                <div class="line"></div><br>
                <strong>Cliente</strong><br>
                {{ $workOrder->client->name }}
            </td>
            <td>
                <div class="line"></div><br>
                <strong>Taller</strong><br>
                {{ $company->name }}
            </td>
            <td>
                <div class="line"></div><br>
                <strong>Liquidador</strong><br>
                {{ $workOrder->liquidator->name ?? '—' }}
            </td>
        </tr>
    </table>

    {{-- ─── Pie ─── --}}
    <div class="footer">
        @if($company->quotation_validity_days)
            Esta orden de trabajo tiene una validez de {{ $company->quotation_validity_days }} dias a partir de la fecha de emision.
        @endif
        <br>
        {{ $company->name }} — RUT: {{ $company->rut }}
        @if($company->address) — {{ $company->address }} @endif
    </div>

</body>
</html>
