@extends('layouts.app')

@section('title', 'Dashboard Profesional')

@section('content')
    <header class="d-flex justify-content-between align-items-center mb-5 mt-2 animate-in">
        <div>
            <h2 class="fw-bold mb-1">Bienvenido, {{ auth()->user()->name ?? 'Administrador' }}</h2>
            <p class="text-secondary small mb-0">Resumen operativo para el día de hoy.</p>
        </div>

        <div class="d-flex align-items-center gap-4">
            <div class="bg-white rounded-pill px-3 py-2 border shadow-sm d-flex align-items-center gap-3"
                style="width: 380px;">
                <i class="bi bi-search text-secondary"></i>
                <input type="text" class="border-0 bg-transparent w-100 outline-none"
                    placeholder="Buscar patente, RUT o folio..." style="outline: none;">
            </div>

            <div class="d-flex align-items-center gap-3 ps-4 border-start">
                <div class="text-end">
                    <p class="mb-0 fw-semibold small">Taller Lira</p>
                    <p class="mb-0 text-secondary tiny" style="font-size: 0.7rem;">Operador Senior</p>
                </div>
                <img src="https://ui-avatars.com/api/?name=Admin&background=2563eb&color=fff" class="rounded-circle"
                    width="42" alt="">
            </div>
        </div>
    </header>

    <!-- Stats Grid -->
    <div class="row g-4 mb-4 animate-in" style="animation-delay: 0.1s;">
        <div class="col-md-3">
            <div class="card p-4 d-flex flex-row align-items-center gap-3">
                <div class="rounded-4 d-flex align-items-center justify-content-center"
                    style="width: 56px; height: 56px; background-color: #eff6ff; color: var(--primary);">
                    <i class="bi bi-people fs-4"></i>
                </div>
                <div>
                    <p class="text-secondary small fw-medium mb-0">Clientes</p>
                    <h4 class="fw-bold mb-0 outfit">{{ $stats['total_clients'] }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-4 d-flex flex-row align-items-center gap-3">
                <div class="rounded-4 d-flex align-items-center justify-content-center"
                    style="width: 56px; height: 56px; background-color: #f0fdf4; color: var(--success);">
                    <i class="bi bi-check-circle fs-4"></i>
                </div>
                <div>
                    <p class="text-secondary small fw-medium mb-0">Aprobados</p>
                    <h4 class="fw-bold mb-0 outfit">{{ $stats['approved_quotations'] }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-4 d-flex flex-row align-items-center gap-3">
                <div class="rounded-4 d-flex align-items-center justify-content-center"
                    style="width: 56px; height: 56px; background-color: #fffbeb; color: var(--warning);">
                    <i class="bi bi-clock-history fs-4"></i>
                </div>
                <div>
                    <p class="text-secondary small fw-medium mb-0">Pendientes</p>
                    <h4 class="fw-bold mb-0 outfit">{{ $stats['pending_quotations'] }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-4 d-flex flex-row align-items-center gap-3">
                <div class="rounded-4 d-flex align-items-center justify-content-center"
                    style="width: 56px; height: 56px; background-color: #fef2f2; color: var(--danger);">
                    <i class="bi bi-currency-dollar fs-4"></i>
                </div>
                <div>
                    <p class="text-secondary small fw-medium mb-0">Total Mensual</p>
                    <h4 class="fw-bold mb-0 outfit">${{ number_format($stats['total_revenue'], 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-5 animate-in" style="animation-delay: 0.2s;">
        <div class="col-md-8">
            <div class="card h-100 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0">Ingresos Mensuales</h5>
                    <span class="badge bg-light text-secondary">Últimos 6 meses</span>
                </div>
                <div style="position: relative; height: 260px;">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 p-4 d-flex flex-column justify-content-between">
                <div>
                    <h5 class="fw-bold mb-4">Métricas Rápidas</h5>
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-secondary small">Eficiencia de Cierre</span>
                            <span class="text-dark fw-bold small">75%</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-primary" style="width: 75%"></div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-secondary small">Vehículos en Taller</span>
                            <span class="text-dark fw-bold small">{{ $stats['total_vehicles'] }}</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-success" style="width: 60%"></div>
                        </div>
                    </div>
                </div>
                <div class="bg-light p-3 rounded-4 mb-3">
                    <p class="text-secondary small mb-1">Total Facturado</p>
                    <h4 class="fw-bold mb-0 outfit text-success">
                        ${{ number_format($stats['total_revenue'], 0, ',', '.') }}
                    </h4>
                </div>
                <div class="bg-light p-3 rounded-4">
                    <p class="text-secondary small mb-1">Monto Pendiente</p>
                    <h4 class="fw-bold mb-0 outfit text-warning">
                        ${{ number_format($stats['total_pending_amount'], 0, ',', '.') }}
                    </h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="card animate-in" style="animation-delay: 0.3s;">
        <div class="p-4 d-flex justify-content-between align-items-center border-bottom">
            <h5 class="fw-bold mb-0">Últimos Presupuestos</h5>
            <a href="{{ route('quotations.create') }}" class="btn-primary-premium py-2 px-3 small">
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
                            <td><span class="fw-bold text-dark">#{{ $q->folio }}</span></td>
                            <td class="small fw-medium">{{ $q->client->name }}</td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-semibold small">{{ $q->vehicle->license_plate }}</span>
                                    <span class="tiny text-secondary" style="font-size: 0.7rem;">{{ $q->vehicle->brand }}
                                        {{ $q->vehicle->model }}</span>
                                </div>
                            </td>
                            <td class="text-secondary small">{{ \Carbon\Carbon::parse($q->date)->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge rounded-pill px-3 py-2 fw-semibold" style="font-size: 0.65rem; 
                                            @if($q->status == 'draft') background-color: #fef3c7; color: #92400e;
                                            @elseif($q->status == 'approved') background-color: #dcfce7; color: #166534;
                                            @elseif($q->status == 'sent') background-color: #e0f2fe; color: #075985;
                                            @elseif($q->status == 'rejected') background-color: #fee2e2; color: #991b1b;
                                            @else background-color: #f1f5f9; color: #475569; @endif">
                                    {{ $q->status_label }}
                                </span>
                            </td>
                            <td class="text-end fw-bold text-dark">
                                ${{ number_format($q->total_amount, 0, ',', '.') }}
                            </td>
                            <td class="text-end">
                                <a href="{{ route('quotations.show', $q) }}"
                                    class="btn btn-sm btn-light p-1 px-2 border-0 bg-transparent text-secondary">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="bi bi-inbox fs-1 text-light"></i>
                                <p class="text-secondary mt-2">No se encontraron presupuestos registrados.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3 text-center border-top">
            <a href="{{ route('quotations.index') }}"
                class="btn btn-link btn-sm text-decoration-none text-primary fw-semibold">Ver todos los
                presupuestos</a>
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
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.1)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#2563eb',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#f1f5f9'
                            },
                            ticks: {
                                callback: function (value) {
                                    return '$' + value.toLocaleString('es-CL');
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection