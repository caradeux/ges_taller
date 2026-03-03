@extends('layouts.app')

@section('title', 'Reportes')

@section('styles')
<style>
    .report-section {
        margin-bottom: 2.5rem;
    }
    .report-section-title {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #94a3b8;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .report-section-title::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #f1f5f9;
    }
    .kpi-card {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
    .kpi-card .kpi-value {
        font-size: 1.75rem;
        font-weight: 700;
        font-family: 'Outfit', sans-serif;
        line-height: 1.1;
        margin: 0.25rem 0;
    }
    .kpi-card .kpi-label {
        font-size: 0.75rem;
        color: #64748b;
        font-weight: 500;
    }
    .kpi-card .kpi-change {
        font-size: 0.72rem;
        font-weight: 600;
        margin-top: 0.5rem;
    }
    .change-up   { color: #10b981; }
    .change-down { color: #ef4444; }
    .change-neutral { color: #94a3b8; }
    .pipeline-row {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f8fafc;
    }
    .pipeline-row:last-child { border-bottom: none; }
    .pipeline-bar-wrap {
        flex: 1;
        background: #f1f5f9;
        border-radius: 99px;
        height: 8px;
        overflow: hidden;
    }
    .pipeline-bar {
        height: 100%;
        border-radius: 99px;
    }
    .preset-btn {
        font-size: 0.72rem;
        padding: 0.3rem 0.8rem;
        border-radius: 99px;
    }

    @media print {
        .sidebar, .main-content > header, .no-print { display: none !important; }
        .main-content { margin-left: 0 !important; padding: 1rem !important; }
        .card { box-shadow: none !important; border: 1px solid #e2e8f0 !important; }
        .report-section { page-break-inside: avoid; }
        .row { display: flex; flex-wrap: wrap; }
        .col-md-3, .col-md-4, .col-md-5, .col-md-7, .col-md-8 { flex: 1; min-width: 0; }
        canvas { max-height: 200px !important; }
        body { font-size: 11px; }
        h2 { font-size: 18px; }
    }
</style>
@endsection

@section('content')
<div class="animate-in">

    {{-- Header + Filtro de Período --}}
    <div class="d-flex justify-content-between align-items-start mb-5">
        <div>
            <h2 class="fw-bold mb-1">Reportes</h2>
            <p class="text-secondary small mb-0">Análisis de gestión para socios y dirección.</p>
            <div class="d-flex gap-2 mt-3 no-print">
                <a href="{{ route('reports.pdf', ['from' => $from, 'to' => $to]) }}"
                    class="btn btn-danger btn-sm px-3" target="_blank">
                    <i class="bi bi-file-earmark-pdf"></i> Descargar PDF
                </a>
                <button onclick="window.print()" class="btn btn-outline-secondary btn-sm px-3">
                    <i class="bi bi-printer"></i> Imprimir
                </button>
            </div>
        </div>

        <div class="card p-3 no-print" style="min-width: 420px;">
            <form action="{{ route('reports.index') }}" method="GET" class="d-flex gap-2 align-items-end flex-wrap">
                <div>
                    <label class="form-label small fw-semibold mb-1">Desde</label>
                    <input type="date" name="from" class="form-control form-control-sm" id="inputFrom"
                        value="{{ $from }}">
                </div>
                <div>
                    <label class="form-label small fw-semibold mb-1">Hasta</label>
                    <input type="date" name="to" class="form-control form-control-sm" id="inputTo"
                        value="{{ $to }}">
                </div>
                @if(auth()->user()->role === 'admin' && $branches->count() > 0)
                <div>
                    <label class="form-label small fw-semibold mb-1">Sucursal</label>
                    <select name="branch_id" class="form-select form-select-sm">
                        <option value="">Todas</option>
                        @foreach($branches as $b)
                            <option value="{{ $b->id }}" {{ $branchId == $b->id ? 'selected' : '' }}>
                                {{ $b->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif
                <button type="submit" class="btn btn-primary btn-sm px-3">Aplicar</button>
            </form>
            <div class="d-flex gap-1 mt-2 flex-wrap">
                <button class="btn btn-outline-secondary preset-btn" onclick="setPreset('month')">Este mes</button>
                <button class="btn btn-outline-secondary preset-btn" onclick="setPreset('last_month')">Mes anterior</button>
                <button class="btn btn-outline-secondary preset-btn" onclick="setPreset('quarter')">Últ. 3 meses</button>
                <button class="btn btn-outline-secondary preset-btn" onclick="setPreset('year')">Este año</button>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════════════ --}}
    {{-- 1. RESUMEN EJECUTIVO --}}
    {{-- ══════════════════════════════════════════════════════════════════════ --}}
    <div class="report-section">
        <p class="report-section-title"><i class="bi bi-speedometer2"></i> Resumen Ejecutivo</p>

        <div class="row g-3 mb-4">
            {{-- Ingresos Facturados --}}
            <div class="col-md-3">
                <div class="kpi-card">
                    <div class="kpi-label">Ingresos Facturados</div>
                    <div class="kpi-value text-primary">
                        ${{ number_format($executive['totalRevenue'], 0, ',', '.') }}
                    </div>
                    @if($executive['revenueChange'] !== null)
                        <div class="kpi-change {{ $executive['revenueChange'] >= 0 ? 'change-up' : 'change-down' }}">
                            <i class="bi bi-arrow-{{ $executive['revenueChange'] >= 0 ? 'up' : 'down' }}-short"></i>
                            {{ abs($executive['revenueChange']) }}% vs período anterior
                        </div>
                    @else
                        <div class="kpi-change change-neutral">Sin datos previos</div>
                    @endif
                </div>
            </div>

            {{-- Presupuestos Emitidos --}}
            <div class="col-md-3">
                <div class="kpi-card">
                    <div class="kpi-label">Presupuestos Emitidos</div>
                    <div class="kpi-value text-dark">{{ $executive['totalQuotations'] }}</div>
                    @if($executive['countChange'] !== null)
                        <div class="kpi-change {{ $executive['countChange'] >= 0 ? 'change-up' : 'change-down' }}">
                            <i class="bi bi-arrow-{{ $executive['countChange'] >= 0 ? 'up' : 'down' }}-short"></i>
                            {{ abs($executive['countChange']) }}% vs período anterior
                        </div>
                    @else
                        <div class="kpi-change change-neutral">Sin datos previos</div>
                    @endif
                </div>
            </div>

            {{-- Ticket Promedio --}}
            <div class="col-md-3">
                <div class="kpi-card">
                    <div class="kpi-label">Ticket Promedio Facturado</div>
                    <div class="kpi-value text-success">
                        ${{ number_format($executive['avgTicket'], 0, ',', '.') }}
                    </div>
                    <div class="kpi-change change-neutral">
                        {{ $executive['invoicedCount'] }} facturas emitidas
                    </div>
                </div>
            </div>

            {{-- Tasa de Aprobación --}}
            <div class="col-md-3">
                <div class="kpi-card">
                    <div class="kpi-label">Tasa de Aprobación</div>
                    <div class="kpi-value {{ $executive['approvalRate'] >= 70 ? 'text-success' : ($executive['approvalRate'] >= 40 ? 'text-warning' : 'text-danger') }}">
                        {{ $executive['approvalRate'] }}%
                    </div>
                    <div class="mt-2">
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar {{ $executive['approvalRate'] >= 70 ? 'bg-success' : ($executive['approvalRate'] >= 40 ? 'bg-warning' : 'bg-danger') }}"
                                style="width: {{ $executive['approvalRate'] }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Gráfico de ingresos mensuales --}}
        <div class="card p-4">
            <h6 class="fw-bold mb-1">Evolución de Ingresos en el Período</h6>
            <p class="text-secondary small mb-3">Solo presupuestos facturados.</p>
            @if($monthlyChart->count() > 0)
                <div style="position: relative; height: 220px;">
                    <canvas id="monthlyChart"></canvas>
                </div>
            @else
                <div class="text-center py-4 text-secondary">
                    <i class="bi bi-graph-up fs-2 d-block mb-2 opacity-25"></i>
                    No hay facturación en el período seleccionado.
                </div>
            @endif
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════════════ --}}
    {{-- 2. PIPELINE --}}
    {{-- ══════════════════════════════════════════════════════════════════════ --}}
    <div class="report-section">
        <p class="report-section-title"><i class="bi bi-funnel-fill"></i> Pipeline de Presupuestos</p>

        <div class="row g-4">
            <div class="col-md-7">
                <div class="card p-4">
                    <h6 class="fw-bold mb-1">Distribución por Estado</h6>
                    <p class="text-secondary small mb-4">Cantidad y montos en cada etapa del proceso.</p>
                    @php
                        $maxCount = max(collect($pipeline)->pluck('count')->max(), 1);
                        $colors = [
                            'draft'    => ['bar' => '#fbbf24', 'badge' => '#fef3c7', 'text' => '#92400e'],
                            'sent'     => ['bar' => '#38bdf8', 'badge' => '#e0f2fe', 'text' => '#075985'],
                            'approved' => ['bar' => '#34d399', 'badge' => '#dcfce7', 'text' => '#166534'],
                            'finished' => ['bar' => '#818cf8', 'badge' => '#ede9fe', 'text' => '#4c1d95'],
                            'invoiced' => ['bar' => '#2563eb', 'badge' => '#dbeafe', 'text' => '#1e3a8a'],
                            'rejected' => ['bar' => '#f87171', 'badge' => '#fee2e2', 'text' => '#991b1b'],
                        ];
                    @endphp
                    @foreach($pipeline as $stage)
                        @php $c = $colors[$stage['key']] ?? ['bar' => '#94a3b8', 'badge' => '#f1f5f9', 'text' => '#475569']; @endphp
                        <div class="pipeline-row">
                            <div style="width: 100px;">
                                <span class="badge rounded-pill px-3 py-2 fw-semibold"
                                    style="font-size: 0.65rem; background-color: {{ $c['badge'] }}; color: {{ $c['text'] }}">
                                    {{ $stage['label'] }}
                                </span>
                            </div>
                            <div class="pipeline-bar-wrap">
                                <div class="pipeline-bar"
                                    style="width: {{ $maxCount > 0 ? round($stage['count'] / $maxCount * 100) : 0 }}%; background: {{ $c['bar'] }}">
                                </div>
                            </div>
                            <div class="text-end" style="width: 40px;">
                                <span class="fw-bold small">{{ $stage['count'] }}</span>
                            </div>
                            <div class="text-end text-secondary small" style="width: 110px;">
                                ${{ number_format($stage['amount'], 0, ',', '.') }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="col-md-5">
                <div class="card p-4 h-100 d-flex flex-column justify-content-center">
                    <h6 class="fw-bold mb-3">Resumen del Embudo</h6>
                    @php
                        $totalP = collect($pipeline)->sum('count');
                        $invoicedP = collect($pipeline)->firstWhere('key', 'invoiced')['count'] ?? 0;
                        $rejectedP = collect($pipeline)->firstWhere('key', 'rejected')['count'] ?? 0;
                        $activeP = $totalP - $invoicedP - $rejectedP;
                        $conversionP = $totalP > 0 ? round($invoicedP / $totalP * 100) : 0;
                    @endphp
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded-3">
                            <span class="small text-secondary">Total ingresados</span>
                            <span class="fw-bold">{{ $totalP }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center p-3 rounded-3"
                            style="background: #ede9fe;">
                            <span class="small" style="color: #4c1d95;">En proceso</span>
                            <span class="fw-bold" style="color: #4c1d95;">{{ $activeP }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center p-3 rounded-3"
                            style="background: #dcfce7;">
                            <span class="small text-success">Facturados</span>
                            <span class="fw-bold text-success">{{ $invoicedP }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center p-3 rounded-3"
                            style="background: #fee2e2;">
                            <span class="small text-danger">Rechazados</span>
                            <span class="fw-bold text-danger">{{ $rejectedP }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center p-3 bg-primary text-white rounded-3">
                            <span class="small fw-semibold">Tasa de conversión</span>
                            <span class="fw-bold fs-5">{{ $conversionP }}%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════════════ --}}
    {{-- 3. INGRESOS POR ASEGURADORA --}}
    {{-- ══════════════════════════════════════════════════════════════════════ --}}
    <div class="report-section">
        <p class="report-section-title"><i class="bi bi-building-fill"></i> Ingresos por Aseguradora</p>

        <div class="row g-4">
            <div class="col-md-5">
                <div class="card p-4">
                    <h6 class="fw-bold mb-1">Distribución de Ingresos</h6>
                    <p class="text-secondary small mb-3">Solo presupuestos facturados.</p>
                    @if($byInsurance->count() > 0)
                        <div style="position: relative; height: 260px;">
                            <canvas id="insuranceChart"></canvas>
                        </div>
                    @else
                        <div class="text-center py-5 text-secondary">
                            <i class="bi bi-building fs-2 d-block mb-2 opacity-25"></i>
                            Sin datos en el período.
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-md-7">
                <div class="card p-4">
                    <h6 class="fw-bold mb-3">Detalle por Fuente</h6>
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th>Fuente</th>
                                    <th class="text-center">Facturas</th>
                                    <th class="text-end">Total</th>
                                    <th class="text-end">% del Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $grandTotalIns = $byInsurance->sum('total'); @endphp
                                @forelse($byInsurance as $ins)
                                    <tr>
                                        <td class="fw-semibold">{{ $ins['name'] }}</td>
                                        <td class="text-center text-secondary small">{{ $ins['count'] }}</td>
                                        <td class="text-end fw-bold">
                                            ${{ number_format($ins['total'], 0, ',', '.') }}</td>
                                        <td class="text-end">
                                            <span class="badge bg-light text-dark border">
                                                {{ $grandTotalIns > 0 ? round($ins['total'] / $grandTotalIns * 100) : 0 }}%
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-secondary py-4">
                                            Sin facturación en el período.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════════════ --}}
    {{-- 4. RANKING DE CLIENTES --}}
    {{-- ══════════════════════════════════════════════════════════════════════ --}}
    <div class="report-section">
        <p class="report-section-title"><i class="bi bi-trophy-fill"></i> Ranking de Clientes</p>

        <div class="card">
            <div class="p-4 border-bottom">
                <h6 class="fw-bold mb-0">Top 10 Clientes por Monto</h6>
                <p class="text-secondary small mb-0">Presupuestos aprobados, terminados y facturados.</p>
            </div>
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead>
                        <tr>
                            <th style="width: 40px;">#</th>
                            <th>Cliente</th>
                            <th>RUT</th>
                            <th class="text-center">Presupuestos</th>
                            <th class="text-end">Monto Total</th>
                            <th style="width: 160px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $maxClient = $topClients->max('total') ?: 1; @endphp
                        @forelse($topClients as $i => $client)
                            <tr>
                                <td>
                                    @if($i === 0) <span class="badge bg-warning text-dark">1°</span>
                                    @elseif($i === 1) <span class="badge bg-secondary">2°</span>
                                    @elseif($i === 2) <span class="badge" style="background:#cd7f32;color:white">3°</span>
                                    @else <span class="text-secondary small">{{ $i + 1 }}°</span>
                                    @endif
                                </td>
                                <td class="fw-semibold">{{ $client['name'] }}</td>
                                <td class="text-secondary small">{{ $client['rut'] }}</td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark border">{{ $client['count'] }}</span>
                                </td>
                                <td class="text-end fw-bold text-primary">
                                    ${{ number_format($client['total'], 0, ',', '.') }}
                                </td>
                                <td>
                                    <div class="progress" style="height: 5px;">
                                        <div class="progress-bar bg-primary"
                                            style="width: {{ round($client['total'] / $maxClient * 100) }}%"></div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-secondary py-4">
                                    Sin datos en el período.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════════════ --}}
    {{-- 5. REPUESTOS VS MANO DE OBRA --}}
    {{-- ══════════════════════════════════════════════════════════════════════ --}}
    <div class="report-section">
        <p class="report-section-title"><i class="bi bi-tools"></i> Composición: Repuestos vs Mano de Obra</p>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card p-4">
                    <h6 class="fw-bold mb-1">Participación por Tipo</h6>
                    <p class="text-secondary small mb-3">Suma de ítems en presupuestos cerrados.</p>
                    @if($itemTypes['itemsGrandTotal'] > 0)
                        <div style="position: relative; height: 220px;">
                            <canvas id="itemTypesChart"></canvas>
                        </div>
                    @else
                        <div class="text-center py-5 text-secondary">
                            <i class="bi bi-tools fs-2 d-block mb-2 opacity-25"></i>
                            Sin datos en el período.
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-md-8">
                <div class="row g-3 h-100 align-content-start">
                    <div class="col-6">
                        <div class="kpi-card h-100" style="border-left: 4px solid #2563eb;">
                            <div class="kpi-label">Repuestos</div>
                            <div class="kpi-value text-primary">
                                ${{ number_format($itemTypes['repuestoTotal'], 0, ',', '.') }}
                            </div>
                            <p class="text-secondary small mb-1 mt-2">{{ $itemTypes['repuestoCount'] }} líneas</p>
                            @if($itemTypes['itemsGrandTotal'] > 0)
                                <div class="progress mt-2" style="height: 5px;">
                                    <div class="progress-bar bg-primary"
                                        style="width: {{ round($itemTypes['repuestoTotal'] / $itemTypes['itemsGrandTotal'] * 100) }}%">
                                    </div>
                                </div>
                                <p class="small fw-bold text-primary mt-1 mb-0">
                                    {{ round($itemTypes['repuestoTotal'] / $itemTypes['itemsGrandTotal'] * 100) }}%
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="kpi-card h-100" style="border-left: 4px solid #10b981;">
                            <div class="kpi-label">Mano de Obra</div>
                            <div class="kpi-value text-success">
                                ${{ number_format($itemTypes['manoObraTotal'], 0, ',', '.') }}
                            </div>
                            <p class="text-secondary small mb-1 mt-2">{{ $itemTypes['manoObraCount'] }} líneas</p>
                            @if($itemTypes['itemsGrandTotal'] > 0)
                                <div class="progress mt-2" style="height: 5px;">
                                    <div class="progress-bar bg-success"
                                        style="width: {{ round($itemTypes['manoObraTotal'] / $itemTypes['itemsGrandTotal'] * 100) }}%">
                                    </div>
                                </div>
                                <p class="small fw-bold text-success mt-1 mb-0">
                                    {{ round($itemTypes['manoObraTotal'] / $itemTypes['itemsGrandTotal'] * 100) }}%
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="kpi-card" style="border-left: 4px solid #64748b;">
                            <div class="kpi-label">Total de Ítems (base neta)</div>
                            <div class="kpi-value text-dark">
                                ${{ number_format($itemTypes['itemsGrandTotal'], 0, ',', '.') }}
                            </div>
                            <p class="text-secondary small mb-0 mt-1">
                                {{ $itemTypes['repuestoCount'] + $itemTypes['manoObraCount'] }} líneas en total
                                · En presupuestos aprobados, terminados y facturados
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script src="/vendor/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const fmt = val => '$' + Math.round(val).toLocaleString('es-CL');

    const chartDefaults = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
    };

    // ── Gráfico Ingresos Mensuales ───────────────────────────────────────────
    @if($monthlyChart->count() > 0)
    new Chart(document.getElementById('monthlyChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($monthlyChart->pluck('label')) !!},
            datasets: [{
                label: 'Ingresos',
                data: {!! json_encode($monthlyChart->pluck('total')) !!},
                backgroundColor: 'rgba(37, 99, 235, 0.15)',
                borderColor: '#2563eb',
                borderWidth: 2,
                borderRadius: 8,
            }]
        },
        options: {
            ...chartDefaults,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#f1f5f9' },
                    ticks: { callback: fmt }
                },
                x: { grid: { display: false } }
            },
            plugins: {
                legend: { display: false },
                tooltip: { callbacks: { label: ctx => fmt(ctx.parsed.y) } }
            }
        }
    });
    @endif

    // ── Gráfico Aseguradoras (Donut) ─────────────────────────────────────────
    @if($byInsurance->count() > 0)
    new Chart(document.getElementById('insuranceChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($byInsurance->pluck('name')) !!},
            datasets: [{
                data: {!! json_encode($byInsurance->pluck('total')) !!},
                backgroundColor: ['#2563eb','#10b981','#f59e0b','#ef4444','#8b5cf6','#64748b'],
                borderWidth: 2,
                borderColor: '#fff',
            }]
        },
        options: {
            ...chartDefaults,
            cutout: '65%',
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: { font: { size: 11 }, padding: 12 }
                },
                tooltip: { callbacks: { label: ctx => ` ${ctx.label}: ${fmt(ctx.parsed)}` } }
            }
        }
    });
    @endif

    // ── Gráfico Repuestos vs MO (Donut) ──────────────────────────────────────
    @if($itemTypes['itemsGrandTotal'] > 0)
    new Chart(document.getElementById('itemTypesChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: ['Repuestos', 'Mano de Obra'],
            datasets: [{
                data: [{{ $itemTypes['repuestoTotal'] }}, {{ $itemTypes['manoObraTotal'] }}],
                backgroundColor: ['#2563eb', '#10b981'],
                borderWidth: 2,
                borderColor: '#fff',
            }]
        },
        options: {
            ...chartDefaults,
            cutout: '65%',
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: { font: { size: 11 }, padding: 12 }
                },
                tooltip: { callbacks: { label: ctx => ` ${ctx.label}: ${fmt(ctx.parsed)}` } }
            }
        }
    });
    @endif

});

// ── Presets de fecha ──────────────────────────────────────────────────────────
function setPreset(preset) {
    const from = document.getElementById('inputFrom');
    const to   = document.getElementById('inputTo');
    const now  = new Date();
    const pad  = n => String(n).padStart(2, '0');
    const fmt  = d => `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())}`;

    if (preset === 'month') {
        from.value = fmt(new Date(now.getFullYear(), now.getMonth(), 1));
        to.value   = fmt(now);
    } else if (preset === 'last_month') {
        const first = new Date(now.getFullYear(), now.getMonth() - 1, 1);
        const last  = new Date(now.getFullYear(), now.getMonth(), 0);
        from.value = fmt(first);
        to.value   = fmt(last);
    } else if (preset === 'quarter') {
        const d = new Date(now);
        d.setMonth(d.getMonth() - 3);
        from.value = fmt(d);
        to.value   = fmt(now);
    } else if (preset === 'year') {
        from.value = `${now.getFullYear()}-01-01`;
        to.value   = fmt(now);
    }
    from.closest('form').submit();
}
</script>
@endsection
