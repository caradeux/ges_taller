<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0d1520">
    <title>GesTaller | @yield('title', 'Gestión de Taller')</title>

    <!-- Bootstrap 5 CSS (local) -->
    <link href="/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons (local) -->
    <link rel="stylesheet" href="/vendor/bootstrap-icons/bootstrap-icons.css">

    <style>
        /* ─── Design Tokens ──────────────────────────────────── */
        :root {
            /* Brand */
            --primary:         #1e40af;
            --primary-dark:    #1e3a8a;
            --primary-light:   #eff6ff;
            --primary-border:  #bfdbfe;

            /* Accent — automotive orange */
            --accent:          #ea580c;
            --accent-light:    #fff7ed;

            /* Semantic */
            --success:         #16a34a;
            --success-light:   #f0fdf4;
            --warning:         #d97706;
            --warning-light:   #fffbeb;
            --danger:          #dc2626;
            --danger-light:    #fef2f2;
            --info:            #0284c7;
            --info-light:      #f0f9ff;

            /* Layout */
            --bg-main:         #f0f2f5;
            --sidebar-bg:      #0d1520;
            --card-bg:         #ffffff;

            /* Typography */
            --text-primary:    #111827;
            --text-secondary:  #6b7280;
            --text-muted:      #9ca3af;

            /* Borders */
            --border:          #e5e7eb;
            --border-light:    #f3f4f6;

            /* Shadows */
            --shadow-sm:  0 1px 2px rgba(15, 23, 42, 0.06);
            --shadow:     0 1px 3px rgba(15, 23, 42, 0.08), 0 1px 2px rgba(15, 23, 42, 0.04);
            --shadow-md:  0 4px 14px rgba(15, 23, 42, 0.10), 0 2px 4px rgba(15, 23, 42, 0.04);
            --shadow-lg:  0 10px 28px rgba(15, 23, 42, 0.13), 0 4px 8px rgba(15, 23, 42, 0.06);
            --card-shadow: var(--shadow);

            /* Shape */
            --radius-sm: 0.5rem;
            --radius:    0.75rem;
            --radius-lg: 1rem;

            /* Motion */
            --transition:      all 0.18s ease;
            --transition-slow: all 0.32s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* ─── Base ───────────────────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: "Segoe UI Variable", "Segoe UI", -apple-system, BlinkMacSystemFont,
                         "Helvetica Neue", Arial, sans-serif;
            background-color: var(--bg-main);
            color: var(--text-primary);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            font-size: 0.9375rem;
            line-height: 1.5;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: inherit;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -0.02em;
            line-height: 1.25;
        }

        a { transition: var(--transition); }

        /* ─── Scrollbar ──────────────────────────────────────── */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #9ca3af; }

        /* ─── Sidebar ────────────────────────────────────────── */
        .sidebar {
            height: 100vh;
            background: var(--sidebar-bg);
            position: fixed;
            width: 260px;
            z-index: 1000;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            border-right: 1px solid rgba(255, 255, 255, 0.04);
        }

        /* Brand */
        .sidebar-brand {
            padding: 1.375rem 1.375rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            flex-shrink: 0;
            text-decoration: none;
        }

        .sidebar-brand-icon {
            width: 34px;
            height: 34px;
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1rem;
            flex-shrink: 0;
            box-shadow: 0 3px 10px rgba(30, 64, 175, 0.45);
        }

        .sidebar-brand-name {
            font-weight: 700;
            font-size: 0.975rem;
            color: white;
            letter-spacing: -0.01em;
            line-height: 1.2;
        }

        .sidebar-brand-tag {
            font-size: 0.575rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #475569;
            margin-top: 2px;
        }

        /* Body */
        .sidebar-body {
            padding: 0.875rem 0.875rem 0;
            flex: 1;
        }

        /* Branch widget */
        .sidebar-branch-widget {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.07);
            border-radius: var(--radius-sm);
            padding: 0.5rem 0.75rem;
            margin-bottom: 0.625rem;
        }

        .sidebar-branch-label {
            font-size: 0.575rem;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.09em;
            font-weight: 700;
            margin-bottom: 3px;
        }

        /* Section labels */
        .sidebar-section-label {
            display: block;
            font-size: 0.585rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            color: #334155;
            text-transform: uppercase;
            padding: 0 0.5rem;
            margin: 1rem 0 0.3rem;
        }

        /* Nav links */
        .sidebar .nav-link {
            color: #8b9ab5;
            font-weight: 500;
            font-size: 0.845rem;
            padding: 0.575rem 0.75rem;
            border-radius: var(--radius-sm);
            margin-bottom: 1px;
            display: flex;
            align-items: center;
            gap: 9px;
            transition: var(--transition);
            position: relative;
            text-decoration: none;
        }

        .sidebar .nav-link i {
            font-size: 0.95rem;
            width: 16px;
            text-align: center;
            flex-shrink: 0;
            opacity: 0.7;
            transition: var(--transition);
        }

        .sidebar .nav-link:hover {
            background: rgba(255, 255, 255, 0.06);
            color: #c8d3e0;
        }

        .sidebar .nav-link:hover i { opacity: 1; }

        .sidebar .nav-link.active {
            background: rgba(30, 64, 175, 0.65);
            color: white;
            font-weight: 600;
        }

        .sidebar .nav-link.active i { opacity: 1; }

        /* Orange accent indicator on active link */
        .sidebar .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 22%;
            height: 56%;
            width: 3px;
            background: var(--accent);
            border-radius: 0 2px 2px 0;
        }

        /* Footer */
        .sidebar-footer {
            padding: 0.875rem;
            border-top: 1px solid rgba(255, 255, 255, 0.06);
            flex-shrink: 0;
        }

        .sidebar-footer .nav-link { margin-bottom: 0; }

        /* ─── Main Content ───────────────────────────────────── */
        .main-content {
            margin-left: 260px;
            padding: 2rem 2.5rem;
            min-height: 100vh;
        }

        /* ─── Cards ──────────────────────────────────────────── */
        .card {
            border: 1px solid var(--border-light);
            border-radius: var(--radius-lg);
            background: white;
            box-shadow: var(--card-shadow);
            transition: var(--transition);
        }

        .card-hover:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-1px);
        }

        /* ─── Tables ─────────────────────────────────────────── */
        .table thead th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.67rem;
            letter-spacing: 0.07em;
            color: var(--text-muted);
            background: #f8f9fb;
            padding: 0.875rem 1rem;
            border-bottom: 1px solid var(--border);
            border-top: none;
            white-space: nowrap;
        }

        .table tbody td {
            padding: 0.9375rem 1rem;
            border-bottom: 1px solid var(--border-light);
            vertical-align: middle;
        }

        .table tbody tr:hover td { background-color: #f5f7ff; }
        .table tbody tr:last-child td { border-bottom: none; }

        /* ─── Buttons ────────────────────────────────────────── */
        .btn-primary-premium {
            background: var(--primary);
            color: white !important;
            padding: 0.5625rem 1.125rem;
            border-radius: var(--radius);
            font-weight: 600;
            font-size: 0.845rem;
            border: none;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            cursor: pointer;
            line-height: 1.4;
        }

        .btn-primary-premium:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(30, 64, 175, 0.35);
            color: white !important;
        }

        .btn-app-secondary {
            background: white;
            color: var(--text-secondary);
            padding: 0.5625rem 1.125rem;
            border-radius: var(--radius);
            font-weight: 500;
            font-size: 0.845rem;
            border: 1px solid var(--border);
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            cursor: pointer;
        }

        .btn-app-secondary:hover {
            background: #f9fafb;
            border-color: #d1d5db;
            color: var(--text-primary);
        }

        .btn-success-app {
            background: var(--success);
            color: white !important;
            padding: 0.5625rem 1.125rem;
            border-radius: var(--radius);
            font-weight: 600;
            font-size: 0.845rem;
            border: none;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            cursor: pointer;
            line-height: 1.4;
        }

        .btn-success-app:hover {
            background: #15803d;
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(22, 163, 74, 0.3);
            color: white !important;
        }

        .btn-danger-app {
            background: var(--danger);
            color: white !important;
            padding: 0.5625rem 1.125rem;
            border-radius: var(--radius);
            font-weight: 600;
            font-size: 0.845rem;
            border: none;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            cursor: pointer;
            line-height: 1.4;
        }

        .btn-danger-app:hover {
            background: #b91c1c;
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(220, 38, 38, 0.3);
            color: white !important;
        }

        .btn-accent-app {
            background: var(--accent);
            color: white !important;
            padding: 0.5625rem 1.125rem;
            border-radius: var(--radius);
            font-weight: 600;
            font-size: 0.845rem;
            border: none;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            cursor: pointer;
            line-height: 1.4;
        }

        .btn-accent-app:hover {
            background: #c2410c;
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(234, 88, 12, 0.3);
            color: white !important;
        }

        .btn-info-app {
            background: var(--info);
            color: white !important;
            padding: 0.5625rem 1.125rem;
            border-radius: var(--radius);
            font-weight: 600;
            font-size: 0.845rem;
            border: none;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            cursor: pointer;
            line-height: 1.4;
        }

        .btn-info-app:hover {
            background: #0369a1;
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(2, 132, 199, 0.3);
            color: white !important;
        }

        /* ─── Status Badges ──────────────────────────────────── */
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.225rem 0.625rem;
            border-radius: 9999px;
            font-size: 0.69rem;
            font-weight: 600;
            letter-spacing: 0.025em;
            white-space: nowrap;
        }

        .status-draft    { background: #fef3c7; color: #92400e; }
        .status-sent     { background: #dbeafe; color: #1d4ed8; }
        .status-approved { background: #dcfce7; color: #166534; }
        .status-rejected { background: #fee2e2; color: #991b1b; }
        .status-finished { background: #ede9fe; color: #6d28d9; }
        .status-invoiced { background: #d1fae5; color: #065f46; }

        /* ─── Form Controls ──────────────────────────────────── */
        .form-control,
        .form-select {
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            font-size: 0.875rem;
            color: var(--text-primary);
            padding: 0.5625rem 0.875rem;
            transition: var(--transition);
            background: white;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #6b98e3;
            box-shadow: 0 0 0 3px rgba(30, 64, 175, 0.1);
            outline: none;
        }

        .form-control::placeholder { color: var(--text-muted); }

        .form-label {
            font-size: 0.8125rem;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 0.35rem;
        }

        /* Input with icon */
        .input-icon-wrap { position: relative; }

        .input-icon-wrap > .bi {
            position: absolute;
            left: 0.875rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            pointer-events: none;
            font-size: 0.85rem;
        }

        .input-icon-wrap .form-control { padding-left: 2.375rem; }

        /* ─── Stat Cards ─────────────────────────────────────── */
        .stat-card {
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            border-radius: var(--radius-lg) var(--radius-lg) 0 0;
        }

        .stat-primary::before  { background: var(--primary); }
        .stat-success::before  { background: var(--success); }
        .stat-warning::before  { background: var(--warning); }
        .stat-accent::before   { background: var(--accent);  }
        .stat-info::before     { background: var(--info);    }

        .stat-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .stat-icon-primary { background: var(--primary-light); color: var(--primary); }
        .stat-icon-success { background: var(--success-light); color: var(--success); }
        .stat-icon-warning { background: var(--warning-light); color: var(--warning); }
        .stat-icon-accent  { background: var(--accent-light);  color: var(--accent);  }
        .stat-icon-info    { background: var(--info-light);    color: var(--info);    }

        .stat-value {
            font-size: 1.625rem;
            font-weight: 800;
            color: var(--text-primary);
            letter-spacing: -0.035em;
            line-height: 1.1;
            margin: 0;
        }

        .stat-label {
            font-size: 0.72rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin: 0;
        }

        /* ─── Page Header ────────────────────────────────────── */
        .page-header {
            margin-bottom: 1.75rem;
        }

        .page-title {
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -0.025em;
            margin-bottom: 0.2rem;
        }

        .page-subtitle {
            font-size: 0.8125rem;
            color: var(--text-muted);
            margin: 0;
            font-weight: 400;
        }

        /* ─── Alerts ─────────────────────────────────────────── */
        .alert {
            border-radius: var(--radius);
            border: none;
            border-left: 4px solid;
            font-size: 0.875rem;
            font-weight: 500;
            padding: 0.875rem 1.25rem;
            box-shadow: var(--shadow-sm);
        }

        .alert-success {
            background: #f0fdf4;
            color: #14532d;
            border-left-color: var(--success);
        }

        .alert-danger {
            background: #fef2f2;
            color: #7f1d1d;
            border-left-color: var(--danger);
        }

        /* ─── Dropdown ───────────────────────────────────────── */
        .dropdown-menu {
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow-md);
            padding: 0.375rem;
            font-size: 0.875rem;
        }

        .dropdown-item {
            border-radius: var(--radius-sm);
            padding: 0.5rem 0.75rem;
            color: var(--text-primary);
            font-weight: 500;
            transition: var(--transition);
        }

        .dropdown-item:hover { background: var(--border-light); }
        .dropdown-item.text-danger:hover { background: var(--danger-light); }

        /* ─── Pagination ─────────────────────────────────────── */
        .pagination .page-link {
            border-radius: var(--radius-sm) !important;
            border-color: var(--border);
            color: var(--text-secondary);
            font-size: 0.845rem;
            font-weight: 500;
            padding: 0.4rem 0.75rem;
            transition: var(--transition);
        }

        .pagination .page-link:hover {
            background: var(--primary-light);
            border-color: var(--primary-border);
            color: var(--primary);
        }

        .pagination .active .page-link {
            background: var(--primary);
            border-color: var(--primary);
        }

        /* ─── Metric Boxes ───────────────────────────────────── */
        .metric-box {
            padding: 0.875rem 1rem;
            border-radius: var(--radius);
            border-left: 3px solid;
        }

        .metric-box-success {
            background: var(--success-light);
            border-left-color: var(--success);
        }

        .metric-box-warning {
            background: var(--warning-light);
            border-left-color: var(--warning);
        }

        .metric-box-primary {
            background: var(--primary-light);
            border-left-color: var(--primary);
        }

        .metric-label {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 4px;
        }

        .metric-value {
            font-size: 1.35rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            line-height: 1;
        }

        /* ─── Totals Panel (shared: create, show) ────────────── */
        .totals-panel {
            background: var(--bg-main);
            border-radius: var(--radius);
            padding: 1.125rem 1.375rem;
            min-width: 270px;
        }

        .totals-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.3rem 0;
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .totals-row span:last-child {
            font-weight: 600;
            color: var(--text-primary);
            font-variant-numeric: tabular-nums;
        }

        .totals-grand {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 0.75rem;
            margin-top: 0.5rem;
            border-top: 2px solid var(--border);
        }

        /* ─── Filter Bar ─────────────────────────────────────── */
        .filter-bar {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border-light);
            background: #fafbfc;
            border-radius: var(--radius-lg) var(--radius-lg) 0 0;
        }

        /* ─── Pagination Footer ───────────────────────────────── */
        .table-footer {
            padding: 0.875rem 1.25rem;
            border-top: 1px solid var(--border-light);
            background: #fafbfc;
            border-radius: 0 0 var(--radius-lg) var(--radius-lg);
        }

        /* ─── License Plate ───────────────────────────────────── */
        .plate-badge {
            display: inline-block;
            background: white;
            color: var(--text-primary);
            border: 2px solid #cbd5e1;
            border-radius: 5px;
            padding: 3px 10px;
            font-family: "Courier New", "Lucida Console", monospace;
            font-weight: 800;
            font-size: 0.875rem;
            letter-spacing: 2px;
            line-height: 1.3;
        }

        /* ─── Empty State ─────────────────────────────────────── */
        .empty-state {
            padding: 3.5rem 1rem;
            text-align: center;
        }

        .empty-state-icon {
            width: 56px;
            height: 56px;
            background: var(--border-light);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.875rem;
            font-size: 1.375rem;
            color: var(--text-muted);
        }

        .empty-state p {
            font-size: 0.845rem;
            color: var(--text-muted);
            margin: 0;
        }

        /* ─── Info Card (show pages) ──────────────────────────── */
        .info-section-label {
            font-size: 0.63rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.09em;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 1rem;
            padding-bottom: 0.625rem;
            border-bottom: 1px solid var(--border-light);
        }

        .info-row { margin-bottom: 0.75rem; }
        .info-row:last-child { margin-bottom: 0; }

        .info-label {
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--text-muted);
            margin-bottom: 2px;
        }

        .info-value {
            font-size: 0.9rem;
            color: var(--text-primary);
            font-weight: 500;
        }

        /* ─── Animations ─────────────────────────────────────── */
        .animate-in {
            animation: fadeSlideUp 0.45s ease both;
        }

        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ─── Utilities ──────────────────────────────────────── */
        .outfit { font-weight: 700; letter-spacing: -0.02em; }
        .fw-800 { font-weight: 800 !important; }
        .ls-tight { letter-spacing: -0.025em; }
        .text-xs  { font-size: 0.72rem; }
        .text-sm  { font-size: 0.845rem; }
        .text-app-primary { color: var(--primary) !important; }
        .text-accent      { color: var(--accent) !important; }
    </style>
    @yield('styles')
</head>

<body>

    <!-- ─── Sidebar ─────────────────────────────────────────── -->
    <div class="sidebar">

        <!-- Brand -->
        <a class="sidebar-brand" href="{{ route('dashboard') }}">
            <div class="sidebar-brand-icon">
                <i class="bi bi-wrench-adjustable"></i>
            </div>
            <div>
                <div class="sidebar-brand-name">GesTaller</div>
                <div class="sidebar-brand-tag">Gestión de Taller</div>
            </div>
        </a>

        @php $role = auth()->user()?->role ?? 'taller'; @endphp

        <div class="sidebar-body">

            {{-- Branch selector / indicator --}}
            @if($role === 'admin')
            <div class="sidebar-branch-widget">
                <div class="sidebar-branch-label"><i class="bi bi-building me-1"></i>Sucursal activa</div>
                <form method="POST" action="{{ route('branch.switch') }}">
                    @csrf
                    <select name="branch_id"
                        style="background:transparent;border:none;color:#8b9ab5;font-size:0.82rem;padding:0;width:100%;cursor:pointer;outline:none;"
                        onchange="this.form.submit()">
                        <option value="" style="background:#0d1520;">Todas las sucursales</option>
                        @foreach($branches as $b)
                            <option value="{{ $b->id }}" style="background:#0d1520;"
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
            <div class="sidebar-branch-widget">
                <div class="sidebar-branch-label"><i class="bi bi-building me-1"></i>Sucursal</div>
                <div style="font-size:0.82rem;color:#8b9ab5;">{{ $userBranch->name }}</div>
            </div>
            @endif
            @endif

            <!-- Navigation -->
            <nav class="nav flex-column mt-1">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                    href="{{ route('dashboard') }}">
                    <i class="bi bi-grid-fill"></i> Panel General
                </a>
                <a class="nav-link {{ request()->routeIs('quotations.index') || (request()->routeIs('quotations.*') && !request()->routeIs('quotations.followup')) ? 'active' : '' }}"
                    href="{{ route('quotations.index') }}">
                    <i class="bi bi-receipt"></i> Cotizaciones
                </a>
                @if(in_array($role, ['admin','recepcion']))
                <a class="nav-link {{ request()->routeIs('quotations.followup') ? 'active' : '' }}"
                    href="{{ route('quotations.followup') }}">
                    <i class="bi bi-telephone-outbound-fill"></i> Seguimiento
                </a>
                @endif

                @if(in_array($role, ['admin','recepcion','taller']))
                <a class="nav-link {{ request()->routeIs('clients.*') ? 'active' : '' }}"
                    href="{{ route('clients.index') }}">
                    <i class="bi bi-people-fill"></i> Clientes
                </a>
                <a class="nav-link {{ request()->routeIs('vehicles.*') ? 'active' : '' }}"
                    href="{{ route('vehicles.index') }}">
                    <i class="bi bi-car-front-fill"></i> Vehículos
                </a>
                @endif

                @if(in_array($role, ['admin','recepcion']))
                <a class="nav-link {{ request()->is('liquidators*') ? 'active' : '' }}"
                    href="{{ route('liquidators.index') }}">
                    <i class="bi bi-person-badge-fill"></i> Liquidadores
                </a>
                <a class="nav-link {{ request()->is('insurance-companies*') ? 'active' : '' }}"
                    href="{{ route('insurance-companies.index') }}">
                    <i class="bi bi-building-fill"></i> Compañías
                </a>
                <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}"
                    href="{{ route('reports.index') }}">
                    <i class="bi bi-bar-chart-fill"></i> Reportes
                </a>
                @endif

                {{-- Admin section --}}
                @if($role === 'admin')
                <span class="sidebar-section-label">Administración</span>
                <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}"
                    href="{{ route('users.index') }}">
                    <i class="bi bi-people-fill"></i> Usuarios
                </a>
                <a class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}"
                    href="{{ route('roles.index') }}">
                    <i class="bi bi-shield-lock-fill"></i> Roles
                </a>
                <a class="nav-link {{ request()->routeIs('branches.*') ? 'active' : '' }}"
                    href="{{ route('branches.index') }}">
                    <i class="bi bi-building-fill-gear"></i> Sucursales
                </a>
                <a class="nav-link {{ request()->routeIs('un-types.*') ? 'active' : '' }}"
                    href="{{ route('un-types.index') }}">
                    <i class="bi bi-tags-fill"></i> Tipos de UN
                </a>
                <a class="nav-link {{ request()->routeIs('service-items.*') ? 'active' : '' }}"
                    href="{{ route('service-items.index') }}">
                    <i class="bi bi-clipboard2-pulse-fill"></i> Catálogo Servicios
                </a>
                <a class="nav-link {{ request()->is('vehicle-brands*') ? 'active' : '' }}"
                    href="{{ route('vehicle-brands.index') }}">
                    <i class="bi bi-signpost-split-fill"></i> Marcas / Modelos
                </a>
                @endif

                <a class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}"
                    href="{{ route('profile.index') }}">
                    <i class="bi bi-gear-fill"></i> Ajustes
                </a>
            </nav>
        </div>

        <!-- Sidebar Footer -->
        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="nav-link border-0 bg-transparent w-100 text-start"
                    style="color:#ef4444;">
                    <i class="bi bi-box-arrow-left"></i> Cerrar Sesión
                </button>
            </form>
        </div>
    </div>

    <!-- ─── Main Content ─────────────────────────────────────── -->
    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')

    {{-- ═══ MODAL: Control de Inactividad ═══ --}}
    <div class="modal fade" id="inactivityModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
         aria-labelledby="inactivityModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
            <div class="modal-content border-0" style="border-radius:1.25rem;box-shadow:0 20px 60px rgba(0,0,0,.18);overflow:hidden;">

                {{-- Header con borde de alerta --}}
                <div style="background:linear-gradient(135deg,#f59e0b 0%,#ef4444 100%);padding:1.5rem 1.75rem 1.25rem;">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width:48px;height:48px;background:rgba(255,255,255,.2);border-radius:50%;
                                    display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bi bi-clock-history" style="font-size:1.4rem;color:#fff;"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0" style="color:#fff;font-size:1.05rem;" id="inactivityModalLabel">
                                Sesión por expirar
                            </h5>
                            <p class="mb-0" style="color:rgba(255,255,255,.85);font-size:0.8rem;">
                                Detectamos inactividad en tu cuenta
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Body --}}
                <div class="modal-body px-4 py-4 text-center">
                    <p class="mb-1" style="font-size:0.9rem;color:#374151;">
                        Tu sesión se cerrará automáticamente en:
                    </p>
                    <div id="inactivityCountdown"
                         style="font-size:3rem;font-weight:800;color:#ef4444;letter-spacing:-2px;
                                font-variant-numeric:tabular-nums;line-height:1.1;margin:0.5rem 0;">
                        10:00
                    </div>
                    <p class="mb-0" style="font-size:0.8rem;color:#6b7280;">
                        Haz clic en <strong>Continuar</strong> para seguir trabajando.
                    </p>
                </div>

                {{-- Footer --}}
                <div class="modal-footer border-0 px-4 pb-4 pt-0 gap-2 justify-content-center">
                    <form action="{{ route('logout') }}" method="POST" id="inactivityLogoutForm">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary rounded-pill px-4"
                                style="font-size:0.845rem;">
                            <i class="bi bi-box-arrow-left me-1"></i> Cerrar sesión
                        </button>
                    </form>
                    <button type="button" id="inactivityKeepBtn"
                            class="btn rounded-pill px-5 fw-600"
                            style="background:linear-gradient(135deg,#1e40af,#2563eb);color:#fff;
                                   font-size:0.845rem;font-weight:600;">
                        <i class="bi bi-shield-check me-1"></i> Continuar
                    </button>
                </div>

            </div>
        </div>
    </div>

    <script>
    (function () {
        // ── Configuración ───────────────────────────────────────────
        const SESSION_MINUTES  = {{ (int) env('SESSION_LIFETIME', 120) }};  // minutos totales de sesión
        const WARN_BEFORE_SECS = 10 * 60;   // mostrar aviso 10 min antes
        const IDLE_TIMEOUT_MS  = (SESSION_MINUTES * 60 - WARN_BEFORE_SECS) * 1000;
        const COUNTDOWN_SECS   = WARN_BEFORE_SECS;                          // cuenta regresiva

        let idleTimer, countdownTimer, secondsLeft;
        const modal       = new bootstrap.Modal(document.getElementById('inactivityModal'), {backdrop:'static'});
        const countdownEl = document.getElementById('inactivityCountdown');
        const keepBtn     = document.getElementById('inactivityKeepBtn');

        // ── Formatea segundos a MM:SS ───────────────────────────────
        function fmt(s) {
            const m = Math.floor(s / 60).toString().padStart(2, '0');
            const sec = (s % 60).toString().padStart(2, '0');
            return `${m}:${sec}`;
        }

        // ── Inicia la cuenta regresiva dentro del modal ─────────────
        function startCountdown() {
            secondsLeft = COUNTDOWN_SECS;
            countdownEl.textContent = fmt(secondsLeft);
            countdownEl.style.color = '#ef4444';

            clearInterval(countdownTimer);
            countdownTimer = setInterval(() => {
                secondsLeft--;
                countdownEl.textContent = fmt(secondsLeft);

                // Urgencia: últimos 3 minutos → rojo pulsante
                if (secondsLeft <= 180) {
                    countdownEl.style.animation = 'pulse-red 1s ease-in-out infinite';
                }

                if (secondsLeft <= 0) {
                    clearInterval(countdownTimer);
                    document.getElementById('inactivityLogoutForm').submit();
                }
            }, 1000);
        }

        // ── Muestra el modal de advertencia ────────────────────────
        function showWarning() {
            startCountdown();
            modal.show();
        }

        // ── Reinicia el temporizador de inactividad ─────────────────
        function resetIdle() {
            clearTimeout(idleTimer);
            clearInterval(countdownTimer);

            // Si el modal está abierto por inactividad, cerrarlo
            const modalEl = document.getElementById('inactivityModal');
            if (modalEl.classList.contains('show')) {
                modal.hide();
                countdownEl.style.animation = '';
            }

            idleTimer = setTimeout(showWarning, IDLE_TIMEOUT_MS);
        }

        // ── Botón "Continuar" ──────────────────────────────────────
        keepBtn.addEventListener('click', function () {
            // Ping al servidor para renovar la sesión
            fetch(window.location.href, { method: 'HEAD', credentials: 'same-origin' })
                .catch(() => {});
            resetIdle();
        });

        // ── Eventos de actividad del usuario ───────────────────────
        ['mousemove', 'mousedown', 'keydown', 'scroll', 'touchstart', 'click'].forEach(evt => {
            document.addEventListener(evt, resetIdle, { passive: true });
        });

        // ── Arranca ─────────────────────────────────────────────────
        resetIdle();
    })();
    </script>

    <style>
    @keyframes pulse-red {
        0%, 100% { opacity: 1; transform: scale(1); }
        50%       { opacity: 0.65; transform: scale(1.04); }
    }
    </style>
</body>

</html>
