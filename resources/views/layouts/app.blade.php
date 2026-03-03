<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ges_Taller | @yield('title', 'Gestión de Taller')</title>

    <!-- Bootstrap 5 CSS (local) -->
    <link href="/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons (local) -->
    <link rel="stylesheet" href="/vendor/bootstrap-icons/bootstrap-icons.css">

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
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: var(--bg-main);
            color: #1e293b;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6, .outfit {
            font-family: "Segoe UI", -apple-system, BlinkMacSystemFont, Roboto, "Helvetica Neue", Arial, sans-serif;
            font-weight: 600;
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
            overflow-y: auto;
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

        .card {
            border: none;
            border-radius: 1rem;
            background: white;
            box-shadow: var(--card-shadow);
        }

        .btn-primary-premium {
            background: var(--primary);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            font-weight: 600;
            border: none;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-primary-premium:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.2);
            color: white;
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
    </style>
    @yield('styles')
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="brand px-2">
            <i class="bi bi-shield-shaded fs-3 text-primary"></i>
            <span>Ges_Taller</span>
        </div>

        @php $role = auth()->user()?->role ?? 'taller'; @endphp

        {{-- Branch switcher for admin --}}
        @if($role === 'admin')
        <div class="px-1 mb-1">
            <form method="POST" action="{{ route('branch.switch') }}">
                @csrf
                <select name="branch_id"
                    style="background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.12);color:#94a3b8;border-radius:0.6rem;font-size:0.78rem;padding:0.45rem 0.75rem;width:100%;cursor:pointer;"
                    onchange="this.form.submit()">
                    <option value="" style="background:#0f172a;">Todas las sucursales</option>
                    @foreach($branches as $b)
                        <option value="{{ $b->id }}" style="background:#0f172a;"
                            {{ session('active_branch_id') == $b->id ? 'selected' : '' }}>
                            {{ $b->name }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
        @else
        @php $userBranch = auth()->user()?->branch; @endphp
        @if($userBranch)
        <div class="px-1 mb-1">
            <div style="background:rgba(255,255,255,0.05);border-radius:0.6rem;padding:0.45rem 0.75rem;">
                <div style="font-size:0.65rem;color:#475569;text-transform:uppercase;letter-spacing:0.05em;font-weight:700;">Sucursal</div>
                <div style="font-size:0.82rem;color:#94a3b8;margin-top:2px;">{{ $userBranch->name }}</div>
            </div>
        </div>
        @endif
        @endif

        <nav class="nav flex-column mt-3">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"><i
                    class="bi bi-grid-fill"></i> Panel General</a>
            <a class="nav-link {{ request()->routeIs('quotations.*') ? 'active' : '' }}"
                href="{{ route('quotations.index') }}"><i class="bi bi-receipt"></i> Presupuestos</a>

            @if(in_array($role, ['admin','recepcion','taller']))
            <a class="nav-link {{ request()->routeIs('clients.*') ? 'active' : '' }}"
                href="{{ route('clients.index') }}"><i class="bi bi-people-fill"></i> Clientes</a>
            <a class="nav-link {{ request()->routeIs('vehicles.*') ? 'active' : '' }}"
                href="{{ route('vehicles.index') }}"><i class="bi bi-car-front-fill"></i> Vehículos</a>
            @endif

            @if(in_array($role, ['admin','recepcion']))
            <a class="nav-link {{ request()->is('liquidators*') ? 'active' : '' }}"
                href="{{ route('liquidators.index') }}"><i class="bi bi-person-badge-fill"></i> Liquidadores</a>
            <a class="nav-link {{ request()->is('insurance-companies*') ? 'active' : '' }}"
                href="{{ route('insurance-companies.index') }}"><i class="bi bi-building-fill"></i> Compañías</a>
            <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}"
                href="{{ route('reports.index') }}"><i class="bi bi-bar-chart-fill"></i> Reportes</a>
            @endif

            {{-- Admin section --}}
            @if($role === 'admin')
            <div class="mt-3 mb-1 px-1">
                <span style="font-size:0.65rem;font-weight:700;letter-spacing:0.1em;color:#475569;text-transform:uppercase;">Administración</span>
            </div>
            <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}"
                href="{{ route('users.index') }}"><i class="bi bi-people-fill"></i> Usuarios</a>
            <a class="nav-link {{ request()->routeIs('branches.*') ? 'active' : '' }}"
                href="{{ route('branches.index') }}"><i class="bi bi-building-fill-gear"></i> Sucursales</a>
            <a class="nav-link {{ request()->routeIs('service-items.*') ? 'active' : '' }}"
                href="{{ route('service-items.index') }}"><i class="bi bi-clipboard2-pulse-fill"></i> Catálogo Servicios</a>
            <a class="nav-link {{ request()->is('vehicle-brands*') ? 'active' : '' }}"
                href="{{ route('vehicle-brands.index') }}"><i class="bi bi-signpost-split-fill"></i> Marcas / Modelos</a>
            @endif

            <a class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}"
                href="{{ route('profile.index') }}"><i class="bi bi-gear-fill"></i> Ajustes</a>

            <div class="mt-3 pt-4 border-top border-secondary border-opacity-25 pb-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-link text-danger border-0 bg-transparent w-100 text-start">
                        <i class="bi bi-box-arrow-left"></i> Cerrar Sesión
                    </button>
                </form>
            </div>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert"
                style="border-radius: 1rem;">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert"
                style="border-radius: 1rem;">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>

</html>