@extends('layouts.app')

@section('title', 'Panel General')

@section('content')

    {{-- ─── Page Header ────────────────────────────────────── --}}
    <header class="d-flex justify-content-between align-items-center mb-4 animate-in">
        <div>
            <h2 class="page-title mb-1">Bienvenido, {{ auth()->user()->name ?? 'Administrador' }}</h2>
            <p class="page-subtitle">Resumen operativo · {{ \Carbon\Carbon::now()->isoFormat('dddd D [de] MMMM, YYYY') }}</p>
        </div>

        <div class="d-flex align-items-center gap-3">
            {{-- Search bar --}}
            <div class="input-icon-wrap" style="width: 340px;">
                <i class="bi bi-search"></i>
                <input type="text" class="form-control"
                    placeholder="Buscar patente, RUT o folio…"
                    style="background:#fff;border-radius:var(--radius);">
            </div>

            {{-- User chip --}}
            <div class="d-flex align-items-center gap-2 ps-3 border-start">
                <div class="text-end">
                    <p class="mb-0 fw-semibold text-sm">{{ auth()->user()->name ?? 'Usuario' }}</p>
                    <p class="mb-0 text-xs" style="color:var(--text-muted);">{{ ucfirst(auth()->user()->role ?? 'taller') }}</p>
                </div>
                <div class="rounded-circle d-flex align-items-center justify-content-center fw-800 text-white flex-shrink-0"
                    style="width:36px;height:36px;background:linear-gradient(135deg,var(--primary) 0%,#3b82f6 100%);font-size:0.875rem;letter-spacing:-0.01em;">
                    {{ strtoupper(mb_substr(auth()->user()->name ?? 'U', 0, 1)) }}
                </div>
            </div>
        </div>
    </header>

    {{-- ─── Stats Grid ─────────────────────────────────────── --}}
    <div class="row g-3 mb-4 animate-in" style="animation-delay:.08s;">

        {{-- Clientes --}}
        <div class="col-md-3">
            <div class="card p-4 stat-card stat-primary card-hover">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <p class="stat-label mb-2">Clientes</p>
                        <h3 class="stat-value outfit">{{ $stats['total_clients'] }}</h3>
                    </div>
                    <div class="stat-icon stat-icon-primary">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Aprobados --}}
        <div class="col-md-3">
            <div class="card p-4 stat-card stat-success card-hover">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <p class="stat-label mb-2">Aprobados</p>
                        <h3 class="stat-value outfit">{{ $stats['approved_quotations'] }}</h3>
                    </div>
                    <div class="stat-icon stat-icon-success">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pendientes --}}
        <div class="col-md-3">
            <div class="card p-4 stat-card stat-warning card-hover">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <p class="stat-label mb-2">Pendientes</p>
                        <h3 class="stat-value outfit">{{ $stats['pending_quotations'] }}</h3>
                    </div>
                    <div class="stat-icon stat-icon-warning">
                        <i class="bi bi-clock-history"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Facturado mes --}}
        <div class="col-md-3">
            <div class="card p-4 stat-card stat-accent card-hover">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <p class="stat-label mb-2">Facturado (mes)</p>
                        <h3 class="stat-value outfit" style="font-size:1.35rem;">
                            ${{ number_format($stats['total_revenue'], 0, ',', '.') }}
                        </h3>
                    </div>
                    <div class="stat-icon stat-icon-accent">
                        <i class="bi bi-currency-dollar"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ─── Charts + Metrics ───────────────────────────────── --}}
    <div class="row g-3 mb-4 animate-in" style="animation-delay:.16s;">

        {{-- Revenue chart --}}
        <div class="col-md-8">
            <div class="card h-100 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h5 class="fw-bold mb-0 ls-tight">Ingresos Mensuales</h5>
                        <p class="text-xs mb-0" style="color:var(--text-muted);margin-top:2px;">Presupuestos facturados</p>
                    </div>
                    <span class="px-3 py-1 rounded-pill text-xs fw-600"
                        style="background:var(--border-light);color:var(--text-secondary);font-weight:600;">
                        Últimos 6 meses
                    </span>
                </div>
                <div style="position: relative; height: 240px;">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Quick metrics --}}
        <div class="col-md-4">
            <div class="card h-100 p-4 d-flex flex-column">
                <h5 class="fw-bold mb-4 ls-tight">Métricas Rápidas</h5>

                {{-- Progress bars --}}
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-sm" style="color:var(--text-secondary);">Eficiencia de cierre</span>
                        <span class="fw-700 text-sm" style="font-weight:700;">75%</span>
                    </div>
                    <div class="progress" style="height:5px;border-radius:9999px;background:var(--border-light);">
                        <div class="progress-bar" style="width:75%;background:var(--primary);border-radius:9999px;"></div>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-sm" style="color:var(--text-secondary);">Vehículos en taller</span>
                        <span class="fw-700 text-sm" style="font-weight:700;">{{ $stats['total_vehicles'] }}</span>
                    </div>
                    <div class="progress" style="height:5px;border-radius:9999px;background:var(--border-light);">
                        <div class="progress-bar" style="width:60%;background:var(--success);border-radius:9999px;"></div>
                    </div>
                </div>

                {{-- KPI boxes --}}
                <div class="metric-box metric-box-success mb-2">
                    <p class="metric-label mb-1" style="color:var(--success);">Total Facturado</p>
                    <p class="metric-value mb-0" style="color:var(--success);">
                        ${{ number_format($stats['total_revenue'], 0, ',', '.') }}
                    </p>
                </div>

                <div class="metric-box metric-box-warning">
                    <p class="metric-label mb-1" style="color:var(--warning);">Monto Pendiente</p>
                    <p class="metric-value mb-0" style="color:var(--warning);">
                        ${{ number_format($stats['total_pending_amount'], 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>

    </div>

    {{-- ─── Recent Quotations Table ─────────────────────────── --}}
    <div class="card animate-in" style="animation-delay:.24s;">

        <div class="d-flex justify-content-between align-items-center p-4 border-bottom"
            style="border-color:var(--border-light)!important;">
            <div>
                <h5 class="fw-bold mb-0 ls-tight">Últimos Presupuestos</h5>
                <p class="text-xs mb-0" style="color:var(--text-muted);margin-top:2px;">Actividad reciente del taller</p>
            </div>
            <a href="{{ route('quotations.create') }}" class="btn-primary-premium">
                <i class="bi bi-plus-lg"></i> Nuevo Presupuesto
            </a>
        </div>

        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Folio</th>
                        <th>Cliente</th>
                        <th>Vehículo</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th class="text-end">Total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stats['recent_quotations'] as $q)
                        <tr>
                            <td>
                                <span class="fw-700 text-sm ls-tight"
                                    style="font-weight:700;color:var(--text-primary);">
                                    #{{ $q->folio }}
                                </span>
                            </td>
                            <td class="text-sm fw-500" style="font-weight:500;">
                                {{ $q->client->name }}
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-600 text-sm" style="font-weight:600;">
                                        {{ $q->vehicle->license_plate }}
                                    </span>
                                    <span class="text-xs" style="color:var(--text-muted);">
                                        {{ $q->vehicle->brand }} {{ $q->vehicle->model }}
                                    </span>
                                </div>
                            </td>
                            <td class="text-sm" style="color:var(--text-secondary);">
                                {{ \Carbon\Carbon::parse($q->date)->format('d/m/Y') }}
                            </td>
                            <td>
                                <span class="status-badge status-{{ $q->status }}">
                                    {{ $q->status_label }}
                                </span>
                            </td>
                            <td class="text-end fw-700 text-sm ls-tight"
                                style="font-weight:700;color:var(--text-primary);">
                                ${{ number_format($q->total_amount, 0, ',', '.') }}
                            </td>
                            <td class="text-end">
                                <a href="{{ route('quotations.show', $q) }}"
                                    class="btn btn-sm border-0 bg-transparent"
                                    style="color:var(--text-muted);"
                                    title="Ver detalle">
                                    <i class="bi bi-arrow-right-short fs-5"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="bi bi-inbox fs-1" style="color:var(--border);"></i>
                                <p class="text-sm mb-0 mt-2" style="color:var(--text-muted);">
                                    No se encontraron presupuestos registrados.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-3 text-center border-top" style="border-color:var(--border-light)!important;">
            <a href="{{ route('quotations.index') }}"
                class="text-sm fw-600 text-decoration-none"
                style="color:var(--primary);font-weight:600;">
                Ver todos los presupuestos <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

    </div>

@endsection

@section('scripts')
    <script src="/vendor/chart.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('revenueChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($stats['chartData']['labels']) !!},
                    datasets: [{
                        label: 'Ingresos (CLP)',
                        data: {!! json_encode($stats['chartData']['values']) !!},
                        borderColor: '#1e40af',
                        backgroundColor: 'rgba(30, 64, 175, 0.07)',
                        borderWidth: 2.5,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#1e40af',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointHoverBackgroundColor: '#1e40af',
                        pointHoverBorderColor: '#fff',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0d1520',
                            titleColor: '#94a3b8',
                            bodyColor: '#ffffff',
                            bodyFont: { weight: '700', size: 13 },
                            padding: 10,
                            cornerRadius: 8,
                            callbacks: {
                                label: function(ctx) {
                                    return ' $' + ctx.parsed.y.toLocaleString('es-CL');
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#f1f5f9', drawBorder: false },
                            border: { display: false },
                            ticks: {
                                color: '#9ca3af',
                                font: { size: 11 },
                                callback: function (value) {
                                    if (value >= 1000000) return '$' + (value / 1000000).toFixed(1) + 'M';
                                    if (value >= 1000) return '$' + (value / 1000).toFixed(0) + 'K';
                                    return '$' + value.toLocaleString('es-CL');
                                }
                            }
                        },
                        x: {
                            grid: { display: false },
                            border: { display: false },
                            ticks: { color: '#9ca3af', font: { size: 11 } }
                        }
                    }
                }
            });
        });
    </script>
@endsection
