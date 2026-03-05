<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Cotización #{{ $quotation->folio }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 9.5px;
            color: #1a1a1a;
            padding: 16px 20px;
        }

        /* ─── HEADER CON LOGO ─── */
        .doc-header {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
        }
        .doc-header td { vertical-align: middle; padding: 0; }
        .logo-cell { width: 140px; }
        .logo-cell img { max-width: 130px; max-height: 70px; object-fit: contain; }
        .logo-placeholder {
            width: 130px; height: 60px;
            border: 2px solid #1a3c6e;
            border-radius: 4px;
            display: inline-block;
            text-align: center;
            line-height: 60px;
            font-size: 18px;
            font-weight: bold;
            color: #1a3c6e;
            letter-spacing: 2px;
        }
        .title-cell { text-align: center; }
        .title-cell .doc-title {
            font-size: 22px;
            font-weight: bold;
            color: #1a3c6e;
            letter-spacing: 3px;
            text-transform: uppercase;
        }
        .title-cell .company-sub {
            font-size: 10px;
            color: #555;
            margin-top: 2px;
        }

        /* ─── BARRA FOLIO/FECHA ─── */
        .folio-bar {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
            border: 1.5px solid #1a3c6e;
        }
        .folio-bar td {
            padding: 4px 8px;
            border: 1px solid #6e8ab5;
            font-size: 9px;
        }
        .folio-bar .lbl { background: #1a3c6e; color: #fff; font-weight: bold; text-align: center; white-space: nowrap; }
        .folio-bar .val { text-align: center; font-weight: bold; font-size: 12px; }
        .folio-bar .val-date { text-align: center; font-weight: bold; font-size: 11px; }

        /* ─── INFO CLIENTE / VEHÍCULO ─── */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
            border: 1px solid #6e8ab5;
        }
        .info-table td {
            padding: 2.5px 5px;
            border: 1px solid #aab8d0;
            font-size: 9px;
            vertical-align: middle;
        }
        .info-table .lbl {
            background: #e8edf5;
            font-weight: bold;
            color: #1a3c6e;
            white-space: nowrap;
            width: 95px;
        }
        .info-table .lbl-sm {
            background: #e8edf5;
            font-weight: bold;
            color: #1a3c6e;
            white-space: nowrap;
            width: 75px;
        }

        /* ─── TABLA DE ÍTEMS ─── */
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
            font-size: 8.5px;
            border: 1px solid #1a3c6e;
            font-weight: bold;
            white-space: nowrap;
        }
        .items-table th.left { text-align: left; padding-left: 6px; }
        .items-table td {
            border: 1px solid #c8d4e3;
            padding: 3px 4px;
            font-size: 9px;
            vertical-align: middle;
        }
        .items-table td.num { text-align: right; }
        .items-table td.center { text-align: center; }
        .items-table tr:nth-child(even) td { background: #f5f7fb; }
        .items-table .subtotal-row td {
            background: #dce6f4;
            font-weight: bold;
            border-top: 2px solid #1a3c6e;
            font-size: 9px;
        }
        .items-table .subtotal-row td.num { text-align: right; }
        .action-badge {
            display: inline-block;
            background: #e0e7f3;
            border: 1px solid #a0b4d0;
            border-radius: 2px;
            padding: 1px 4px;
            font-weight: bold;
            font-size: 8px;
            color: #1a3c6e;
        }
        .salvar-badge {
            font-size: 7.5px;
            color: #c00;
            font-weight: bold;
            background: #fff0f0;
            border: 1px solid #f00;
            border-radius: 2px;
            padding: 1px 3px;
        }

        /* ─── PIE: REFERENCIAS + TOTALES ─── */
        .footer-grid {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }
        .footer-grid td { vertical-align: top; }

        .refs-box {
            border: 1px solid #a0b4d0;
            padding: 5px 8px;
            font-size: 8px;
            margin-right: 8px;
        }
        .refs-box .title {
            font-weight: bold;
            color: #1a3c6e;
            text-decoration: underline;
            margin-bottom: 3px;
            font-size: 8.5px;
        }
        .refs-box .ref-line { margin-bottom: 1px; }

        .obs-box {
            border: 1px solid #a0b4d0;
            padding: 5px 8px;
            font-size: 8px;
            margin-top: 5px;
            margin-right: 8px;
        }
        .obs-box strong { color: #1a3c6e; }

        .totals-box {
            border: 1.5px solid #1a3c6e;
            width: 210px;
        }
        .totals-box table { width: 100%; border-collapse: collapse; }
        .totals-box td { padding: 4px 8px; font-size: 9.5px; border: 1px solid #aab8d0; }
        .totals-box .t-lbl { background: #e8edf5; font-weight: bold; color: #1a3c6e; }
        .totals-box .t-val { text-align: right; font-weight: bold; }
        .totals-box .total-row td {
            background: #1a3c6e;
            color: #fff;
            font-size: 12px;
            font-weight: bold;
        }
        .totals-box .total-row td.t-val { color: #fff; }

        /* ─── FIRMA ─── */
        .sign-area {
            margin-top: 20px;
            width: 100%;
            border-collapse: collapse;
        }
        .sign-area td { text-align: center; font-size: 8.5px; color: #444; }
        .sign-line { border-top: 1px solid #333; width: 150px; margin: 0 auto 3px; }

        /* ─── PIE LEGAL ─── */
        .legal {
            margin-top: 10px;
            border-top: 1.5px solid #1a3c6e;
            padding-top: 5px;
            font-size: 7.5px;
            color: #555;
        }
        .legal .company-line {
            font-weight: bold;
            font-size: 9px;
            color: #1a3c6e;
            margin-bottom: 2px;
        }
        .validity {
            text-align: center;
            font-weight: bold;
            font-size: 8.5px;
            margin-top: 5px;
            letter-spacing: 1.5px;
            color: #1a3c6e;
            border: 1px dashed #1a3c6e;
            padding: 3px 0;
        }

        .clearfix::after { content: ''; display: table; clear: both; }
    </style>
</head>
<body>

    @php
        $logoPath = $company->logo_path
            ? storage_path('app/public/' . $company->logo_path)
            : null;
        $logoExists = $logoPath && file_exists($logoPath);
        $logoBase64 = '';
        if ($logoExists) {
            $mime = mime_content_type($logoPath);
            $logoBase64 = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($logoPath));
        }
    @endphp

    {{-- ═══ ENCABEZADO ═══ --}}
    <table class="doc-header">
        <tr>
            <td class="logo-cell">
                @if($logoBase64)
                    <img src="{{ $logoBase64 }}" alt="Logo">
                @else
                    <div class="logo-placeholder">{{ strtoupper(substr($company->name ?? 'GT', 0, 3)) }}</div>
                @endif
            </td>
            <td class="title-cell">
                <div class="doc-title">Hoja de Presupuesto</div>
                <div class="company-sub">{{ $company->name ?? 'Ges Taller' }}</div>
            </td>
            <td style="width:10px;"></td>
        </tr>
    </table>

    {{-- ═══ FOLIO / FECHA ═══ --}}
    <table class="folio-bar">
        <tr>
            <td class="lbl" style="width:90px;">DIRECCIÓN</td>
            <td style="min-width:180px;">{{ $company->address ?? '' }}</td>
            <td class="lbl" style="width:105px;">PRESUPUESTO Nº</td>
            <td class="val" style="width:55px;">{{ $quotation->folio }}</td>
            <td class="lbl" style="width:45px;">FECHA</td>
            <td class="val-date" style="width:72px;">{{ \Carbon\Carbon::parse($quotation->date)->format('d-M-y') }}</td>
        </tr>
        @if($company->phone || $company->email)
        <tr>
            <td class="lbl">TELÉFONO / EMAIL</td>
            <td colspan="5" style="font-size:8.5px;">
                {{ $company->phone ?? '' }}{{ ($company->phone && $company->email) ? '  ·  ' : '' }}{{ $company->email ?? '' }}
            </td>
        </tr>
        @endif
    </table>

    {{-- ═══ INFO CLIENTE / VEHÍCULO ═══ --}}
    <table class="info-table">
        <tr>
            <td class="lbl">ASEGURADO</td>
            <td style="width:160px;">{{ $quotation->client->name }}</td>
            <td class="lbl-sm">MODELO</td>
            <td style="width:110px;">{{ $quotation->vehicle->brand }} {{ $quotation->vehicle->model }}</td>
            <td class="lbl-sm" style="width:70px;">Nº SINIESTRO</td>
            <td>{{ $quotation->claim_number ?? '' }}</td>
        </tr>
        <tr>
            <td class="lbl">RUT / DNI</td>
            <td>{{ $quotation->client->rut_dni ?? '' }}</td>
            <td class="lbl-sm">AÑO</td>
            <td>{{ $quotation->vehicle->year ?? '' }}</td>
            <td class="lbl-sm">Nº INGRESO</td>
            <td>{{ $quotation->intake_number ?? '' }}</td>
        </tr>
        <tr>
            <td class="lbl">TELÉFONO</td>
            <td>{{ $quotation->client->phone ?? '' }}</td>
            <td class="lbl-sm">COLOR</td>
            <td>{{ $quotation->vehicle->color ?? '' }}</td>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td class="lbl">CIA. DE SEGUROS</td>
            <td>{{ $quotation->insuranceCompany?->name ?? 'Particular' }}</td>
            <td class="lbl-sm">PATENTE</td>
            <td>{{ strtoupper($quotation->vehicle->license_plate) }}</td>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td class="lbl">LIQUIDADOR</td>
            <td>{{ $quotation->liquidator?->name ?? '' }}</td>
            <td class="lbl-sm">Nº CHASIS</td>
            <td>{{ $quotation->vehicle->vin_chassis ?? '' }}</td>
            <td colspan="2"></td>
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
                <th style="width:32px;">UN</th>
                <th class="left">DESCRIPCIÓN DE TRABAJOS</th>
                <th style="width:75px;">REPARACIÓN</th>
                <th style="width:68px;">PINTURA</th>
                <th style="width:60px;">D/M</th>
                <th style="width:75px;">VALOR RPTO</th>
                <th style="width:60px;">OTROS</th>
            </tr>
        </thead>
        <tbody>
            @forelse($quotation->items as $item)
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
            @empty
            <tr>
                <td colspan="7" style="text-align:center; color:#888; padding:8px;">Sin ítems registrados.</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="subtotal-row">
                <td colspan="2" style="text-align:right; padding-right:8px;">SUBTOTALES</td>
                <td class="num">{{ $subRepair > 0 ? '$ '.number_format($subRepair,0,',','.') : '' }}</td>
                <td class="num">{{ $subPaint  > 0 ? '$ '.number_format($subPaint, 0,',','.') : '' }}</td>
                <td class="num">{{ $subDm     > 0 ? '$ '.number_format($subDm,    0,',','.') : '' }}</td>
                <td class="num">{{ $subParts  > 0 ? '$ '.number_format($subParts, 0,',','.') : '' }}</td>
                <td class="num">{{ $subOther  > 0 ? '$ '.number_format($subOther, 0,',','.') : '' }}</td>
            </tr>
        </tfoot>
    </table>

    {{-- ═══ PIE: REFERENCIAS + TOTALES ═══ --}}
    <table class="footer-grid">
        <tr>
            <td style="width:58%;">
                <div class="refs-box">
                    <div class="title">REFERENCIAS:</div>
                    @foreach($quotation->items->map(fn($i)=>$i->unType)->unique('id') as $ut)
                    <div class="ref-line">{{ $ut->code }} = {{ strtoupper($ut->name) }}</div>
                    @endforeach
                </div>
                @if($quotation->notes)
                <div class="obs-box">
                    <strong>Observaciones:</strong> {{ $quotation->notes }}
                </div>
                @endif
            </td>
            <td style="width:42%; text-align:right; vertical-align:bottom;">
                <div class="totals-box">
                    <table>
                        @if($quotation->deductible_amount > 0)
                        <tr>
                            <td class="t-lbl">DEDUCIBLE</td>
                            <td class="t-val">$ {{ number_format($quotation->deductible_amount, 0, ',', '.') }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td class="t-lbl">NETO</td>
                            <td class="t-val">$ {{ number_format($neto, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="t-lbl">IVA (19%)</td>
                            <td class="t-val">$ {{ number_format($iva, 0, ',', '.') }}</td>
                        </tr>
                        <tr class="total-row">
                            <td><strong>TOTAL</strong></td>
                            <td class="t-val"><strong>$ {{ number_format($quotation->total_amount, 0, ',', '.') }}</strong></td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>

    {{-- ═══ FIRMA ═══ --}}
    <table class="sign-area" style="margin-top:22px;">
        <tr>
            <td style="width:33%;">
                <div class="sign-line"></div>
                Firma Cliente
            </td>
            <td style="width:33%;">
                <div class="sign-line"></div>
                Encargado Taller
            </td>
            <td style="width:33%;">
                <div class="sign-line"></div>
                Liquidador / Aprobación
            </td>
        </tr>
    </table>

    {{-- ═══ PIE LEGAL ═══ --}}
    <div class="legal">
        <div class="company-line">
            {{ strtoupper($company->name ?? 'GES_TALLER') }}
            @if($company->rut) &nbsp;·&nbsp; RUT: {{ $company->rut }} @endif
            @if($company->address) &nbsp;·&nbsp; {{ $company->address }} @endif
            @if($company->phone) &nbsp;·&nbsp; {{ $company->phone }} @endif
        </div>
        <div>Los valores descritos están sujetos a variación y pueden ser modificados a la fecha de su reparación.
        Los ajustes según convenios y temparios serán descontados en la Orden Final.
        {{ $company->name ?? 'GesTaller' }} se reserva el derecho de atención.</div>
        <div class="validity">
            ★ VALIDEZ DE COTIZACIÓN: {{ $company->quotation_validity_days ?? 30 }} DÍAS ★
        </div>
    </div>

</body>
</html>
