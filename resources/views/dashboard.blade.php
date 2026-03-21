@extends('layouts.app')

@section('title', 'Panel General')

@section('styles')
<style>
    /* ── Dashboard Stats ──────────────────────────────── */
    .dashboard-stat {
        position: relative;
        overflow: hidden;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .dashboard-stat:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.08);
    }
    .dashboard-stat .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }
    .dashboard-stat .stat-icon-primary {
        background: linear-gradient(135deg, rgba(30,64,175,0.12) 0%, rgba(59,130,246,0.18) 100%);
        color: var(--primary, #1e40af);
    }
    .dashboard-stat .stat-icon-info {
        background: linear-gradient(135deg, rgba(6,182,212,0.12) 0%, rgba(34,211,238,0.18) 100%);
        color: #0891b2;
    }
    .dashboard-stat .stat-icon-warning {
        background: linear-gradient(135deg, rgba(245,158,11,0.12) 0%, rgba(251,191,36,0.18) 100%);
        color: var(--warning, #f59e0b);
    }
    .dashboard-stat .stat-icon-success {
        background: linear-gradient(135deg, rgba(22,163,74,0.12) 0%, rgba(74,222,128,0.18) 100%);
        color: var(--success, #16a34a);
    }
    .dashboard-stat .stat-icon-orange {
        background: linear-gradient(135deg, rgba(234,88,12,0.12) 0%, rgba(251,146,60,0.18) 100%);
        color: #ea580c;
    }
    .dashboard-stat .stat-value {
        font-size: 1.75rem;
        font-weight: 800;
        line-height: 1.1;
        color: var(--text-primary, #0f172a);
    }
    .dashboard-stat .stat-label {
        font-size: 0.8rem;
        font-weight: 500;
        color: var(--text-muted, #94a3b8);
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }
    .dashboard-stat .stat-badge {
        font-size: 0.65rem;
        font-weight: 600;
        padding: 2px 7px;
        border-radius: 9999px;
        display: inline-flex;
        align-items: center;
        gap: 2px;
    }
    .stat-badge-up {
        background: rgba(22,163,74,0.1);
        color: #16a34a;
    }
    .stat-badge-neutral {
        background: rgba(100,116,139,0.1);
        color: #64748b;
    }

    /* ── KPI Financial Cards ──────────────────────────── */
    .kpi-financial {
        position: relative;
        overflow: hidden;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .kpi-financial:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.07);
    }
    .kpi-financial .kpi-value {
        font-size: 1.5rem;
        font-weight: 800;
        line-height: 1.2;
    }
    .kpi-financial .kpi-label {
        font-size: 0.8rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }
    .kpi-financial .kpi-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
    }
    .kpi-green .kpi-value { color: #16a34a; }
    .kpi-green .kpi-label { color: #16a34a; }
    .kpi-green .kpi-icon { background: rgba(22,163,74,0.1); color: #16a34a; }

    .kpi-orange .kpi-value { color: #ea580c; }
    .kpi-orange .kpi-label { color: #ea580c; }
    .kpi-orange .kpi-icon { background: rgba(234,88,12,0.1); color: #ea580c; }

    .kpi-blue .kpi-value { color: var(--primary, #1e40af); }
    .kpi-blue .kpi-label { color: var(--primary, #1e40af); }
    .kpi-blue .kpi-icon { background: rgba(30,64,175,0.1); color: var(--primary, #1e40af); }

    /* ── Fade-in Animations ───────────────────────────── */
    .dash-fade-in {
        opacity: 0;
        transform: translateY(12px);
        animation: dashFadeUp 0.45s ease forwards;
    }
    @keyframes dashFadeUp {
        to { opacity: 1; transform: translateY(0); }
    }
    .dash-delay-1 { animation-delay: 0.05s; }
    .dash-delay-2 { animation-delay: 0.10s; }
    .dash-delay-3 { animation-delay: 0.15s; }
    .dash-delay-4 { animation-delay: 0.20s; }
    .dash-delay-5 { animation-delay: 0.25s; }
    .dash-delay-6 { animation-delay: 0.30s; }
    .dash-delay-7 { animation-delay: 0.35s; }
    .dash-delay-8 { animation-delay: 0.40s; }
    .dash-delay-9 { animation-delay: 0.45s; }

    /* ── User Avatar ──────────────────────────────────── */
    .user-avatar {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 1rem;
        color: #fff;
        background: linear-gradient(135deg, var(--primary, #1e40af) 0%, #3b82f6 100%);
        letter-spacing: -0.01em;
        flex-shrink: 0;
    }
</style>
@endsection

@section('content')

    {{-- ─── Page Header ────────────────────────────────────── --}}
    <header class="d-flex justify-content-between align-items-center mb-4 dash-fade-in dash-delay-1">
        <div>
            <h2 class="page-title mb-1">Bienvenido, {{ auth()->user()->name ?? 'Administrador' }}</h2>
            <p class="page-subtitle mb-0">Resumen operativo &middot; {{ \Carbon\Carbon::now()->isoFormat('dddd D [de] MMMM, YYYY') }}</p>
        </div>

        <div class="d-flex align-items-center gap-2 ps-3">
            <div class="text-end d-none d-md-block">
                <p class="mb-0 fw-semibold" style="font-size:0.875rem;">{{ auth()->user()->name ?? 'Usuario' }}</p>
                <p class="mb-0" style="font-size:0.75rem;color:var(--text-muted);">{{ ucfirst(auth()->user()->role ?? 'taller') }}</p>
            </div>
            <div class="user-avatar">
                {{ strtoupper(mb_substr(auth()->user()->name ?? 'U', 0, 1)) }}
            </div>
        </div>
    </header>

    {{-- ─── Stats Grid (6 cards) ──────────────────────────── --}}
    <div class="row g-3 mb-4">

        {{-- Clientes --}}
        <div class="col-md-4 col-xl-2">
            <div class="card p-3 dashboard-stat card-hover dash-fade-in dash-delay-2">
                <div class="d-flex align-items-start justify-content-between mb-2">
                    <p class="stat-label mb-0">Clientes</p>
                    <div class="stat-icon stat-icon-primary">
                        <i class="bi bi-person-lines-fill"></i>
                    </div>
                </div>
                <h3 class="stat-value outfit mb-1">{{ $stats['total_clients'] }}</h3>
                <span class="stat-badge stat-badge-neutral"><i class="bi bi-circle-fill" style="font-size:0.4rem;"></i> activo</span>
            </div>
        </div>

        {{-- Vehiculos --}}
        <div class="col-md-4 col-xl-2">
            <div class="card p-3 dashboard-stat card-hover dash-fade-in dash-delay-3">
                <div class="d-flex align-items-start justify-content-between mb-2">
                    <p class="stat-label mb-0">Vehiculos</p>
                    <div class="stat-icon stat-icon-info">
                        <i class="bi bi-car-front-fill"></i>
                    </div>
                </div>
                <h3 class="stat-value outfit mb-1">{{ $stats['total_vehicles'] }}</h3>
                <span class="stat-badge stat-badge-neutral"><i class="bi bi-circle-fill" style="font-size:0.4rem;"></i> activo</span>
            </div>
        </div>

        {{-- OTs Pendientes --}}
        <div class="col-md-4 col-xl-2">
            <div class="card p-3 dashboard-stat card-hover dash-fade-in dash-delay-4">
                <div class="d-flex align-items-start justify-content-between mb-2">
                    <p class="stat-label mb-0">OTs Pendientes</p>
                    <div class="stat-icon stat-icon-warning">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                </div>
                <h3 class="stat-value outfit mb-1">{{ $stats['pending_ots'] }}</h3>
                @if($stats['pending_ots'] > 0)
                    <span class="stat-badge" style="background:rgba(245,158,11,0.1);color:#f59e0b;"><i class="bi bi-arrow-up-short"></i> pendiente</span>
                @else
                    <span class="stat-badge stat-badge-up"><i class="bi bi-check2"></i> al dia</span>
                @endif
            </div>
        </div>

        {{-- OTs Aprobadas --}}
        <div class="col-md-4 col-xl-2">
            <div class="card p-3 dashboard-stat card-hover dash-fade-in dash-delay-5">
                <div class="d-flex align-items-start justify-content-between mb-2">
                    <p class="stat-label mb-0">OTs Aprobadas</p>
                    <div class="stat-icon stat-icon-success">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                </div>
                <h3 class="stat-value outfit mb-1">{{ $stats['approved_ots'] }}</h3>
                <span class="stat-badge stat-badge-up"><i class="bi bi-arrow-up-short"></i> activo</span>
            </div>
        </div>

        {{-- Esperando Repuestos --}}
        <div class="col-md-4 col-xl-2">
            <div class="card p-3 dashboard-stat card-hover dash-fade-in dash-delay-6">
                <div class="d-flex align-items-start justify-content-between mb-2">
                    <p class="stat-label mb-0">Esperando Repuestos</p>
                    <div class="stat-icon stat-icon-orange">
                        <i class="bi bi-box-seam"></i>
                    </div>
                </div>
                <h3 class="stat-value outfit mb-1">{{ $stats['waiting_parts'] }}</h3>
                @if($stats['waiting_parts'] > 0)
                    <span class="stat-badge" style="background:rgba(234,88,12,0.1);color:#ea580c;"><i class="bi bi-clock"></i> en espera</span>
                @else
                    <span class="stat-badge stat-badge-up"><i class="bi bi-check2"></i> completo</span>
                @endif
            </div>
        </div>

        {{-- En Reparacion --}}
        <div class="col-md-4 col-xl-2">
            <div class="card p-3 dashboard-stat card-hover dash-fade-in dash-delay-7">
                <div class="d-flex align-items-start justify-content-between mb-2">
                    <p class="stat-label mb-0">En Reparacion</p>
                    <div class="stat-icon stat-icon-primary">
                        <i class="bi bi-wrench"></i>
                    </div>
                </div>
                <h3 class="stat-value outfit mb-1">{{ $stats['in_repair'] }}</h3>
                @if($stats['in_repair'] > 0)
                    <span class="stat-badge" style="background:rgba(30,64,175,0.1);color:var(--primary,#1e40af);"><i class="bi bi-gear-fill" style="font-size:0.5rem;"></i> en curso</span>
                @else
                    <span class="stat-badge stat-badge-neutral"><i class="bi bi-dash"></i> sin actividad</span>
                @endif
            </div>
        </div>

    </div>

    {{-- ─── KPIs Financieros ──────────────────────────────── --}}
    <div class="row g-3 mb-4">

        {{-- Total Facturado --}}
        <div class="col-md-4">
            <div class="card p-4 kpi-financial kpi-green dash-fade-in dash-delay-7">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="kpi-label mb-1">Total Facturado</p>
                        <p class="kpi-value mb-0 outfit">${{ number_format($stats['total_revenue'], 0, ',', '.') }}</p>
                    </div>
                    <div class="kpi-icon">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Monto Pendiente --}}
        <div class="col-md-4">
            <div class="card p-4 kpi-financial kpi-orange dash-fade-in dash-delay-8">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="kpi-label mb-1">Monto Pendiente</p>
                        <p class="kpi-value mb-0 outfit">${{ number_format($stats['total_pending_amount'], 0, ',', '.') }}</p>
                    </div>
                    <div class="kpi-icon">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Ticket Promedio --}}
        <div class="col-md-4">
            @php
                $ticketPromedio = $stats['approved_ots'] > 0
                    ? $stats['total_revenue'] / $stats['approved_ots']
                    : 0;
            @endphp
            <div class="card p-4 kpi-financial kpi-blue dash-fade-in dash-delay-9">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="kpi-label mb-1">Ticket Promedio</p>
                        <p class="kpi-value mb-0 outfit">${{ number_format($ticketPromedio, 0, ',', '.') }}</p>
                    </div>
                    <div class="kpi-icon">
                        <i class="bi bi-receipt"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ─── Grafico de Ingresos ───────────────────────────── --}}
    <div class="row g-3 mb-4">
        <div class="col-12">
            <div class="card p-4 dash-fade-in dash-delay-8">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h5 class="fw-bold mb-0 ls-tight">Ingresos Mensuales</h5>
                        <p class="text-xs mb-0" style="color:var(--text-muted);margin-top:2px;">OTs facturadas</p>
                    </div>
                    <span class="px-3 py-1 rounded-pill text-xs fw-600"
                        style="background:var(--border-light);color:var(--text-secondary);font-weight:600;">
                        Ultimos 6 meses
                    </span>
                </div>
                <div style="position: relative; height: 280px;">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- ─── Tabla Ultimas OT ──────────────────────────────── --}}
    <div class="card dash-fade-in dash-delay-9">

        <div class="d-flex justify-content-between align-items-center p-4 border-bottom"
            style="border-color:var(--border-light)!important;">
            <div>
                <h5 class="fw-bold mb-0 ls-tight">Ultimas Ordenes de Trabajo</h5>
                <p class="text-xs mb-0" style="color:var(--text-muted);margin-top:2px;">Actividad reciente del taller</p>
            </div>
            @if(auth()->user()->role !== 'taller')
            <a href="{{ route('work-orders.create') }}" class="btn-primary-premium">
                <i class="bi bi-plus-lg"></i> Nueva OT
            </a>
            @endif
        </div>

        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Folio</th>
                        <th>Cliente</th>
                        <th>Vehiculo</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th class="text-end">Total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stats['recent_work_orders']->take(5) as $q)
                        <tr>
                            <td>
                                <span class="fw-700 text-sm ls-tight"
                                    style="font-weight:700;color:var(--text-primary);">
                                    {{ $q->folio ? '#' . $q->folio : 'Sin Folio' }}
                                </span>
                            </td>
                            <td class="text-sm fw-500" style="font-weight:500;">
                                {{ $q->client->name ?? '-' }}
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-600 text-sm" style="font-weight:600;">
                                        {{ $q->vehicle->license_plate ?? '-' }}
                                    </span>
                                    <span class="text-xs" style="color:var(--text-muted);">
                                        {{ ($q->vehicle->brand ?? '') . ' ' . ($q->vehicle->model ?? '') }}
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
                                <a href="{{ route('work-orders.show', $q) }}"
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
                                    No se encontraron ordenes de trabajo registradas.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-3 text-center border-top" style="border-color:var(--border-light)!important;">
            <a href="{{ route('work-orders.index') }}"
                class="text-sm fw-600 text-decoration-none"
                style="color:var(--primary);font-weight:600;">
                Ver todas las OT <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

    </div>

@endsection

@section('scripts')
    <script src="/vendor/chart.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('revenueChart').getContext('2d');

            // Gradiente para el area del grafico
            const gradient = ctx.createLinearGradient(0, 0, 0, 280);
            gradient.addColorStop(0, 'rgba(30, 64, 175, 0.15)');
            gradient.addColorStop(0.5, 'rgba(59, 130, 246, 0.06)');
            gradient.addColorStop(1, 'rgba(59, 130, 246, 0.0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($stats['chartData']['labels']) !!},
                    datasets: [{
                        label: 'Ingresos (CLP)',
                        data: {!! json_encode($stats['chartData']['values']) !!},
                        borderColor: '#1e40af',
                        backgroundColor: gradient,
                        borderWidth: 2.5,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#1e40af',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        pointHoverBackgroundColor: '#1e40af',
                        pointHoverBorderColor: '#fff',
                        pointHoverBorderWidth: 3,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0d1520',
                            titleColor: '#94a3b8',
                            bodyColor: '#ffffff',
                            bodyFont: { weight: '700', size: 13 },
                            padding: 12,
                            cornerRadius: 10,
                            displayColors: false,
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
