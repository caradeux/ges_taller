@extends('layouts.app')

@section('title', 'Reporte de Rentabilidad')

@section('styles')
<style>
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
        color: var(--text-muted);
        font-weight: 500;
    }
    .preset-btn {
        font-size: 0.72rem;
        padding: 0.3rem 0.8rem;
        border-radius: 99px;
    }
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
    .profit-positive { color: var(--success); }
    .profit-negative { color: var(--danger); }
    @media print {
        .sidebar, .main-content > header, .no-print { display: none !important; }
        .main-content { margin-left: 0 !important; padding: 1rem !important; }
        .card { box-shadow: none !important; border: 1px solid #e2e8f0 !important; }
        .report-section { page-break-inside: avoid; }
        canvas { max-height: 250px !important; }
        body { font-size: 11px; }
    }
</style>
@endsection

@section('content')
<div class="animate-in">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h2 class="fw-bold mb-1">Reporte de Rentabilidad</h2>
            <p class="text-secondary small mb-0">Analisis de ganancia por orden de trabajo.</p>
        </div>
    </div>

    {{-- Filtro --}}
    <div class="card p-4 mb-4 no-print">
        <form action="{{ route('reports.profitability') }}" method="GET" class="d-flex gap-3 align-items-end flex-wrap">
            <div>
                <label class="form-label" style="font-size:0.78rem;">Desde</label>
                <input type="date" name="from" class="form-control form-control-sm" id="inputFrom" value="{{ $from }}">
            </div>
            <div>
                <label class="form-label" style="font-size:0.78rem;">Hasta</label>
                <input type="date" name="to" class="form-control form-control-sm" id="inputTo" value="{{ $to }}">
            </div>
            @if(auth()->user()->role === 'admin')
            <div>
                <label class="form-label" style="font-size:0.78rem;">Sucursal</label>
                <select name="branch_id" class="form-select form-select-sm">
                    <option value="">Todas</option>
                    @foreach($branches as $b)
                    <option value="{{ $b->id }}" {{ $branchId == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            <button type="submit" class="btn-primary-premium" style="padding:0.4rem 1rem;">
                <i class="bi bi-funnel"></i> Aplicar
            </button>
        </form>
    </div>

    {{-- KPI Cards --}}
    <div class="report-section">
        <p class="report-section-title"><i class="bi bi-speedometer2"></i> Indicadores Clave</p>

        <div class="row g-3 mb-4">
            {{-- Total Autorizado --}}
            <div class="col-md-3">
                <div class="kpi-card" style="border-left: 4px solid var(--primary);">
                    <div class="kpi-label">Total Autorizado</div>
                    <div class="kpi-value" style="color: var(--primary);">
                        ${{ number_format($totals['authorized'], 0, ',', '.') }}
                    </div>
                </div>
            </div>

            {{-- Costo Real --}}
            <div class="col-md-3">
                <div class="kpi-card" style="border-left: 4px solid var(--danger);">
                    <div class="kpi-label">Costo Real</div>
                    <div class="kpi-value" style="color: var(--danger);">
                        ${{ number_format($totals['real_cost'], 0, ',', '.') }}
                    </div>
                </div>
            </div>

            {{-- Ganancia --}}
            <div class="col-md-3">
                @php $profitColor = $totals['profit'] >= 0 ? 'var(--success)' : 'var(--danger)'; @endphp
                <div class="kpi-card" style="border-left: 4px solid {{ $profitColor }};">
                    <div class="kpi-label">Ganancia</div>
                    <div class="kpi-value" style="color: {{ $profitColor }};">
                        ${{ number_format($totals['profit'], 0, ',', '.') }}
                    </div>
                </div>
            </div>

            {{-- Margen % --}}
            <div class="col-md-3">
                @php
                    if ($totals['margin'] >= 20) {
                        $marginColor = 'var(--success)';
                    } elseif ($totals['margin'] >= 0) {
                        $marginColor = 'var(--warning)';
                    } else {
                        $marginColor = 'var(--danger)';
                    }
                @endphp
                <div class="kpi-card" style="border-left: 4px solid {{ $marginColor }};">
                    <div class="kpi-label">Margen %</div>
                    <div class="kpi-value" style="color: {{ $marginColor }};">
                        {{ number_format($totals['margin'], 1, ',', '.') }}%
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Bar Chart: Rentabilidad por OT (top 10) --}}
    <div class="report-section">
        <p class="report-section-title"><i class="bi bi-bar-chart-fill"></i> Rentabilidad por OT (Top 10)</p>

        <div class="card p-4 mb-4">
            @if($workOrders->count() > 0)
                <div style="position: relative; height: 300px;">
                    <canvas id="profitChart"></canvas>
                </div>
            @else
                <div class="text-center py-5 text-secondary">
                    <i class="bi bi-bar-chart fs-2 d-block mb-2 opacity-25"></i>
                    Sin datos en el periodo seleccionado.
                </div>
            @endif
        </div>
    </div>

    {{-- Tabla detalle --}}
    <div class="report-section">
        <p class="report-section-title"><i class="bi bi-table"></i> Detalle por Orden de Trabajo</p>

        <div class="card">
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>Folio</th>
                            <th>Cliente</th>
                            <th>Vehiculo</th>
                            <th class="text-end">Autorizado</th>
                            <th class="text-end">Costo Real</th>
                            <th class="text-end">Ganancia</th>
                            <th class="text-end">Margen %</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($workOrders as $wo)
                            @php
                                $woProfit = $wo->profit;
                                $woMargin = $wo->margin;
                            @endphp
                            <tr>
                                <td class="fw-semibold">{{ $wo->folio_display ?? '-' }}</td>
                                <td>{{ $wo->client->name ?? '-' }}</td>
                                <td class="text-secondary small">{{ $wo->vehicle->license_plate ?? '-' }} {{ $wo->vehicle->brand ?? '' }} {{ $wo->vehicle->model ?? '' }}</td>
                                <td class="text-end fw-bold" style="color: var(--primary);">
                                    ${{ number_format($wo->total_authorized, 0, ',', '.') }}
                                </td>
                                <td class="text-end">
                                    ${{ number_format($wo->total_real_cost, 0, ',', '.') }}
                                </td>
                                <td class="text-end fw-bold {{ $woProfit >= 0 ? 'profit-positive' : 'profit-negative' }}">
                                    ${{ number_format($woProfit, 0, ',', '.') }}
                                </td>
                                <td class="text-end fw-bold {{ $woMargin >= 0 ? 'profit-positive' : 'profit-negative' }}">
                                    {{ number_format($woMargin, 1, ',', '.') }}%
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-secondary py-4">
                                    Sin datos en el periodo seleccionado.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if($workOrders->count() > 0)
                    <tfoot>
                        <tr class="fw-bold" style="background: #f8fafc;">
                            <td colspan="3">Totales</td>
                            <td class="text-end" style="color: var(--primary);">
                                ${{ number_format($totals['authorized'], 0, ',', '.') }}
                            </td>
                            <td class="text-end">
                                ${{ number_format($totals['real_cost'], 0, ',', '.') }}
                            </td>
                            <td class="text-end {{ $totals['profit'] >= 0 ? 'profit-positive' : 'profit-negative' }}">
                                ${{ number_format($totals['profit'], 0, ',', '.') }}
                            </td>
                            <td class="text-end {{ $totals['margin'] >= 0 ? 'profit-positive' : 'profit-negative' }}">
                                {{ number_format($totals['margin'], 1, ',', '.') }}%
                            </td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
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

    // ── Bar Chart: Rentabilidad por OT ─────────────────────────────────────
    @if($workOrders->count() > 0)
    @php
        $top10 = $workOrders->take(10);
        $chartLabels = $top10->map(fn($wo) => $wo->folio_display ?? 'S/F');
        $chartProfits = $top10->pluck('profit');
        $chartColors = $top10->map(fn($wo) => $wo->profit >= 0 ? 'rgba(22,163,74,0.7)' : 'rgba(220,38,38,0.7)');
        $chartBorders = $top10->map(fn($wo) => $wo->profit >= 0 ? '#16a34a' : '#dc2626');
    @endphp
    new Chart(document.getElementById('profitChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartLabels->values()) !!},
            datasets: [{
                label: 'Ganancia',
                data: {!! json_encode($chartProfits->values()) !!},
                backgroundColor: {!! json_encode($chartColors->values()) !!},
                borderColor: {!! json_encode($chartBorders->values()) !!},
                borderWidth: 2,
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => ` Ganancia: ${fmt(ctx.parsed.y)}`
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#f1f5f9' },
                    ticks: { callback: val => fmt(val) }
                },
                x: {
                    grid: { display: false }
                }
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
    const fmtD = d => `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())}`;

    if (preset === 'month') {
        from.value = fmtD(new Date(now.getFullYear(), now.getMonth(), 1));
        to.value   = fmtD(now);
    } else if (preset === 'last_month') {
        const first = new Date(now.getFullYear(), now.getMonth() - 1, 1);
        const last  = new Date(now.getFullYear(), now.getMonth(), 0);
        from.value = fmtD(first);
        to.value   = fmtD(last);
    } else if (preset === 'quarter') {
        const d = new Date(now);
        d.setMonth(d.getMonth() - 3);
        from.value = fmtD(d);
        to.value   = fmtD(now);
    }
    from.closest('form').submit();
}
</script>
@endsection
