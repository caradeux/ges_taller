@extends('layouts.app')

@section('title', 'Reporte por Aseguradora')

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
            <h2 class="fw-bold mb-1">Reporte por Aseguradora</h2>
            <p class="text-secondary small mb-0">Desglose de OTs autorizadas y facturadas por compania de seguros.</p>
        </div>
    </div>

    {{-- Filtro --}}
    <div class="card p-4 mb-4 no-print">
        <form action="{{ route('reports.insurance') }}" method="GET" class="d-flex gap-3 align-items-end flex-wrap">
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
        <div class="d-flex gap-1 mt-2 flex-wrap">
            <button class="btn btn-outline-secondary preset-btn" onclick="setPreset('month')">Este mes</button>
            <button class="btn btn-outline-secondary preset-btn" onclick="setPreset('last_month')">Mes anterior</button>
            <button class="btn btn-outline-secondary preset-btn" onclick="setPreset('quarter')">Ult. 3 meses</button>
        </div>
    </div>

    {{-- Donut Chart --}}
    <div class="report-section">
        <p class="report-section-title"><i class="bi bi-pie-chart-fill"></i> Distribucion por Aseguradora</p>

        <div class="row g-4">
            <div class="col-md-5">
                <div class="card p-4">
                    <h6 class="fw-bold mb-1">Total Autorizado por Aseguradora</h6>
                    <p class="text-secondary small mb-3">Presupuestos autorizados en el periodo.</p>
                    @if($byInsurance->count() > 0)
                        <div style="position: relative; height: 280px;">
                            <canvas id="insuranceDoughnut"></canvas>
                        </div>
                    @else
                        <div class="text-center py-5 text-secondary">
                            <i class="bi bi-building fs-2 d-block mb-2 opacity-25"></i>
                            Sin datos en el periodo.
                        </div>
                    @endif
                </div>
            </div>

            {{-- Tabla --}}
            <div class="col-md-7">
                <div class="card p-4">
                    <h6 class="fw-bold mb-3">Detalle por Aseguradora</h6>
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th>Aseguradora</th>
                                    <th class="text-center">Cantidad OTs</th>
                                    <th class="text-end">Total Autorizado</th>
                                    <th class="text-end">Total Facturado</th>
                                    <th class="text-end">% del Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $grandTotalAuthorized = $byInsurance->sum('total_authorized');
                                @endphp
                                @forelse($byInsurance as $ins)
                                    <tr>
                                        <td class="fw-semibold">{{ $ins->name }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-light text-dark border">{{ $ins->count }}</span>
                                        </td>
                                        <td class="text-end fw-bold" style="color: var(--primary);">
                                            ${{ number_format($ins->total_authorized, 0, ',', '.') }}
                                        </td>
                                        <td class="text-end text-secondary">
                                            ${{ number_format($ins->total_invoiced, 0, ',', '.') }}
                                        </td>
                                        <td class="text-end">
                                            <span class="badge bg-light text-dark border">
                                                {{ $grandTotalAuthorized > 0 ? round($ins->total_authorized / $grandTotalAuthorized * 100) : 0 }}%
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-secondary py-4">
                                            Sin datos en el periodo seleccionado.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if($byInsurance->count() > 0)
                            <tfoot>
                                <tr class="fw-bold" style="background: #f8fafc;">
                                    <td>Total</td>
                                    <td class="text-center">{{ $byInsurance->sum('count') }}</td>
                                    <td class="text-end" style="color: var(--primary);">
                                        ${{ number_format($grandTotalAuthorized, 0, ',', '.') }}
                                    </td>
                                    <td class="text-end">
                                        ${{ number_format($byInsurance->sum('total_invoiced'), 0, ',', '.') }}
                                    </td>
                                    <td class="text-end">100%</td>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
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

    // ── Doughnut Aseguradoras ──────────────────────────────────────────────
    @if($byInsurance->count() > 0)
    new Chart(document.getElementById('insuranceDoughnut').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($byInsurance->pluck('name')) !!},
            datasets: [{
                data: {!! json_encode($byInsurance->pluck('total_authorized')) !!},
                backgroundColor: ['#1e40af','#16a34a','#d97706','#dc2626','#8b5cf6','#0284c7','#ea580c','#64748b'],
                borderWidth: 2,
                borderColor: '#fff',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: { font: { size: 11 }, padding: 12 }
                },
                tooltip: {
                    callbacks: {
                        label: ctx => ` ${ctx.label}: ${fmt(ctx.parsed)}`
                    }
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
