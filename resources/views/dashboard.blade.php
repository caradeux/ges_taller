<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ges_Taller | Gestión de Taller Automotriz</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts: Inter & Outfit -->
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;600;700&display=swap"
        rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --secondary: #64748b;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #0ea5e9;
            --bg-main: #f8fafc;
            --sidebar-bg: #0f172a;
            --card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.04), 0 4px 6px -2px rgba(0, 0, 0, 0.01);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-main);
            color: #1e293b;
            overflow-x: hidden;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        .outfit {
            font-family: 'Outfit', sans-serif;
        }

        .sidebar {
            height: 100vh;
            background: var(--sidebar-bg);
            color: #94a3b8;
            padding: 2rem 1.25rem;
            position: fixed;
            width: 260px;
            transition: var(--transition);
            z-index: 1000;
        }

        .sidebar .brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: white;
            margin-bottom: 3rem;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar .nav-link {
            color: #94a3b8;
            font-weight: 500;
            padding: 0.85rem 1rem;
            border-radius: 0.75rem;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: var(--transition);
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: rgba(255, 255, 255, 0.08);
            color: white;
        }

        .sidebar .nav-link.active {
            background-color: var(--primary);
            color: white;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
        }

        .main-content {
            margin-left: 260px;
            padding: 2.5rem 3rem;
            min-height: 100vh;
        }

        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 3rem;
        }

        .card {
            border: none;
            border-radius: 1rem;
            background: white;
            box-shadow: var(--card-shadow);
        }

        .stat-card {
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1.25rem;
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .bg-primary-soft {
            background-color: #eff6ff;
            color: var(--primary);
        }

        .bg-success-soft {
            background-color: #f0fdf4;
            color: var(--success);
        }

        .bg-warning-soft {
            background-color: #fffbeb;
            color: var(--warning);
        }

        .bg-danger-soft {
            background-color: #fef2f2;
            color: var(--danger);
        }

        .btn-primary-premium {
            background: var(--primary);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            font-weight: 600;
            border: none;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary-premium:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.2);
            color: white;
        }

        .table thead th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.7rem;
            letter-spacing: 0.05em;
            color: #64748b;
            background: #f8fafc;
            padding: 1rem;
            border: none;
        }

        .table tbody td {
            padding: 1.25rem 1rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .badge-pill {
            padding: 0.4rem 0.85rem;
            border-radius: 50rem;
            font-weight: 600;
            font-size: 0.7rem;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .badge-borrador {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-aprobado {
            background: #dcfce7;
            color: #166534;
        }

        .badge-pendiente {
            background: #e0f2fe;
            color: #075985;
        }

        .badge-rechazado {
            background: #fee2e2;
            color: #991b1b;
        }

        .search-bar {
            background: white;
            border-radius: 0.75rem;
            padding: 0.65rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 12px;
            width: 380px;
            border: 1px solid #e2e8f0;
        }

        .search-bar input {
            border: none;
            outline: none;
            width: 100%;
            font-size: 0.9rem;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            padding-left: 1rem;
            border-left: 1px solid #e2e8f0;
        }

        .animate-in {
            animation: slideUp 0.6s ease forwards;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="brand px-2">
            <i class="bi bi-shield-shaded fs-3 text-primary"></i>
            <span>Ges_Taller</span>
        </div>

        <nav class="nav flex-column mt-4">
            <a class="nav-link active" href="/"><i class="bi bi-grid-fill"></i> Panel General</a>
            <a class="nav-link" href="#"><i class="bi bi-receipt"></i> Presupuestos</a>
            <a class="nav-link" href="#"><i class="bi bi-people-fill"></i> Clientes</a>
            <a class="nav-link" href="#"><i class="bi bi-car-front-fill"></i> Vehículos</a>
            <a class="nav-link" href="#"><i class="bi bi-building-fill"></i> Compañías</a>
            <a class="nav-link" href="#"><i class="bi bi-gear-fill mt-auto"></i> Ajustes</a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <header class="header-section">
            <div>
                <h2 class="fw-bold mb-1">Bienvenido, {{ auth()->user()->name ?? 'Administrador' }}</h2>
                <p class="text-secondary small mb-0">Resumen operativo para el día de hoy.</p>
            </div>

            <div class="d-flex align-items-center gap-4">
                <div class="search-bar">
                    <i class="bi bi-search text-secondary"></i>
                    <input type="text" placeholder="Buscar patente, RUT o folio...">
                </div>

                <div class="user-profile">
                    <div class="text-end">
                        <p class="mb-0 fw-semibold small">Taller Lira</p>
                        <p class="mb-0 text-secondary tiny" style="font-size: 0.7rem;">Operador Senior</p>
                    </div>
                    <img src="https://ui-avatars.com/api/?name=Admin&background=2563eb&color=fff" class="rounded-circle"
                        width="38" alt="">
                </div>
            </div>
        </header>

        <!-- Stats Grid -->
        <div class="row g-4 mb-5 animate-in">
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="stat-icon bg-primary-soft">
                        <i class="bi bi-people"></i>
                    </div>
                    <div>
                        <p class="text-secondary small fw-medium mb-1">Cientes Totales</p>
                        <h4 class="fw-bold mb-0 outfit">{{ $stats['total_clients'] }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="stat-icon bg-success-soft">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div>
                        <p class="text-secondary small fw-medium mb-1">Aprobados</p>
                        <h4 class="fw-bold mb-0 outfit">{{ $stats['approved_quotations'] }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="stat-icon bg-warning-soft">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div>
                        <p class="text-secondary small fw-medium mb-1">Pendientes</p>
                        <h4 class="fw-bold mb-0 outfit">{{ $stats['pending_quotations'] }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="stat-icon bg-danger-soft">
                        <i class="bi bi-tools"></i>
                    </div>
                    <div>
                        <p class="text-secondary small fw-medium mb-1">En Taller</p>
                        <h4 class="fw-bold mb-0 outfit">{{ $stats['total_vehicles'] }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="card animate-in" style="animation-delay: 0.1s;">
            <div class="p-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Últimos Presupuestos</h5>
                <button class="btn-primary-premium">
                    <i class="bi bi-plus-lg"></i> Nuevo Presupuesto
                </button>
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
                                        <span class="tiny text-secondary"
                                            style="font-size: 0.7rem;">{{ $q->vehicle->brand }}
                                            {{ $q->vehicle->model }}</span>
                                    </div>
                                </td>
                                <td class="text-secondary small">{{ \Carbon\Carbon::parse($q->date)->format('d/m/Y') }}</td>
                                <td>
                                    @php
                                        $statusLabel = match ($q->status) {
                                            'draft' => 'Borrador',
                                            'approved' => 'Aprobado',
                                            'sent' => 'Pendiente',
                                            'rejected' => 'Rechazado',
                                            default => 'Pendiente'
                                        };
                                        $badgeClass = 'badge-' . strtolower($statusLabel);
                                    @endphp
                                    <span class="badge-pill {{ $badgeClass }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                                <td class="text-end fw-bold text-dark">
                                    ${{ number_format($q->total_amount, 0, ',', '.') }}
                                </td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-light p-1 px-2 border-0 bg-transparent text-secondary">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
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
                <a href="#" class="btn btn-link btn-sm text-decoration-none text-primary fw-semibold">Ver todos los
                    presupuestos</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>