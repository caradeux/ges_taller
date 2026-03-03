<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ges_Taller - Dashboard Premium</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #64748b;
            --success-color: #10b981;
            --bg-body: #f8fafc;
            --glass-bg: rgba(255, 255, 255, 0.8);
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-body);
            color: #1e293b;
        }

        .navbar {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 1rem 2rem;
        }

        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease;
            background: #ffffff;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .stat-card {
            padding: 1.5rem;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .bg-primary-soft {
            background-color: #e0e7ff;
            color: #4338ca;
        }

        .bg-success-soft {
            background-color: #dcfce7;
            color: #15803d;
        }

        .bg-warning-soft {
            background-color: #fef9c3;
            color: #a16207;
        }

        .bg-info-soft {
            background-color: #e0f2fe;
            color: #0369a1;
        }

        .sidebar {
            height: 100vh;
            border-right: 1px solid rgba(0, 0, 0, 0.05);
            padding: 2rem 1rem;
        }

        .nav-link {
            color: var(--secondary-color);
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            margin-bottom: 0.25rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 500;
        }

        .nav-link:hover,
        .nav-link.active {
            background-color: #eff6ff;
            color: var(--primary-color);
        }

        .table-premium thead th {
            background-color: #f1f5f9;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            color: var(--secondary-color);
            border: none;
        }

        .badge-status {
            padding: 0.35em 0.65em;
            border-radius: 50rem;
            font-weight: 600;
            font-size: 0.75rem;
        }
    </style>
</head>

<body>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 d-none d-md-block sidebar bg-white">
                <div class="d-flex align-items-center mb-4 px-2">
                    <i class="bi bi-tools text-primary fs-3 me-2"></i>
                    <h4 class="fw-bold mb-0">Ges_Taller</h4>
                </div>

                <nav class="nav flex-column">
                    <a class="nav-link active" href="/"><i class="bi bi-speedometer2"></i> Dashboard</a>
                    <a class="nav-link" href="#"><i class="bi bi-people"></i> Clientes</a>
                    <a class="nav-link" href="#"><i class="bi bi-car-front"></i> Vehículos</a>
                    <a class="nav-link" href="#"><i class="bi bi-file-earmark-pdf"></i> Presupuestos</a>
                    <hr>
                    <a class="nav-link" href="#"><i class="bi bi-gear"></i> Configuración</a>
                </nav>
            </div>

            <!-- Main Content -->
            <main class="col-md-10 ms-sm-auto px-md-4 py-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4">
                    <h1 class="h2 fw-bold">Resumen General</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <button type="button" class="btn btn-primary d-flex align-items-center gap-2">
                            <i class="bi bi-plus-lg"></i> Nuevo Presupuesto
                        </button>
                    </div>
                </div>

                <!-- Stats Rows -->
                <div class="row g-4 mb-5">
                    <div class="col-md-3">
                        <div class="card stat-card">
                            <div class="stat-icon bg-primary-soft text-primary">
                                <i class="bi bi-people"></i>
                            </div>
                            <h6 class="text-secondary small fw-bold">CLIENTES</h6>
                            <h2 class="fw-bold mb-0">{{ $stats['total_clients'] }}</h2>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card">
                            <div class="stat-icon bg-info-soft text-info">
                                <i class="bi bi-car-front"></i>
                            </div>
                            <h6 class="text-secondary small fw-bold">VEHÍCULOS</h6>
                            <h2 class="fw-bold mb-0">{{ $stats['total_vehicles'] }}</h2>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card">
                            <div class="stat-icon bg-warning-soft text-warning">
                                <i class="bi bi-hourglass-split"></i>
                            </div>
                            <h6 class="text-secondary small fw-bold">PENDIENTES</h6>
                            <h2 class="fw-bold mb-0">{{ $stats['pending_quotations'] }}</h2>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card">
                            <div class="stat-icon bg-success-soft text-success">
                                <i class="bi bi-check-circle"></i>
                            </div>
                            <h6 class="text-secondary small fw-bold">APROBADAS</h6>
                            <h2 class="fw-bold mb-0">{{ $stats['approved_quotations'] }}</h2>
                        </div>
                    </div>
                </div>

                <!-- Recent Quotations -->
                <div class="row">
                    <div class="col-12">
                        <div class="card p-4">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="fw-bold mb-0">Últimos Presupuestos</h5>
                                <a href="#" class="btn btn-link text-primary p-0">Ver todos <i
                                        class="bi bi-arrow-right"></i></a>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-premium align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>Folio</th>
                                            <th>Cliente</th>
                                            <th>Vehículo</th>
                                            <th>Fecha</th>
                                            <th>Estado</th>
                                            <th class="text-end">Monto</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($stats['recent_quotations'] as $q)
                                            <tr>
                                                <td class="fw-bold text-primary">#{{ $q->folio }}</td>
                                                <td>{{ $q->client->name }}</td>
                                                <td>{{ $q->vehicle->license_plate }} - {{ $q->vehicle->brand }}</td>
                                                <td>{{ $q->date }}</td>
                                                <td>
                                                    <span class="badge badge-status bg-info-soft text-info">
                                                        {{ ucfirst($q->status) }}
                                                    </span>
                                                </td>
                                                <td class="text-end fw-bold">
                                                    ${{ number_format($q->total_amount, 0, ',', '.') }}</td>
                                                <td class="text-end">
                                                    <button class="btn btn-sm btn-light border"><i
                                                            class="bi bi-three-dots"></i></button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-5">
                                                    <i class="bi bi-inbox fs-1 text-secondary opacity-25"></i>
                                                    <p class="text-secondary mt-2">No hay presupuestos recientes para
                                                        mostrar.</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>