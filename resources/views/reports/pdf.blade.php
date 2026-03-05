<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Informe de Gestión {{ $from }} al {{ $to }}</title>
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
            font-size: 20px;
            font-weight: bold;
            color: #1a3c6e;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        .title-cell .doc-subtitle {
            font-size: 10px;
            color: #555;
            margin-top: 3px;
        }
        .title-cell .doc-company {
            font-size: 10px;
            color: #555;
            margin-top: 1px;
        }

        /* ─── BARRA PERÍODO ─── */
        .period-bar {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            border: 1.5px solid #1a3c6e;
        }
        .period-bar td {
            padding: 4px 8px;
            border: 1px solid #6e8ab5;
            font-size: 9px;
        }
        .period-bar .lbl { background: #1a3c6e; color: #fff; font-weight: bold; text-align: center; white-space: nowrap; }
        .period-bar .val { text-align: center; font-weight: bold; font-size: 10px; }

        /* ─── TÍTULO DE SECCIÓN ─── */
        .section-title {
            background: #1a3c6e;
            color: #fff;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            padding: 5px 8px;
            margin-bottom: 6px;
            margin-top: 12px;
        }
        .section-title .num {
            display: inline-block;
            background: rgba(255,255,255,0.2);
            border-radius: 2px;
            padding: 0 4px;
            margin-right: 4px;
        }

        /* ─── KPI CARDS (tabla 4 col) ─── */
        .kpi-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 4px;
            margin-bottom: 6px;
        }
        .kpi-table td { vertical-align: top; }
        .kpi-card {
            border: 1.5px solid #1a3c6e;
            padding: 7px 9px;
            border-radius: 3px;
            background: #f5f7fb;
        }
        .kpi-label {
            font-size: 7.5px;
            color: #1a3c6e;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
            border-bottom: 1px solid #c8d4e3;
            padding-bottom: 2px;
        }
        .kpi-value {
            font-size: 15px;
            font-weight: bold;
            color: #1a3c6e;
        }
        .kpi-value.green { color: #15803d; }
        .kpi-value.orange { color: #b45309; }
        .kpi-sub {
            font-size: 7.5px;
            color: #555;
            margin-top: 3px;
        }
        .kpi-change-up   { color: #15803d; font-size: 8px; font-weight: bold; margin-top: 2px; }
        .kpi-change-down { color: #c00;    font-size: 8px; font-weight: bold; margin-top: 2px; }

        /* ─── BARRA DE PROGRESO ─── */
        .bar-wrap { background: #dce6f4; border-radius: 3px; height: 6px; width: 100%; margin-top: 4px; }
        .bar-fill  { height: 6px; border-radius: 3px; }

        /* ─── TABLAS GENERALES ─── */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4px;
        }
        .data-table th {
            background: #1a3c6e;
            color: #fff;
            padding: 5px 6px;
            font-size: 8px;
            font-weight: bold;
            text-align: left;
            border: 1px solid #1a3c6e;
            white-space: nowrap;
        }
        .data-table th.r { text-align: right; }
        .data-table th.c { text-align: center; }
        .data-table td {
            padding: 4px 6px;
            border: 1px solid #c8d4e3;
            font-size: 9px;
            vertical-align: middle;
        }
        .data-table td.r  { text-align: right; }
        .data-table td.c  { text-align: center; }
        .data-table td.lbl { background: #e8edf5; font-weight: bold; color: #1a3c6e; white-space: nowrap; }
        .data-table tr:nth-child(even) td { background: #f5f7fb; }
        .data-table tr:nth-child(even) td.lbl { background: #dce6f4; }
        .data-table .total-row td {
            background: #dce6f4;
            font-weight: bold;
            border-top: 2px solid #1a3c6e;
        }

        /* ─── PIPELINE TABLA ─── */
        .pipeline-table {
            width: 100%;
            border-collapse: collapse;
        }
        .pipeline-table td {
            padding: 4px 6px;
            border: 1px solid #c8d4e3;
            font-size: 9px;
            vertical-align: middle;
        }
        .pipeline-table .lbl { background: #e8edf5; font-weight: bold; color: #1a3c6e; width: 90px; }
        .pipeline-table .cnt { text-align: center; font-weight: bold; width: 40px; }
        .pipeline-table .amt { text-align: right; width: 85px; }
        .pipeline-table .bar-cell { padding: 4px 8px; }

        /* ─── PIE ─── */
        .doc-footer {
            margin-top: 16px;
            border-top: 1.5px solid #1a3c6e;
            padding-top: 5px;
            font-size: 7.5px;
            color: #555;
        }
        .doc-footer .company-line {
            font-weight: bold;
            font-size: 9px;
            color: #1a3c6e;
            margin-bottom: 2px;
        }
        .confidential {
            text-align: center;
            font-weight: bold;
            font-size: 8px;
            margin-top: 5px;
            letter-spacing: 1.5px;
            color: #1a3c6e;
            border: 1px dashed #1a3c6e;
            padding: 3px 0;
        }

        /* ─── COLORES ESTADO ─── */
        .badge {
            display: inline-block;
            border-radius: 2px;
            padding: 1px 5px;
            font-size: 8px;
            font-weight: bold;
        }
        .badge-draft    { background:#fef9c3; color:#92400e; border:1px solid #fcd34d; }
        .badge-sent     { background:#e0f2fe; color:#0369a1; border:1px solid #7dd3fc; }
        .badge-approved { background:#dcfce7; color:#166534; border:1px solid #4ade80; }
        .badge-finished { background:#ede9fe; color:#4c1d95; border:1px solid #a78bfa; }
        .badge-invoiced { background:#dbeafe; color:#1e40af; border:1px solid #60a5fa; }
        .badge-rejected { background:#fee2e2; color:#991b1b; border:1px solid #f87171; }

        /* ─── EVOLUCIÓN MENSUAL ─── */
        .monthly-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        .monthly-table td {
            padding: 3px 6px;
            border: 1px solid #c8d4e3;
            font-size: 8.5px;
            vertical-align: middle;
        }
        .monthly-table .lbl { background: #e8edf5; color: #1a3c6e; font-weight: bold; width: 75px; }
        .monthly-table .bar-cell { padding: 4px 8px; }
        .monthly-table .val { text-align: right; width: 85px; font-weight: bold; color: #1a3c6e; }

        /* ─── FUNNEL RESUMEN ─── */
        .funnel-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
        }
        .funnel-table td {
            padding: 5px 8px;
            border: 1px solid #c8d4e3;
            font-size: 9px;
        }
        .funnel-table .f-lbl { background: #e8edf5; color: #1a3c6e; font-weight: bold; width: 120px; }
        .funnel-table .f-val { font-weight: bold; text-align: center; width: 50px; }
        .funnel-table .highlight td { background: #1a3c6e; color: #fff; font-weight: bold; font-size: 11px; }
    </style>
</head>
<body>

@php
    $logoPath   = $company->logo_path ? storage_path('app/public/' . $company->logo_path) : null;
    $logoExists = $logoPath && file_exists($logoPath);
    $logoBase64 = '';
    if ($logoExists) {
        $mime       = mime_content_type($logoPath);
        $logoBase64 = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($logoPath));
    }

    $pipeColors = [
        'draft'    => '#d97706',
        'sent'     => '#0369a1',
        'approved' => '#15803d',
        'finished' => '#6d28d9',
        'invoiced' => '#1d4ed8',
        'rejected' => '#b91c1c',
    ];
    $insColors = ['#1d4ed8','#15803d','#b45309','#b91c1c','#6d28d9','#0e7490'];
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
            <div class="doc-title">Informe de Gestión</div>
            <div class="doc-subtitle">Reporte Ejecutivo — Uso Interno Confidencial</div>
            <div class="doc-company">{{ $company->name ?? 'Ges Taller' }}</div>
        </td>
        <td style="width:10px;"></td>
    </tr>
</table>

{{-- ═══ BARRA PERÍODO ═══ --}}
<table class="period-bar">
    <tr>
        <td class="lbl" style="width:80px;">PERÍODO</td>
        <td class="val">{{ \Carbon\Carbon::parse($from)->format('d/m/Y') }} &mdash; {{ \Carbon\Carbon::parse($to)->format('d/m/Y') }}</td>
        <td class="lbl" style="width:90px;">FECHA EMISIÓN</td>
        <td class="val" style="width:90px;">{{ now()->format('d/m/Y H:i') }}</td>
        @if($company->rut)
        <td class="lbl" style="width:50px;">RUT</td>
        <td class="val" style="width:80px;">{{ $company->rut }}</td>
        @endif
    </tr>
    @if($company->address || $company->phone)
    <tr>
        <td class="lbl">DIRECCIÓN</td>
        <td colspan="{{ $company->rut ? 3 : 3 }}" style="font-size:8.5px;">
            {{ $company->address ?? '' }}{{ ($company->address && $company->phone) ? '  ·  Tel: ' : '' }}{{ $company->phone ?? '' }}
        </td>
        @if($company->rut)
        <td colspan="2"></td>
        @endif
    </tr>
    @endif
</table>


{{-- ══════════════════════════════════════════════════════════════════ --}}
{{-- 1. RESUMEN EJECUTIVO --}}
{{-- ══════════════════════════════════════════════════════════════════ --}}
<div class="section-title"><span class="num">1</span> Resumen Ejecutivo</div>

<table class="kpi-table">
    <tr>
        {{-- KPI 1: Ingresos facturados --}}
        <td style="width:25%;">
            <div class="kpi-card">
                <div class="kpi-label">Ingresos Facturados</div>
                <div class="kpi-value">$ {{ number_format($executive['totalRevenue'], 0, ',', '.') }}</div>
                @if($executive['revenueChange'] !== null)
                    <div class="{{ $executive['revenueChange'] >= 0 ? 'kpi-change-up' : 'kpi-change-down' }}">
                        {{ $executive['revenueChange'] >= 0 ? '▲' : '▼' }} {{ abs($executive['revenueChange']) }}% vs período ant.
                    </div>
                @else
                    <div class="kpi-sub">Sin datos período anterior</div>
                @endif
            </div>
        </td>
        {{-- KPI 2: Presupuestos emitidos --}}
        <td style="width:25%;">
            <div class="kpi-card">
                <div class="kpi-label">Presupuestos Emitidos</div>
                <div class="kpi-value">{{ $executive['totalQuotations'] }}</div>
                @if($executive['countChange'] !== null)
                    <div class="{{ $executive['countChange'] >= 0 ? 'kpi-change-up' : 'kpi-change-down' }}">
                        {{ $executive['countChange'] >= 0 ? '▲' : '▼' }} {{ abs($executive['countChange']) }}% vs período ant.
                    </div>
                @else
                    <div class="kpi-sub">Sin datos período anterior</div>
                @endif
            </div>
        </td>
        {{-- KPI 3: Ticket promedio --}}
        <td style="width:25%;">
            <div class="kpi-card">
                <div class="kpi-label">Ticket Promedio Facturado</div>
                <div class="kpi-value green">$ {{ number_format($executive['avgTicket'], 0, ',', '.') }}</div>
                <div class="kpi-sub">{{ $executive['invoicedCount'] }} facturas emitidas</div>
            </div>
        </td>
        {{-- KPI 4: Tasa de aprobación --}}
        <td style="width:25%;">
            <div class="kpi-card">
                <div class="kpi-label">Tasa de Aprobación</div>
                <div class="kpi-value {{ $executive['approvalRate'] >= 70 ? 'green' : ($executive['approvalRate'] >= 40 ? 'orange' : '') }}">
                    {{ $executive['approvalRate'] }}%
                </div>
                <div class="bar-wrap">
                    <div class="bar-fill" style="width: {{ $executive['approvalRate'] }}%; background: {{ $executive['approvalRate'] >= 70 ? '#15803d' : ($executive['approvalRate'] >= 40 ? '#b45309' : '#b91c1c') }};"></div>
                </div>
                <div class="kpi-sub">Aprobados + Terminados + Facturados</div>
            </div>
        </td>
    </tr>
</table>

{{-- Evolución mensual --}}
@if($monthlyChart->count() > 0)
@php $maxMonth = $monthlyChart->max('total') ?: 1; @endphp
<table class="monthly-table">
    <thead>
        <tr>
            <td class="lbl" style="font-size:7.5px; text-align:center; background:#1a3c6e; color:#fff; border:1px solid #1a3c6e; padding:4px;">EVOLUCIÓN MENSUAL DE INGRESOS FACTURADOS</td>
            <td class="bar-cell" style="background:#1a3c6e; border:1px solid #1a3c6e;"></td>
            <td class="val" style="background:#1a3c6e; color:#fff; border:1px solid #1a3c6e; font-size:7.5px; text-align:center; width:85px;">MONTO</td>
        </tr>
    </thead>
    <tbody>
        @foreach($monthlyChart as $m)
        <tr>
            <td class="lbl">{{ strtoupper($m['label']) }}</td>
            <td class="bar-cell">
                <div class="bar-wrap" style="height:8px;">
                    <div class="bar-fill" style="width: {{ round($m['total'] / $maxMonth * 100) }}%; height:8px; background:#1a3c6e;"></div>
                </div>
            </td>
            <td class="val">$ {{ number_format($m['total'], 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif


{{-- ══════════════════════════════════════════════════════════════════ --}}
{{-- 2. PIPELINE --}}
{{-- ══════════════════════════════════════════════════════════════════ --}}
<div class="section-title" style="margin-top:14px;"><span class="num">2</span> Pipeline de Presupuestos</div>

@php
    $maxCount  = max(collect($pipeline)->pluck('count')->max(), 1);
    $totalP    = collect($pipeline)->sum('count');
    $invoicedP = collect($pipeline)->firstWhere('key', 'invoiced')['count'] ?? 0;
    $rejectedP = collect($pipeline)->firstWhere('key', 'rejected')['count'] ?? 0;
    $activeP   = $totalP - $invoicedP - $rejectedP;
    $convP     = $totalP > 0 ? round($invoicedP / $totalP * 100) : 0;
@endphp

<table class="pipeline-table">
    <thead>
        <tr>
            <td style="background:#1a3c6e; color:#fff; font-weight:bold; font-size:8px; padding:4px 6px; width:90px; border:1px solid #1a3c6e;">ESTADO</td>
            <td style="background:#1a3c6e; color:#fff; font-weight:bold; font-size:8px; padding:4px 6px; text-align:center; width:40px; border:1px solid #1a3c6e;">CANT.</td>
            <td style="background:#1a3c6e; color:#fff; font-weight:bold; font-size:8px; padding:4px 6px; border:1px solid #1a3c6e;">DISTRIBUCIÓN</td>
            <td style="background:#1a3c6e; color:#fff; font-weight:bold; font-size:8px; padding:4px 6px; text-align:right; width:90px; border:1px solid #1a3c6e;">MONTO TOTAL</td>
            <td style="background:#1a3c6e; color:#fff; font-weight:bold; font-size:8px; padding:4px 6px; text-align:center; width:40px; border:1px solid #1a3c6e;">%</td>
        </tr>
    </thead>
    <tbody>
        @foreach($pipeline as $stage)
        <tr>
            <td class="lbl">
                <span class="badge badge-{{ $stage['key'] }}">{{ strtoupper($stage['label']) }}</span>
            </td>
            <td class="cnt">{{ $stage['count'] }}</td>
            <td class="bar-cell">
                <div class="bar-wrap" style="height:8px;">
                    <div class="bar-fill" style="width: {{ $maxCount > 0 ? round($stage['count'] / $maxCount * 100) : 0 }}%; height:8px; background:{{ $pipeColors[$stage['key']] ?? '#1a3c6e' }};"></div>
                </div>
            </td>
            <td class="amt">{{ $stage['amount'] > 0 ? '$ '.number_format($stage['amount'], 0, ',', '.') : '—' }}</td>
            <td style="text-align:center; font-size:8.5px; color:#555;">{{ $totalP > 0 ? round($stage['count'] / $totalP * 100) : 0 }}%</td>
        </tr>
        @endforeach
        <tr class="total-row">
            <td class="lbl">TOTAL</td>
            <td class="cnt">{{ $totalP }}</td>
            <td></td>
            <td class="amt">$ {{ number_format(collect($pipeline)->sum('amount'), 0, ',', '.') }}</td>
            <td style="text-align:center;">100%</td>
        </tr>
    </tbody>
</table>

{{-- Resumen embudo --}}
<table style="width:100%; border-collapse:separate; border-spacing:4px; margin-top:6px;">
    <tr>
        <td style="width:25%;">
            <div class="kpi-card" style="background:#f5f7fb;">
                <div class="kpi-label">Total Ingresados</div>
                <div class="kpi-value">{{ $totalP }}</div>
            </div>
        </td>
        <td style="width:25%;">
            <div class="kpi-card" style="background:#ede9fe;">
                <div class="kpi-label" style="color:#4c1d95;">En Proceso</div>
                <div class="kpi-value" style="color:#4c1d95;">{{ $activeP }}</div>
            </div>
        </td>
        <td style="width:25%;">
            <div class="kpi-card" style="background:#dcfce7;">
                <div class="kpi-label" style="color:#166534;">Facturados</div>
                <div class="kpi-value green">{{ $invoicedP }}</div>
            </div>
        </td>
        <td style="width:25%;">
            <div class="kpi-card" style="background:#dbeafe; border-color:#1d4ed8;">
                <div class="kpi-label" style="color:#1e40af;">Tasa de Conversión</div>
                <div class="kpi-value" style="color:#1e40af; font-size:18px;">{{ $convP }}%</div>
                <div class="kpi-sub">Presupuestos → Facturados</div>
            </div>
        </td>
    </tr>
</table>


{{-- ══════════════════════════════════════════════════════════════════ --}}
{{-- 3. INGRESOS POR ASEGURADORA --}}
{{-- ══════════════════════════════════════════════════════════════════ --}}
<div class="section-title"><span class="num">3</span> Ingresos por Aseguradora (Presupuestos Facturados)</div>

@if($byInsurance->count() > 0)
@php $grandTotalIns = $byInsurance->sum('total'); @endphp
<table class="data-table">
    <thead>
        <tr>
            <th style="width:20px;">#</th>
            <th>Compañía / Fuente</th>
            <th class="c" style="width:55px;">Facturas</th>
            <th class="r" style="width:100px;">Monto Total</th>
            <th class="r" style="width:45px;">% Part.</th>
            <th style="width:120px;">Distribución</th>
        </tr>
    </thead>
    <tbody>
        @foreach($byInsurance as $i => $ins)
        @php $pct = $grandTotalIns > 0 ? round($ins['total'] / $grandTotalIns * 100) : 0; @endphp
        <tr>
            <td class="c" style="color:#1a3c6e; font-weight:bold;">{{ $i + 1 }}</td>
            <td class="lbl">{{ $ins['name'] }}</td>
            <td class="c">{{ $ins['count'] }}</td>
            <td class="r" style="font-weight:bold; color:#1a3c6e;">$ {{ number_format($ins['total'], 0, ',', '.') }}</td>
            <td class="r">{{ $pct }}%</td>
            <td style="padding:4px 8px;">
                <div class="bar-wrap" style="height:8px;">
                    <div class="bar-fill" style="width:{{ $pct }}%; height:8px; background:{{ $insColors[$i % count($insColors)] }};"></div>
                </div>
            </td>
        </tr>
        @endforeach
        <tr class="total-row">
            <td></td>
            <td class="lbl">TOTAL GENERAL</td>
            <td class="c">{{ $byInsurance->sum('count') }}</td>
            <td class="r" style="color:#1a3c6e;">$ {{ number_format($grandTotalIns, 0, ',', '.') }}</td>
            <td class="r">100%</td>
            <td></td>
        </tr>
    </tbody>
</table>
@else
<table class="data-table"><tr><td style="text-align:center; color:#888; padding:10px;">Sin facturación en el período seleccionado.</td></tr></table>
@endif


{{-- ══════════════════════════════════════════════════════════════════ --}}
{{-- 4. RANKING DE CLIENTES --}}
{{-- ══════════════════════════════════════════════════════════════════ --}}
<div class="section-title"><span class="num">4</span> Ranking de Clientes (Aprobados, Terminados y Facturados)</div>

@if($topClients->count() > 0)
@php $maxClient = $topClients->max('total') ?: 1; @endphp
<table class="data-table">
    <thead>
        <tr>
            <th class="c" style="width:25px;">Pos.</th>
            <th>Cliente</th>
            <th style="width:90px;">RUT / DNI</th>
            <th class="c" style="width:65px;">Presupuestos</th>
            <th class="r" style="width:100px;">Monto Total</th>
            <th style="width:130px;">Participación</th>
        </tr>
    </thead>
    <tbody>
        @foreach($topClients as $i => $client)
        @php $pct = round($client['total'] / $maxClient * 100); @endphp
        <tr>
            <td class="c" style="font-weight:bold; color:#1a3c6e;">{{ $i + 1 }}°</td>
            <td class="lbl">{{ $client['name'] }}</td>
            <td style="color:#555;">{{ $client['rut'] ?: '—' }}</td>
            <td class="c">{{ $client['count'] }}</td>
            <td class="r" style="font-weight:bold; color:#1a3c6e;">$ {{ number_format($client['total'], 0, ',', '.') }}</td>
            <td style="padding:4px 8px;">
                <div class="bar-wrap" style="height:8px;">
                    <div class="bar-fill" style="width:{{ $pct }}%; height:8px; background:#1a3c6e;"></div>
                </div>
            </td>
        </tr>
        @endforeach
        <tr class="total-row">
            <td></td>
            <td class="lbl">TOTAL TOP {{ $topClients->count() }}</td>
            <td></td>
            <td class="c">{{ $topClients->sum('count') }}</td>
            <td class="r" style="color:#1a3c6e;">$ {{ number_format($topClients->sum('total'), 0, ',', '.') }}</td>
            <td></td>
        </tr>
    </tbody>
</table>
@else
<table class="data-table"><tr><td style="text-align:center; color:#888; padding:10px;">Sin datos en el período seleccionado.</td></tr></table>
@endif


{{-- ══════════════════════════════════════════════════════════════════ --}}
{{-- 5. COMPOSICIÓN: REPUESTOS VS MANO DE OBRA --}}
{{-- ══════════════════════════════════════════════════════════════════ --}}
<div class="section-title"><span class="num">5</span> Composición: Repuestos vs Mano de Obra</div>

@if($itemTypes['itemsGrandTotal'] > 0)
@php
    $pctRep = round($itemTypes['repuestoTotal'] / $itemTypes['itemsGrandTotal'] * 100);
    $pctMO  = 100 - $pctRep;
@endphp
<table class="data-table">
    <thead>
        <tr>
            <th>Categoría</th>
            <th class="r" style="width:110px;">Monto</th>
            <th class="r" style="width:50px;">% del Total</th>
            <th>Proporción</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="lbl">Mano de Obra (Reparación + Pintura + D/M)</td>
            <td class="r" style="font-weight:bold; color:#15803d;">$ {{ number_format($itemTypes['manoObraTotal'], 0, ',', '.') }}</td>
            <td class="r">{{ $pctMO }}%</td>
            <td style="padding:4px 8px;">
                <div class="bar-wrap" style="height:8px;">
                    <div class="bar-fill" style="width:{{ $pctMO }}%; height:8px; background:#15803d;"></div>
                </div>
            </td>
        </tr>
        <tr>
            <td class="lbl">Repuestos / Partes</td>
            <td class="r" style="font-weight:bold; color:#1a3c6e;">$ {{ number_format($itemTypes['repuestoTotal'], 0, ',', '.') }}</td>
            <td class="r">{{ $pctRep }}%</td>
            <td style="padding:4px 8px;">
                <div class="bar-wrap" style="height:8px;">
                    <div class="bar-fill" style="width:{{ $pctRep }}%; height:8px; background:#1a3c6e;"></div>
                </div>
            </td>
        </tr>
        <tr class="total-row">
            <td class="lbl">TOTAL BASE (Presupuestos Aprobados, Terminados y Facturados)</td>
            <td class="r">$ {{ number_format($itemTypes['itemsGrandTotal'], 0, ',', '.') }}</td>
            <td class="r">100%</td>
            <td></td>
        </tr>
    </tbody>
</table>

{{-- Barra combinada visual --}}
<table style="width:100%; border-collapse:collapse; margin-top:6px;">
    <tr>
        <td style="width:{{ $pctMO }}%; background:#15803d; height:14px; border-radius:3px 0 0 3px;"></td>
        <td style="width:{{ $pctRep }}%; background:#1a3c6e; height:14px; border-radius:0 3px 3px 0;"></td>
    </tr>
</table>
<table style="width:100%; border-collapse:collapse; margin-top:3px;">
    <tr>
        <td style="font-size:7.5px; color:#15803d; font-weight:bold;">&#9632; Mano de Obra {{ $pctMO }}%</td>
        <td style="font-size:7.5px; color:#1a3c6e; font-weight:bold; text-align:right;">&#9632; Repuestos {{ $pctRep }}%</td>
    </tr>
</table>
@else
<table class="data-table"><tr><td style="text-align:center; color:#888; padding:10px;">Sin datos en el período seleccionado.</td></tr></table>
@endif


{{-- ═══ PIE DEL DOCUMENTO ═══ --}}
<div class="doc-footer">
    <div class="company-line">
        {{ strtoupper($company->name ?? 'GES TALLER') }}
        @if($company->rut) &nbsp;·&nbsp; RUT: {{ $company->rut }} @endif
        @if($company->address) &nbsp;·&nbsp; {{ $company->address }} @endif
        @if($company->phone) &nbsp;·&nbsp; {{ $company->phone }} @endif
    </div>
    <div>Este informe fue generado automáticamente por el sistema Ges_Taller el {{ now()->format('d/m/Y \a \l\a\s H:i') }}.
    Los datos reflejan el período {{ \Carbon\Carbon::parse($from)->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($to)->format('d/m/Y') }}.</div>
    <div class="confidential">&#9733; DOCUMENTO CONFIDENCIAL — USO INTERNO &#9733;</div>
</div>

</body>
</html>
