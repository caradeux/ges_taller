<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #000; margin: 15px; }

        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
        .header-table td { vertical-align: top; padding: 4px; }
        .company-name { font-size: 16px; font-weight: bold; color: #1a3c6e; }
        .company-info { font-size: 9px; color: #444; line-height: 1.5; }

        .title-bar {
            background: #1a3c6e; color: white; text-align: center;
            font-size: 13px; font-weight: bold; padding: 6px 0;
            letter-spacing: 2px; margin: 6px 0 10px;
        }

        .data-table { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
        .data-table td { border: 1px solid #999; padding: 4px 8px; font-size: 10px; vertical-align: middle; }
        .data-table .label { background: #e8ecf1; font-weight: bold; color: #333; width: 110px; font-size: 9px; text-transform: uppercase; }
        .data-table .value { color: #000; }
        .data-table .section-header { background: #1a3c6e; color: white; font-weight: bold; text-align: center; font-size: 9px; text-transform: uppercase; letter-spacing: 1px; }

        .inv-table { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
        .inv-table td { border: 1px solid #999; padding: 5px 8px; font-size: 10px; }
        .inv-table .inv-label { font-weight: bold; width: 200px; background: #f0f2f5; }
        .inv-table .inv-val { text-align: center; width: 60px; font-weight: bold; font-size: 11px; }
        .inv-yes { color: #16a34a; }
        .inv-no { color: #dc2626; }

        .obs-box {
            border: 1px solid #999; padding: 8px 10px; min-height: 60px;
            font-size: 10px; margin-bottom: 10px; line-height: 1.6;
        }
        .obs-label { font-weight: bold; font-size: 9px; text-transform: uppercase; color: #333; background: #e8ecf1; padding: 4px 8px; border: 1px solid #999; border-bottom: none; }

        .sig-table { width: 100%; border-collapse: collapse; margin-top: 30px; }
        .sig-table td { text-align: center; padding-top: 50px; width: 50%; vertical-align: bottom; }
        .sig-line { border-top: 1px solid #000; display: inline-block; width: 200px; padding-top: 4px; font-size: 9px; font-weight: bold; }
        .sig-sub { font-size: 8px; color: #666; margin-top: 2px; }

        .legal {
            margin-top: 15px; padding: 8px 10px; background: #f5f7fa;
            border: 1px solid #ddd; font-size: 8px; color: #555; line-height: 1.5;
        }

        .fuel-bar { display: inline-block; width: 120px; height: 14px; border: 1px solid #999; position: relative; }
        .fuel-fill { height: 100%; background: #1a3c6e; }
    </style>
</head>
<body>

    {{-- HEADER --}}
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
            <td style="width:35%; text-align:right;">
                <div style="font-size:18px; font-weight:bold; color:#1a3c6e;">
                    {{ $workOrder->folio ? 'OT N° ' . $workOrder->folio : 'OT #' . $workOrder->id }}
                </div>
                <div style="font-size:9px; color:#666;">
                    Fecha: {{ \Carbon\Carbon::parse($workOrder->date)->format('d/m/Y') }}
                </div>
            </td>
        </tr>
    </table>

    <div class="title-bar">ACTA DE RECEPCIÓN DE VEHÍCULO</div>

    {{-- DATOS DEL CLIENTE --}}
    <table class="data-table">
        <tr><td colspan="4" class="section-header">Datos del Cliente</td></tr>
        <tr>
            <td class="label">Nombre</td>
            <td class="value">{{ $workOrder->client->name ?? '—' }}</td>
            <td class="label">RUT</td>
            <td class="value">{{ $workOrder->client->rut_dni ?? '—' }}</td>
        </tr>
        <tr>
            <td class="label">Teléfono</td>
            <td class="value">{{ $workOrder->client->phone ?? '—' }}</td>
            <td class="label">Email</td>
            <td class="value">{{ $workOrder->client->email ?? '—' }}</td>
        </tr>
    </table>

    {{-- DATOS DEL VEHÍCULO --}}
    <table class="data-table">
        <tr><td colspan="4" class="section-header">Datos del Vehículo</td></tr>
        <tr>
            <td class="label">Patente</td>
            <td class="value" style="font-weight:bold; font-size:12px;">{{ $workOrder->vehicle->license_plate ?? '—' }}</td>
            <td class="label">Marca / Modelo</td>
            <td class="value">{{ $workOrder->vehicle->brand ?? '' }} {{ $workOrder->vehicle->model ?? '' }}</td>
        </tr>
        <tr>
            <td class="label">Año</td>
            <td class="value">{{ $workOrder->vehicle->year ?? '—' }}</td>
            <td class="label">Color</td>
            <td class="value">{{ $workOrder->vehicle->color ?? '—' }}</td>
        </tr>
        <tr>
            <td class="label">VIN / Chasis</td>
            <td class="value" colspan="3">{{ $workOrder->vehicle->vin_chassis ?? '—' }}</td>
        </tr>
    </table>

    {{-- DATOS DE INGRESO --}}
    @php
        $inventory = $workOrder->vehicle_inventory ?? [];
        $fuelMap = ['empty' => 'Vacío', '1/4' => '1/4', '1/2' => '1/2', '3/4' => '3/4', 'full' => 'Lleno'];
        $fuelLevel = $inventory['fuel_level'] ?? null;
        $fuelPercent = match($fuelLevel) { 'empty' => 0, '1/4' => 25, '1/2' => 50, '3/4' => 75, 'full' => 100, default => 0 };
    @endphp

    <table class="data-table" style="margin-bottom:6px;">
        <tr><td colspan="4" class="section-header">Datos de Ingreso</td></tr>
        <tr>
            <td class="label">KM Ingreso</td>
            <td class="value">{{ isset($inventory['km_ingreso']) ? number_format($inventory['km_ingreso'], 0, ',', '.') : '—' }}</td>
            <td class="label">Combustible</td>
            <td class="value">{{ $fuelMap[$fuelLevel] ?? '—' }}</td>
        </tr>
        <tr>
            <td class="label">Llaves</td>
            <td class="value">{{ $inventory['keys_count'] ?? '—' }}</td>
            <td class="label">Conductor</td>
            <td class="value">{{ $workOrder->conductor_name ?? '—' }}</td>
        </tr>
        @if($workOrder->insuranceCompany)
        <tr>
            <td class="label">Aseguradora</td>
            <td class="value">{{ $workOrder->insuranceCompany->name }}</td>
            <td class="label">N° Siniestro</td>
            <td class="value">{{ $workOrder->claim_number ?? '—' }}</td>
        </tr>
        @endif
    </table>

    {{-- INVENTARIO VEHICULAR --}}
    <table class="inv-table">
        <tr><td colspan="4" style="background:#1a3c6e;color:white;font-weight:bold;text-align:center;font-size:9px;text-transform:uppercase;letter-spacing:1px;">Inventario Vehicular</td></tr>
        @php
            $items = \App\Models\WorkOrder::INVENTORY_ITEMS;
            $chunks = array_chunk($items, 2, true);
        @endphp
        @foreach($chunks as $pair)
            <tr>
                @foreach($pair as $key => $label)
                    <td class="inv-label">{{ $label }}</td>
                    <td class="inv-val">
                        @if(!empty($inventory[$key]))
                            <span class="inv-yes">✔ SÍ</span>
                        @else
                            <span class="inv-no">✘ NO</span>
                        @endif
                    </td>
                @endforeach
                @if(count($pair) < 2)
                    <td class="inv-label" style="border:none;background:none;"></td>
                    <td class="inv-val" style="border:none;"></td>
                @endif
            </tr>
        @endforeach
    </table>

    {{-- DECLARACIÓN DE OBJETOS --}}
    <div class="obs-label">Declaración de Objetos y Observaciones</div>
    <div class="obs-box">
        {{ $workOrder->objects_declaration ?? 'Sin observaciones.' }}
    </div>

    {{-- CONDICIONES --}}
    <div class="legal">
        <strong>CONDICIONES DE RECEPCIÓN:</strong><br>
        1. El cliente declara que el vehículo ingresa al taller con los elementos y condiciones descritos en el presente documento.<br>
        2. El taller no se responsabiliza por objetos de valor no declarados al momento del ingreso.<br>
        3. El taller se compromete a mantener el vehículo en custodia durante el periodo de reparación.<br>
        4. El retiro del vehículo deberá realizarse dentro de los 30 días posteriores a la notificación de término de trabajo.<br>
        5. Transcurrido dicho plazo, se cobrará un cargo por estacionamiento diario según tarifa vigente.<br>
        6. El cliente autoriza la realización de los trabajos de reparación acordados en la orden de trabajo asociada.
    </div>

    {{-- FIRMAS --}}
    <table class="sig-table">
        <tr>
            <td>
                <div class="sig-line">Firma del Cliente</div>
                <div class="sig-sub">{{ $workOrder->client->name ?? '' }}</div>
                <div class="sig-sub">RUT: {{ $workOrder->client->rut_dni ?? '' }}</div>
            </td>
            <td>
                <div class="sig-line">Firma Recepcionista</div>
                <div class="sig-sub">{{ $company->name ?? 'Taller' }}</div>
            </td>
        </tr>
    </table>

    <div style="text-align:center; margin-top:15px; font-size:8px; color:#999;">
        Documento generado el {{ now()->format('d/m/Y H:i') }} — {{ $company->name ?? 'GesTaller' }}
    </div>

</body>
</html>
