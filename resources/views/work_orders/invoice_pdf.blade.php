<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 30px;
            line-height: 1.4;
        }

        /* ─── Header ─── */
        .header {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 12px;
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

        /* ─── Title ─── */
        .invoice-title {
            text-align: center;
            font-size: 26px;
            font-weight: bold;
            color: #1a3c6e;
            margin: 25px 0 5px;
            letter-spacing: 3px;
        }
        .invoice-subtitle {
            text-align: center;
            font-size: 11px;
            color: #666;
            margin-bottom: 25px;
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
            margin: 20px 0 8px;
        }

        /* ─── Invoice table ─── */
        table.invoice {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
        }
        table.invoice th,
        table.invoice td {
            padding: 10px 14px;
            border: 1px solid #ddd;
            font-size: 12px;
        }
        table.invoice th {
            background: #1a3c6e;
            color: #fff;
            text-align: left;
            font-weight: 700;
            font-size: 11px;
            text-transform: uppercase;
        }
        table.invoice td.text-right {
            text-align: right;
            font-variant-numeric: tabular-nums;
        }
        table.invoice tr.grand {
            background: #f0f3f8;
        }
        table.invoice tr.grand td {
            font-size: 14px;
            font-weight: bold;
            color: #1a3c6e;
            border-top: 2px solid #1a3c6e;
        }

        /* ─── Signature ─── */
        .signature-area {
            margin-top: 60px;
            text-align: center;
        }
        .signature-line {
            border-top: 1px solid #333;
            display: inline-block;
            width: 200px;
            margin-bottom: 5px;
        }
        .signature-label {
            font-size: 10px;
            color: #555;
        }

        /* ─── Footer ─── */
        .footer {
            margin-top: 40px;
            font-size: 9px;
            color: #999;
            text-align: center;
            border-top: 1px solid #ddd;
            padding-top: 10px;
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

    {{-- ─── Title ─── --}}
    <div class="invoice-title">FACTURA</div>
    <div class="invoice-subtitle">
        OT N&deg; {{ $workOrder->folio_display }}
        &nbsp;&nbsp;|&nbsp;&nbsp;
        Fecha: {{ \Carbon\Carbon::parse($workOrder->date)->format('d/m/Y') }}
    </div>

    {{-- ─── Datos Cliente ─── --}}
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

    {{-- ─── Datos Vehiculo ─── --}}
    <div class="section-title">Vehiculo</div>
    <table class="info-table">
        <tr>
            <td class="label">Vehiculo:</td>
            <td>{{ $workOrder->vehicle->brand }} {{ $workOrder->vehicle->model }} {{ $workOrder->vehicle->year ?? '' }}</td>
            <td class="label">Patente:</td>
            <td>{{ $workOrder->vehicle->license_plate }}</td>
        </tr>
    </table>

    {{-- ─── Tabla de factura (sin desglose) ─── --}}
    @php
        $netoAutorizado = $workOrder->items->where('is_approved', true)->sum('price_authorized');
        $iva = round($netoAutorizado * 0.19);
        $total = $netoAutorizado + $iva;
    @endphp

    <table class="invoice">
        <thead>
            <tr>
                <th>Concepto</th>
                <th style="width:150px;text-align:right;">Monto</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Servicio de Reparacion Automotriz</td>
                <td class="text-right">${{ number_format($netoAutorizado, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>IVA 19%</td>
                <td class="text-right">${{ number_format($iva, 0, ',', '.') }}</td>
            </tr>
            <tr class="grand">
                <td><strong>TOTAL</strong></td>
                <td class="text-right"><strong>${{ number_format($total, 0, ',', '.') }}</strong></td>
            </tr>
        </tbody>
    </table>

    {{-- ─── Firma ─── --}}
    <div class="signature-area">
        <div class="signature-line"></div><br>
        <span class="signature-label">Firma y Timbre</span><br>
        <span class="signature-label">{{ $company->name }}</span>
    </div>

    {{-- ─── Pie legal ─── --}}
    <div class="footer">
        Documento tributario de uso interno. No constituye boleta ni factura electronica oficial (SII).
        <br>
        {{ $company->name }} — RUT: {{ $company->rut }}
        @if($company->address) — {{ $company->address }} @endif
    </div>

</body>
</html>
