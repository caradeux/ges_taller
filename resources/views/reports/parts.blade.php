@extends('layouts.app')

@section('title', 'Reporte de Repuestos')

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
    .days-green  { color: var(--success); font-weight: 700; }
    .days-yellow { color: var(--warning); font-weight: 700; }
    .days-red    { color: var(--danger); font-weight: 700; }
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
            <h2 class="fw-bold mb-1">Reporte de Repuestos &mdash; Dias de Espera</h2>
            <p class="text-secondary small mb-0">Analisis de tiempos de entrega por proveedor de repuestos.</p>
        </div>
    </div>

    {{-- Filtro --}}
    <div class="card p-4 mb-4 no-print">
        <form action="{{ route('reports.parts') }}" method="GET" class="d-flex gap-3 align-items-end flex-wrap">
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

    {{-- Horizontal Bar Chart --}}
    <div class="report-section">
        <p class="report-section-title"><i class="bi bi-clock-history"></i> Dias Promedio por Proveedor</p>

        <div class="card p-4 mb-4">
            @if($partStats->count() > 0)
                <div style="position: relative; height: {{ max(200, $partStats->count() * 45) }}px;">
                    <canvas id="partsChart"></canvas>
                </div>
            @else
                <div class="text-center py-5 text-secondary">
                    <i class="bi bi-box-seam fs-2 d-block mb-2 opacity-25"></i>
                    Sin datos en el periodo seleccionado.
                </div>
            @endif
        </div>
    </div>

    {{-- Tabla detalle --}}
    <div class="report-section">
        <p class="report-section-title"><i class="bi bi-table"></i> Detalle por Proveedor</p>

        <div class="card">
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>Proveedor</th>
                            <th class="text-center">Pedidos</th>
                            <th class="text-center">Dias Promedio</th>
                            <th class="text-center">Dias Maximo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($partStats as $stat)
                            @php
                                $avgDays = round($stat->avg_days, 1);
                                if ($avgDays <= 3) {
                                    $dayClass = 'days-green';
                                } elseif ($avgDays <= 7) {
                                    $dayClass = 'days-yellow';
                                } else {
                                    $dayClass = 'days-red';
                                }
                            @endphp
                            <tr>
                                <td class="fw-semibold">{{ $stat->supplier }}</td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark border">{{ $stat->count }}</span>
                                </td>
                                <td class="text-center {{ $dayClass }}">
                                    {{ number_format($avgDays, 1, ',', '.') }}
                                </td>
                                <td class="text-center text-secondary">
                                    {{ $stat->max_days }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-secondary py-4">
                                    Sin datos en el periodo seleccionado.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
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

    // ── Horizontal Bar: Dias promedio por proveedor ────────────────────────
    @if($partStats->count() > 0)
    @php
        $chartLabels = $partStats->pluck('supplier');
        $chartAvgDays = $partStats->pluck('avg_days')->map(fn($v) => round($v, 1));
        $chartBarColors = $partStats->map(function($stat) {
            $avg = round($stat->avg_days, 1);
            if ($avg <= 3) return 'rgba(22,163,74,0.7)';
            if ($avg <= 7) return 'rgba(217,119,6,0.7)';
            return 'rgba(220,38,38,0.7)';
        });
        $chartBorderColors = $partStats->map(function($stat) {
            $avg = round($stat->avg_days, 1);
            if ($avg <= 3) return '#16a34a';
            if ($avg <= 7) return '#d97706';
            return '#dc2626';
        });
    @endphp
    new Chart(document.getElementById('partsChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartLabels->values()) !!},
            datasets: [{
                label: 'Dias Promedio',
                data: {!! json_encode($chartAvgDays->values()) !!},
                backgroundColor: {!! json_encode($chartBarColors->values()) !!},
                borderColor: {!! json_encode($chartBorderColors->values()) !!},
                borderWidth: 2,
                borderRadius: 6,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => ` ${ctx.parsed.x} dias promedio`
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    grid: { color: '#f1f5f9' },
                    title: {
                        display: true,
                        text: 'Dias',
                        font: { size: 11 },
                        color: '#94a3b8'
                    }
                },
                y: {
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
