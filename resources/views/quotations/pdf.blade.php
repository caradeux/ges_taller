<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Cotización #{{ $quotation->folio }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Helvetica', Arial, sans-serif;
            font-size: 10px;
            color: #222;
            padding: 18px 24px;
        }

        /* ─── HEADER ─── */
        .page-header {
            text-align: center;
            margin-bottom: 8px;
        }
        .page-header .company-name {
            font-size: 22px;
            font-weight: bold;
            color: #1a3c6e;
            letter-spacing: 1px;
        }
        .page-header .doc-title {
            font-size: 16px;
            font-weight: bold;
            color: #333;
            letter-spacing: 2px;
            margin-top: 2px;
        }
        .header-bar {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
            border: 1px solid #555;
        }
        .header-bar td {
            padding: 4px 6px;
            font-size: 9.5px;
            border: 1px solid #999;
        }
        .header-bar .label { background: #e8e8e8; font-weight: bold; }
        .header-bar .folio-num { font-size: 13px; font-weight: bold; text-align: center; }
        .header-bar .date-val  { font-size: 11px; font-weight: bold; text-align: center; }

        /* ─── INFO GRID ─── */
        .info-grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
            border: 1px solid #999;
        }
        .info-grid td {
            padding: 3px 5px;
            border: 1px solid #bbb;
            font-size: 9px;
        }
        .info-grid .lbl { background: #f0f0f0; font-weight: bold; width: 80px; }
        .info-grid .lbl-sm { background: #f0f0f0; font-weight: bold; width: 60px; }

        /* ─── ITEMS TABLE ─── */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4px;
        }
        .items-table th {
            background: #1a3c6e;
            color: #fff;
            padding: 5px 4px;
            text-align: center;
            font-size: 9px;
            border: 1px solid #1a3c6e;
            font-weight: bold;
        }
        .items-table th.desc-col { text-align: left; padding-left: 6px; }
        .items-table td {
            border: 1px solid #ccc;
            padding: 3.5px 4px;
            font-size: 9px;
            vertical-align: middle;
        }
        .items-table td.num { text-align: right; }
        .items-table td.center { text-align: center; }
        .items-table .subtotal-row td {
            background: #f0f0f0;
            font-weight: bold;
            border-top: 2px solid #555;
        }
        .items-table .action-badge {
            display: inline-block;
            background: #e8e8e8;
            border-radius: 2px;
            padding: 1px 4px;
            font-weight: bold;
            font-size: 8.5px;
        }
        .salvar-badge {
            font-size: 8px;
            color: #c00;
            font-weight: bold;
        }

        /* ─── FOOTER SECTION ─── */
        .footer-section {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }
        .footer-section td { vertical-align: top; padding: 0; }

        .acciones-box {
            font-size: 8.5px;
            padding: 6px 8px;
            border: 1px solid #bbb;
        }
        .acciones-box .title { font-weight: bold; text-decoration: underline; margin-bottom: 2px; }

        .totals-box {
            border: 1px solid #555;
            width: 200px;
            float: right;
        }
        .totals-box table { width: 100%; border-collapse: collapse; }
        .totals-box td { padding: 4px 8px; font-size: 10px; border: 1px solid #bbb; }
        .totals-box .lbl { background: #f0f0f0; font-weight: bold; }
        .totals-box .val { text-align: right; font-weight: bold; }
        .totals-box .total-row td { background: #1a3c6e; color: #fff; font-size: 12px; font-weight: bold; }

        /* ─── LEGAL / FOOTER ─── */
        .legal {
            margin-top: 12px;
            border-top: 1px solid #ccc;
            padding-top: 6px;
            font-size: 8px;
            color: #444;
        }
        .legal .company-line { font-weight: bold; font-size: 9px; margin-bottom: 2px; }
        .validity {
            text-align: center;
            font-weight: bold;
            font-size: 9px;
            margin-top: 6px;
            letter-spacing: 1px;
            color: #333;
        }

        .clearfix { clear: both; }
    </style>
</head>
<body>

    {{-- ═══ ENCABEZADO ═══ --}}
    <div class="page-header">
        <div class="company-name">{{ strtoupper($company->name ?? 'GES_TALLER') }}</div>
        <div class="doc-title">HOJA DE COTIZACIÓN</div>
    </div>

    <table class="header-bar">
        <tr>
            <td class="label" style="width:70px;">DIRECCIÓN:</td>
            <td style="width:220px;">{{ $company->address ?? '' }}</td>
            <td class="label" style="width:80px; text-align:center;">COTIZACIÓN Nº</td>
            <td class="folio-num" style="width:60px;">{{ $quotation->folio }}</td>
            <td class="label" style="width:40px; text-align:center;">FECHA</td>
            <td class="date-val" style="width:70px;">
                {{ \Carbon\Carbon::parse($quotation->date)->format('d-M-y') }}
            </td>
        </tr>
    </table>

    {{-- ═══ INFO CLIENTE / VEHÍCULO ═══ --}}
    <table class="info-grid">
        <tr>
            <td class="lbl">NOMBRE CLIENTE</td>
            <td style="width:160px;">{{ $quotation->client->name }}</td>
            <td class="lbl-sm">MODELO</td>
            <td style="width:100px;">{{ $quotation->vehicle->model }}</td>
            <td class="lbl-sm" style="width:55px;">Siniestro</td>
            <td>{{ $quotation->claim_number ?? '' }}</td>
        </tr>
        <tr>
            <td class="lbl">RUT</td>
            <td>{{ $quotation->client->rut_dni ?? '' }}</td>
            <td class="lbl-sm">AÑO</td>
            <td>{{ $quotation->vehicle->year }}</td>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td class="lbl">CONTACTO</td>
            <td>{{ $quotation->client->phone ?? '' }}</td>
            <td class="lbl-sm">COLOR</td>
            <td>{{ $quotation->vehicle->color ?? '' }}</td>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td class="lbl">CIA. DE SEGUROS</td>
            <td>{{ $quotation->insuranceCompany?->name ?? 'Particular' }}</td>
            <td class="lbl-sm">PATENTE</td>
            <td>{{ $quotation->vehicle->license_plate }}</td>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td class="lbl">LIQUIDADOR</td>
            <td>{{ $quotation->liquidator?->name ?? '' }}</td>
            <td class="lbl-sm">Nº DE CHASIS</td>
            <td>{{ $quotation->vehicle->vin_chassis ?? '' }}</td>
            <td class="lbl-sm">Nº de Ingreso</td>
            <td>{{ $quotation->intake_number ?? '' }}</td>
        </tr>
    </table>

    {{-- ═══ TABLA DE ÍTEMS ═══ --}}
    @php
        $grouped   = $quotation->items->groupBy(fn($it) => $it->unType->category);
        $subRepair = $grouped->get('repair', collect())->sum('price');
        $subPaint  = $grouped->get('paint',  collect())->sum('price');
        $subDm     = $grouped->get('dm',     collect())->sum('price');
        $subParts  = $grouped->get('parts',  collect())->sum('price');
        $subOther  = $grouped->get('other',  collect())->sum('price');
        $neto      = $quotation->total_amount / 1.19;
        $iva       = $quotation->total_amount - $neto;
    @endphp
    <table class="items-table">
        <thead>
            <tr>
                <th style="width:36px;">UN</th>
                <th class="desc-col">DESCRIPCIÓN DE TRABAJOS</th>
                <th style="width:72px;">REPARACIÓN</th>
                <th style="width:72px;">PINTURA</th>
                <th style="width:62px;">D/M</th>
                <th style="width:72px;">VALOR RPTO</th>
                <th style="width:62px;">OTROS</th>
            </tr>
        </thead>
        <tbody>
            @foreach($quotation->items as $item)
            @php
                $cat   = $item->unType->category;
                $price = $item->price;
            @endphp
            <tr>
                <td class="center"><span class="action-badge">{{ $item->unType->code }}</span></td>
                <td>
                    {{ $item->description }}
                    @if($item->is_salvage) <span class="salvar-badge">SALVAR</span> @endif
                </td>
                <td class="num">{{ $cat === 'repair' && $price > 0 ? '$ '.number_format($price,0,',','.') : '' }}</td>
                <td class="num">{{ $cat === 'paint'  && $price > 0 ? '$ '.number_format($price,0,',','.') : '' }}</td>
                <td class="num">{{ $cat === 'dm'     && $price > 0 ? '$ '.number_format($price,0,',','.') : '' }}</td>
                <td class="num">{{ $cat === 'parts'  && $price > 0 ? '$ '.number_format($price,0,',','.') : '' }}</td>
                <td class="num">{{ $cat === 'other'  && $price > 0 ? '$ '.number_format($price,0,',','.') : '' }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="subtotal-row">
                <td colspan="2" class="num">SUBTOTALES</td>
                <td class="num">{{ $subRepair > 0 ? '$ '.number_format($subRepair, 0, ',', '.') : '' }}</td>
                <td class="num">{{ $subPaint  > 0 ? '$ '.number_format($subPaint,  0, ',', '.') : '' }}</td>
                <td class="num">{{ $subDm     > 0 ? '$ '.number_format($subDm,     0, ',', '.') : '' }}</td>
                <td class="num">{{ $subParts  > 0 ? '$ '.number_format($subParts,  0, ',', '.') : '' }}</td>
                <td class="num">{{ $subOther  > 0 ? '$ '.number_format($subOther,  0, ',', '.') : '' }}</td>
            </tr>
        </tfoot>
    </table>

    {{-- ═══ PIE: ACCIONES + TOTALES ═══ --}}
    <table class="footer-section">
        <tr>
            <td style="width:60%;">
                <div class="acciones-box">
                    <div class="title">REFERENCIAS:</div>
                    @foreach($quotation->items->map(fn($i)=>$i->unType)->unique('id') as $ut)
                    <div>{{ $ut->code }} = {{ strtoupper($ut->name) }}</div>
                    @endforeach
                </div>
                @if($quotation->notes)
                <div style="font-size:8.5px; margin-top:4px; padding:4px 6px; border:1px solid #ccc;">
                    <strong>Observaciones:</strong> {{ $quotation->notes }}
                </div>
                @endif
            </td>
            <td style="width:40%; text-align:right;">
                <div class="totals-box">
                    <table>
                        <tr>
                            <td class="lbl">SUBTOTAL</td>
                            <td class="val">$ {{ number_format($neto, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="lbl">IVA</td>
                            <td class="val">$ {{ number_format($iva, 0, ',', '.') }}</td>
                        </tr>
                        <tr class="total-row">
                            <td>TOTAL</td>
                            <td class="val">$ {{ number_format($quotation->total_amount, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>
    <div class="clearfix"></div>

    {{-- ═══ PIE LEGAL ═══ --}}
    <div class="legal">
        <div class="company-line">
            {{ strtoupper($company->name ?? 'GES_TALLER') }}
            @if($company->rut) &nbsp;&nbsp; RUT: {{ $company->rut }} @endif
        </div>
        <div>Los valores descritos están sujetos a variación y pueden ser modificados a la fecha de su reparación.
        Los ajustes según convenios y temparios serán descontados en la Orden Final.
        {{ $company->name ?? 'Ges_Taller' }} se reserva el derecho de atención.</div>
        <div class="validity">
            ************** VALIDEZ DE COTIZACIÓN {{ $company->quotation_validity_days ?? 30 }} DÍAS **************
        </div>
    </div>

</body>
</html>
