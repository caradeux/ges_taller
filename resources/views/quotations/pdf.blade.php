<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Presupuesto #{{ $quotation->folio }}</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            color: #333;
            font-size: 12px;
            line-height: 1.5;
        }

        .header {
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #0d6efd;
        }

        .folio-box {
            float: right;
            border: 2px solid #dc3545;
            padding: 10px;
            color: #dc3545;
            font-weight: bold;
            font-size: 16px;
        }

        .info-section {
            width: 100%;
            margin-bottom: 20px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 5px;
            vertical-align: top;
        }

        .section-title {
            background: #f8f9fa;
            font-weight: bold;
            padding: 5px;
            margin-top: 10px;
            border-left: 3px solid #0d6efd;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .items-table th {
            background: #343a40;
            color: #fff;
            padding: 8px;
            text-align: left;
        }

        .items-table td {
            border-bottom: 1px solid #dee2e6;
            padding: 8px;
        }

        .totals {
            float: right;
            width: 250px;
            margin-top: 20px;
        }

        .totals table {
            width: 100%;
            border-collapse: collapse;
        }

        .totals td {
            padding: 5px;
            text-align: right;
        }

        .total-row {
            font-weight: bold;
            font-size: 14px;
            background: #f8f9fa;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #6c757d;
            border-top: 1px solid #dee2e6;
            padding-top: 10px;
        }

        .clearfix {
            clear: both;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="folio-box">PRESUPUESTO #{{ $quotation->folio }}</div>
        <div class="logo">GES_TALLER</div>
        <div>Servicios Automotrices Profesionales</div>
        <div style="font-size: 10px;">Calle Ficticia 123, Viña del Mar | +56 9 1234 5678</div>
    </div>

    <div class="info-section">
        <div class="section-title">DATOS DEL CLIENTE</div>
        <table class="info-table">
            <tr>
                <td width="15%"><strong>Cliente:</strong></td>
                <td width="35%">{{ $quotation->client->name }}</td>
                <td width="15%"><strong>RUT:</strong></td>
                <td width="35%">{{ $quotation->client->rut_dni }}</td>
            </tr>
            <tr>
                <td><strong>Teléfono:</strong></td>
                <td>{{ $quotation->client->phone }}</td>
                <td><strong>Email:</strong></td>
                <td>{{ $quotation->client->email }}</td>
            </tr>
        </table>
    </div>

    <div class="info-section">
        <div class="section-title">DATOS DEL VEHÍCULO</div>
        <table class="info-table">
            <tr>
                <td width="15%"><strong>Patente:</strong></td>
                <td width="35%">{{ $quotation->vehicle->license_plate }}</td>
                <td width="15%"><strong>Marca/Modelo:</strong></td>
                <td width="35%">{{ $quotation->vehicle->brand }} {{ $quotation->vehicle->model }}</td>
            </tr>
            <tr>
                <td><strong>Año:</strong></td>
                <td>{{ $quotation->vehicle->year }}</td>
                <td><strong>Kilometraje:</strong></td>
                <td>{{ number_format($quotation->vehicle->odometer, 0, ',', '.') }} km</td>
            </tr>
        </table>
    </div>

    @if($quotation->insuranceCompany)
        <div class="info-section">
            <div class="section-title">DATOS DEL SEGURO</div>
            <table class="info-table">
                <tr>
                    <td width="15%"><strong>Compañía:</strong></td>
                    <td width="35%">{{ $quotation->insuranceCompany->name }}</td>
                    <td width="15%"><strong>Liquidador:</strong></td>
                    <td width="35%">{{ $quotation->liquidator ? $quotation->liquidator->name : 'N/A' }}</td>
                </tr>
            </table>
        </div>
    @endif

    <div class="section-title">DETALLE DEL PRESUPUESTO</div>
    <table class="items-table">
        <thead>
            <tr>
                <th>Descripción</th>
                <th width="10%">Cant.</th>
                <th width="15%">Repuestos</th>
                <th width="15%">M. Obra</th>
                <th width="15%">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($quotation->items as $item)
                <tr>
                    <td>{{ $item->description }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>${{ number_format($item->parts_price, 0, ',', '.') }}</td>
                    <td>${{ number_format($item->labor_price, 0, ',', '.') }}</td>
                    <td>${{ number_format($item->total, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <table>
            <tr>
                <td>Subtotal Neto:</td>
                <td>${{ number_format($quotation->total_amount / 1.19, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>IVA (19%):</td>
                <td>${{ number_format($quotation->total_amount - ($quotation->total_amount / 1.19), 0, ',', '.') }}</td>
            </tr>
            <tr class="total-row">
                <td>TOTAL:</td>
                <td>${{ number_format($quotation->total_amount, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div class="clearfix"></div>

    <div style="margin-top: 40px;">
        <p><strong>Observaciones:</strong><br>
            {{ $quotation->notes ?? 'Sin observaciones adicionales.' }}</p>
    </div>

    <div class="footer">
        Este documento es un presupuesto formal válido por 15 días.
        <br>Ges_Taller &copy; {{ date('Y') }} - Gestión de Taller Automotriz
    </div>
</body>

</html>