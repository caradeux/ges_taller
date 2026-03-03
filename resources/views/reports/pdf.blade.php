<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Ges_Taller {{ $from }} al {{ $to }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #1e293b;
            background: #fff;
        }

        /* ── Header ─────────────────────────────────────────────────────────── */
        .header {
            background: #0f172a;
            color: white;
            padding: 20px 24px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        .header .brand { font-size: 18px; font-weight: 700; }
        .header .period { font-size: 9px; color: #94a3b8; margin-top: 4px; }
        .header .generated { font-size: 9px; color: #94a3b8; text-align: right; }

        /* ── Secciones ───────────────────────────────────────────────────────── */
        .section { margin-bottom: 22px; page-break-inside: avoid; }
        .section-title {
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #64748b;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }

        /* ── KPI Cards ───────────────────────────────────────────────────────── */
        .kpi-grid { display: flex; gap: 8px; }
        .kpi-card {
            flex: 1;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 10px 12px;
        }
        .kpi-label { font-size: 8px; color: #64748b; font-weight: 600; margin-bottom: 4px; }
        .kpi-value { font-size: 16px; font-weight: 700; color: #1e293b; }
        .kpi-value.blue  { color: #2563eb; }
        .kpi-value.green { color: #10b981; }
        .kpi-sub { font-size: 8px; color: #94a3b8; margin-top: 3px; }
        .kpi-change-up   { color: #10b981; font-size: 8px; font-weight: 600; }
        .kpi-change-down { color: #ef4444; font-size: 8px; font-weight: 600; }

        /* ── Progress Bar ────────────────────────────────────────────────────── */
        .bar-wrap { background: #f1f5f9; border-radius: 4px; height: 7px; width: 100%; }
        .bar-fill { height: 7px; border-radius: 4px; }

        /* ── Tablas ──────────────────────────────────────────────────────────── */
        table { width: 100%; border-collapse: collapse; }
        th {
            background: #f8fafc;
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            padding: 6px 8px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }
        td {
            padding: 6px 8px;
            border-bottom: 1px solid #f8fafc;
            font-size: 9px;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .fw-bold { font-weight: 700; }
        .text-blue { color: #2563eb; }
        .text-green { color: #10b981; }
        .text-secondary { color: #64748b; }

        /* ── Pipeline ────────────────────────────────────────────────────────── */
        .pipeline-row { display: flex; align-items: center; gap: 8px; padding: 5px 0; border-bottom: 1px solid #f8fafc; }
        .pipeline-label { width: 70px; font-size: 9px; font-weight: 600; }
        .pipeline-bar-wrap { flex: 1; background: #f1f5f9; border-radius: 4px; height: 8px; }
        .pipeline-bar { height: 8px; border-radius: 4px; }
        .pipeline-count { width: 25px; text-align: right; font-weight: 700; font-size: 9px; }
        .pipeline-amount { width: 70px; text-align: right; font-size: 8px; color: #64748b; }

        /* ── Donut simulado (cuadros de color) ───────────────────────────────── */
        .legend-item { display: flex; align-items: center; gap: 6px; margin-bottom: 5px; }
        .legend-dot { width: 10px; height: 10px; border-radius: 2px; flex-shrink: 0; }

        /* ── 2 columnas ──────────────────────────────────────────────────────── */
        .two-col { display: flex; gap: 12px; }
        .two-col .col-left  { flex: 0 0 48%; }
        .two-col .col-right { flex: 1; }

        /* ── Embudo resumen ──────────────────────────────────────────────────── */
        .funnel-row {
            display: flex; justify-content: space-between; align-items: center;
            padding: 5px 8px; border-radius: 4px; margin-bottom: 4px; font-size: 9px;
        }

        /* ── Footer ──────────────────────────────────────────────────────────── */
        .footer {
            text-align: center;
            font-size: 8px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 8px;
            margin-top: 24px;
        }

        /* ── Mensual tabla ───────────────────────────────────────────────────── */
        .monthly-bar-row { display: flex; align-items: center; gap: 8px; margin-bottom: 5px; }
        .monthly-label { width: 70px; font-size: 8px; color: #64748b; }
        .monthly-bar-wrap { flex: 1; background: #f1f5f9; border-radius: 3px; height: 14px; position: relative; }
        .monthly-bar-fill { height: 14px; border-radius: 3px; background: #2563eb; }
        .monthly-val { width: 80px; text-align: right; font-size: 8px; font-weight: 700; color: #2563eb; }
    </style>
</head>
<body>

{{-- Header --}}
<div class="header">
    <div>
        <div class="brand">&#9632; Ges_Taller</div>
        <div class="period">Período: {{ \Carbon\Carbon::parse($from)->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($to)->format('d/m/Y') }}</div>
    </div>
    <div class="generated">
        Generado el {{ now()->format('d/m/Y H:i') }}<br>
        Reporte de Gestión — Confidencial
    </div>
</div>

{{-- ══════════════════════════════════════ --}}
{{-- 1. RESUMEN EJECUTIVO --}}
{{-- ══════════════════════════════════════ --}}
<div class="section">
    <div class="section-title">1. Resumen Ejecutivo</div>
    <div class="kpi-grid">
        <div class="kpi-card">
            <div class="kpi-label">Ingresos Facturados</div>
            <div class="kpi-value blue">${{ number_format($executive['totalRevenue'], 0, ',', '.') }}</div>
            @if($executive['revenueChange'] !== null)
                <div class="{{ $executive['revenueChange'] >= 0 ? 'kpi-change-up' : 'kpi-change-down' }}">
                    {{ $executive['revenueChange'] >= 0 ? '▲' : '▼' }} {{ abs($executive['revenueChange']) }}% vs período anterior
                </div>
            @else
                <div class="kpi-sub">Sin datos previos</div>
            @endif
        </div>
        <div class="kpi-card">
            <div class="kpi-label">Presupuestos Emitidos</div>
            <div class="kpi-value">{{ $executive['totalQuotations'] }}</div>
            @if($executive['countChange'] !== null)
                <div class="{{ $executive['countChange'] >= 0 ? 'kpi-change-up' : 'kpi-change-down' }}">
                    {{ $executive['countChange'] >= 0 ? '▲' : '▼' }} {{ abs($executive['countChange']) }}% vs período anterior
                </div>
            @else
                <div class="kpi-sub">Sin datos previos</div>
            @endif
        </div>
        <div class="kpi-card">
            <div class="kpi-label">Ticket Promedio Facturado</div>
            <div class="kpi-value green">${{ number_format($executive['avgTicket'], 0, ',', '.') }}</div>
            <div class="kpi-sub">{{ $executive['invoicedCount'] }} facturas emitidas</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-label">Tasa de Aprobación</div>
            <div class="kpi-value {{ $executive['approvalRate'] >= 70 ? 'green' : '' }}">{{ $executive['approvalRate'] }}%</div>
            <div style="margin-top: 5px;">
                <div class="bar-wrap">
                    <div class="bar-fill" style="width: {{ $executive['approvalRate'] }}%; background: {{ $executive['approvalRate'] >= 70 ? '#10b981' : ($executive['approvalRate'] >= 40 ? '#f59e0b' : '#ef4444') }};"></div>
                </div>
            </div>
        </div>
    </div>

    @if($monthlyChart->count() > 0)
        <div style="margin-top: 12px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 12px;">
            <div class="kpi-label" style="margin-bottom: 8px;">Evolución de Ingresos Mensuales (Facturados)</div>
            @php $maxMonth = $monthlyChart->max('total') ?: 1; @endphp
            @foreach($monthlyChart as $m)
                <div class="monthly-bar-row">
                    <div class="monthly-label">{{ $m['label'] }}</div>
                    <div class="monthly-bar-wrap">
                        <div class="monthly-bar-fill" style="width: {{ round($m['total'] / $maxMonth * 100) }}%"></div>
                    </div>
                    <div class="monthly-val">${{ number_format($m['total'], 0, ',', '.') }}</div>
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- ══════════════════════════════════════ --}}
{{-- 2. PIPELINE --}}
{{-- ══════════════════════════════════════ --}}
<div class="section">
    <div class="section-title">2. Pipeline de Presupuestos</div>
    <div class="two-col">
        <div class="col-left">
            @php
                $maxCount = max(collect($pipeline)->pluck('count')->max(), 1);
                $pipeColors = [
                    'draft'    => '#fbbf24',
                    'sent'     => '#38bdf8',
                    'approved' => '#34d399',
                    'finished' => '#818cf8',
                    'invoiced' => '#2563eb',
                    'rejected' => '#f87171',
                ];
            @endphp
            @foreach($pipeline as $stage)
                <div class="pipeline-row">
                    <div class="pipeline-label">{{ $stage['label'] }}</div>
                    <div class="pipeline-bar-wrap">
                        <div class="pipeline-bar"
                            style="width: {{ round($stage['count'] / $maxCount * 100) }}%; background: {{ $pipeColors[$stage['key']] ?? '#94a3b8' }}">
                        </div>
                    </div>
                    <div class="pipeline-count">{{ $stage['count'] }}</div>
                    <div class="pipeline-amount">${{ number_format($stage['amount'], 0, ',', '.') }}</div>
                </div>
            @endforeach
        </div>
        <div class="col-right">
            @php
                $totalP    = collect($pipeline)->sum('count');
                $invoicedP = collect($pipeline)->firstWhere('key', 'invoiced')['count'] ?? 0;
                $rejectedP = collect($pipeline)->firstWhere('key', 'rejected')['count'] ?? 0;
                $activeP   = $totalP - $invoicedP - $rejectedP;
                $convP     = $totalP > 0 ? round($invoicedP / $totalP * 100) : 0;
            @endphp
            <div class="funnel-row" style="background: #f1f5f9;">
                <span>Total ingresados</span><span class="fw-bold">{{ $totalP }}</span>
            </div>
            <div class="funnel-row" style="background: #ede9fe; color: #4c1d95;">
                <span>En proceso</span><span class="fw-bold">{{ $activeP }}</span>
            </div>
            <div class="funnel-row" style="background: #dcfce7; color: #166534;">
                <span>Facturados</span><span class="fw-bold">{{ $invoicedP }}</span>
            </div>
            <div class="funnel-row" style="background: #fee2e2; color: #991b1b;">
                <span>Rechazados</span><span class="fw-bold">{{ $rejectedP }}</span>
            </div>
            <div class="funnel-row" style="background: #2563eb; color: white;">
                <span style="font-weight:700;">Tasa de conversión</span>
                <span style="font-size:14px; font-weight:700;">{{ $convP }}%</span>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════ --}}
{{-- 3. ASEGURADORAS --}}
{{-- ══════════════════════════════════════ --}}
<div class="section">
    <div class="section-title">3. Ingresos por Aseguradora (Presupuestos Facturados)</div>
    @if($byInsurance->count() > 0)
        @php
            $grandTotalIns = $byInsurance->sum('total');
            $insColors = ['#2563eb','#10b981','#f59e0b','#ef4444','#8b5cf6','#64748b'];
        @endphp
        <div class="two-col">
            <div class="col-left">
                @foreach($byInsurance as $i => $ins)
                    <div style="display:flex; align-items:center; gap:8px; margin-bottom:6px;">
                        <div style="width:10px; height:10px; border-radius:2px; background:{{ $insColors[$i % count($insColors)] }}; flex-shrink:0;"></div>
                        <div style="flex:1; font-size:9px;">{{ $ins['name'] }}</div>
                        <div style="width:80px;">
                            <div class="bar-wrap">
                                <div class="bar-fill" style="width:{{ $grandTotalIns > 0 ? round($ins['total']/$grandTotalIns*100) : 0 }}%; background:{{ $insColors[$i % count($insColors)] }};"></div>
                            </div>
                        </div>
                        <div style="width:30px; text-align:right; font-size:8px; font-weight:700;">
                            {{ $grandTotalIns > 0 ? round($ins['total']/$grandTotalIns*100) : 0 }}%
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="col-right">
                <table>
                    <thead>
                        <tr>
                            <th>Fuente</th>
                            <th class="text-center">Facturas</th>
                            <th class="text-right">Total</th>
                            <th class="text-right">% Part.</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($byInsurance as $ins)
                            <tr>
                                <td class="fw-bold">{{ $ins['name'] }}</td>
                                <td class="text-center text-secondary">{{ $ins['count'] }}</td>
                                <td class="text-right fw-bold text-blue">${{ number_format($ins['total'], 0, ',', '.') }}</td>
                                <td class="text-right">{{ $grandTotalIns > 0 ? round($ins['total']/$grandTotalIns*100) : 0 }}%</td>
                            </tr>
                        @endforeach
                        <tr style="border-top: 2px solid #e2e8f0; font-weight:700;">
                            <td>TOTAL</td>
                            <td class="text-center">{{ $byInsurance->sum('count') }}</td>
                            <td class="text-right text-blue">${{ number_format($grandTotalIns, 0, ',', '.') }}</td>
                            <td class="text-right">100%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <p class="text-secondary" style="font-size:9px;">Sin facturación en el período seleccionado.</p>
    @endif
</div>

{{-- ══════════════════════════════════════ --}}
{{-- 4. RANKING DE CLIENTES --}}
{{-- ══════════════════════════════════════ --}}
<div class="section">
    <div class="section-title">4. Ranking de Clientes (Presupuestos Aprobados, Terminados y Facturados)</div>
    @if($topClients->count() > 0)
        @php $maxClient = $topClients->max('total') ?: 1; @endphp
        <table>
            <thead>
                <tr>
                    <th style="width:25px;">#</th>
                    <th>Cliente</th>
                    <th>RUT</th>
                    <th class="text-center">Presupuestos</th>
                    <th class="text-right">Monto Total</th>
                    <th style="width:120px;"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($topClients as $i => $client)
                    <tr>
                        <td class="fw-bold text-secondary">{{ $i + 1 }}°</td>
                        <td class="fw-bold">{{ $client['name'] }}</td>
                        <td class="text-secondary">{{ $client['rut'] }}</td>
                        <td class="text-center">{{ $client['count'] }}</td>
                        <td class="text-right fw-bold text-blue">${{ number_format($client['total'], 0, ',', '.') }}</td>
                        <td>
                            <div class="bar-wrap">
                                <div class="bar-fill" style="width:{{ round($client['total']/$maxClient*100) }}%; background:#2563eb;"></div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="text-secondary" style="font-size:9px;">Sin datos en el período seleccionado.</p>
    @endif
</div>

{{-- ══════════════════════════════════════ --}}
{{-- 5. REPUESTOS VS MANO DE OBRA --}}
{{-- ══════════════════════════════════════ --}}
<div class="section">
    <div class="section-title">5. Composición: Repuestos vs Mano de Obra</div>
    @if($itemTypes['itemsGrandTotal'] > 0)
        @php
            $pctRep = round($itemTypes['repuestoTotal'] / $itemTypes['itemsGrandTotal'] * 100);
            $pctMO  = 100 - $pctRep;
        @endphp
        <div class="two-col">
            <div class="col-left">
                <div class="kpi-card" style="margin-bottom: 8px; border-left: 3px solid #2563eb;">
                    <div class="kpi-label">Repuestos</div>
                    <div class="kpi-value blue">${{ number_format($itemTypes['repuestoTotal'], 0, ',', '.') }}</div>
                    <div class="kpi-sub">{{ $itemTypes['repuestoCount'] }} líneas · {{ $pctRep }}% del total</div>
                    <div style="margin-top: 5px;">
                        <div class="bar-wrap"><div class="bar-fill" style="width:{{ $pctRep }}%; background:#2563eb;"></div></div>
                    </div>
                </div>
                <div class="kpi-card" style="border-left: 3px solid #10b981;">
                    <div class="kpi-label">Mano de Obra</div>
                    <div class="kpi-value green">${{ number_format($itemTypes['manoObraTotal'], 0, ',', '.') }}</div>
                    <div class="kpi-sub">{{ $itemTypes['manoObraCount'] }} líneas · {{ $pctMO }}% del total</div>
                    <div style="margin-top: 5px;">
                        <div class="bar-wrap"><div class="bar-fill" style="width:{{ $pctMO }}%; background:#10b981;"></div></div>
                    </div>
                </div>
            </div>
            <div class="col-right">
                <div class="kpi-card" style="border-left: 3px solid #64748b; height: 100%;">
                    <div class="kpi-label">Total de Ítems (Base Neta)</div>
                    <div class="kpi-value" style="font-size: 20px;">${{ number_format($itemTypes['itemsGrandTotal'], 0, ',', '.') }}</div>
                    <div class="kpi-sub" style="margin-top:8px;">{{ $itemTypes['repuestoCount'] + $itemTypes['manoObraCount'] }} líneas en total</div>
                    <div class="kpi-sub">En presupuestos aprobados, terminados y facturados</div>

                    <div style="margin-top: 14px;">
                        <div style="display:flex; gap:0; height: 16px; border-radius:4px; overflow:hidden;">
                            <div style="width:{{ $pctRep }}%; background:#2563eb;"></div>
                            <div style="width:{{ $pctMO }}%; background:#10b981;"></div>
                        </div>
                        <div style="display:flex; justify-content:space-between; margin-top:5px;">
                            <div class="legend-item">
                                <div class="legend-dot" style="background:#2563eb;"></div>
                                <span style="font-size:8px;">Repuestos {{ $pctRep }}%</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-dot" style="background:#10b981;"></div>
                                <span style="font-size:8px;">Mano de Obra {{ $pctMO }}%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <p class="text-secondary" style="font-size:9px;">Sin datos en el período seleccionado.</p>
    @endif
</div>

<div class="footer">
    Ges_Taller &bull; Sistema de Gestión de Taller &bull; Reporte generado el {{ now()->format('d/m/Y \a \l\a\s H:i') }}
</div>

</body>
</html>
