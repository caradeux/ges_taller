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
            border: 1px solid rgba(0, 0, 0, 0.06);
            border-radius: var(--radius-lg);
            background: white;
            box-shadow: var(--card-shadow);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
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
            border-bottom: 2px solid var(--border);
            border-top: none;
            white-space: nowrap;
        }

        .table tbody td {
            padding: 0.9375rem 1rem;
            border-bottom: 1px solid var(--border-light);
            vertical-align: middle;
            transition: background-color 0.15s ease;
        }

        .table tbody tr:nth-child(even) td { background-color: #fafbfc; }
        .table tbody tr:hover td { background-color: #eef2ff; }
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
            box-shadow: 0 4px 12px rgba(30, 64, 175, 0.25);
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

        .btn-warning-app {
            background: var(--warning);
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

        .btn-warning-app:hover {
            background: #b45309;
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(217, 119, 6, 0.3);
            color: white !important;
        }

        /* ─── Status Badges ──────────────────────────────────── */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 0.25rem 0.7rem;
            border-radius: 9999px;
            font-size: 0.69rem;
            font-weight: 600;
            letter-spacing: 0.025em;
            white-space: nowrap;
        }

        .status-badge::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
            flex-shrink: 0;
            opacity: 0.7;
        }

        .status-draft    { background: #fef3c7; color: #92400e; }
        .status-sent     { background: #dbeafe; color: #1d4ed8; }
        .status-approved { background: #dcfce7; color: #166534; }
        .status-rejected { background: #fee2e2; color: #991b1b; }
        .status-finished { background: #ede9fe; color: #6d28d9; }
        .status-invoiced { background: #d1fae5; color: #065f46; }

        /* OT status badges */
        .status-intake        { background: #fef3c7; color: #92400e; }
        .status-budget_sent   { background: #dbeafe; color: #1d4ed8; }
        .status-waiting_parts { background: #ffedd5; color: #9a3412; }
        .status-in_repair     { background: #e0e7ff; color: #3730a3; }
        .status-completed     { background: #ccfbf1; color: #115e59; }
        .status-delivered     { background: #ede9fe; color: #6d28d9; }

        /* ─── Form Controls ──────────────────────────────────── */
        .form-control,
        .form-select {
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            font-size: 0.875rem;
            color: var(--text-primary);
            padding: 0.5625rem 0.875rem;
            transition: border-color 0.25s ease, box-shadow 0.25s ease;
            background: white;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #93b4f0;
            box-shadow: 0 0 0 3.5px rgba(30, 64, 175, 0.08);
            outline: none;
        }

        .form-select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 14px 10px;
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
            width: 46px;
            height: 46px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            flex-shrink: 0;
        }

        .stat-icon-primary { background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); color: var(--primary); }
        .stat-icon-success { background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); color: var(--success); }
        .stat-icon-warning { background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%); color: var(--warning); }
        .stat-icon-accent  { background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%); color: var(--accent);  }
        .stat-icon-info    { background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); color: var(--info);    }

        .stat-value {
            font-size: 1.75rem;
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
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -0.025em;
            margin-bottom: 0.25rem;
        }

        .page-subtitle {
            font-size: 0.84rem;
            color: var(--text-muted);
            margin: 0;
            font-weight: 400;
            letter-spacing: 0.01em;
        }

        /* ─── Alerts ─────────────────────────────────────────── */
        .alert {
            border-radius: var(--radius);
            border: none;
            border-left: 4px solid;
            font-size: 0.875rem;
            font-weight: 500;
            padding: 1rem 1.375rem;
            box-shadow: none;
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

        .alert-warning {
            background: #fffbeb;
            color: #78350f;
            border-left-color: var(--warning);
        }

        .alert-info {
            background: #f0f9ff;
            color: #0c4a6e;
            border-left-color: var(--info);
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
            padding: 1.125rem 1.25rem;
            border-radius: var(--radius-lg);
            border-left: 3px solid;
        }

        .metric-box-success {
            background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);
            border-left-color: var(--success);
        }

        .metric-box-warning {
            background: linear-gradient(135deg, #fffbeb 0%, #fef9e7 100%);
            border-left-color: var(--warning);
        }

        .metric-box-primary {
            background: linear-gradient(135deg, #eff6ff 0%, #eef2ff 100%);
            border-left-color: var(--primary);
        }

        .metric-box-danger {
            background: linear-gradient(135deg, #fef2f2 0%, #fff1f2 100%);
            border-left-color: var(--danger);
        }

        .metric-box-info {
            background: linear-gradient(135deg, #f0f9ff 0%, #ecfeff 100%);
            border-left-color: var(--info);
        }

        .metric-label {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 4px;
        }

        .metric-value {
            font-size: 1.4rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            line-height: 1;
        }

        /* ─── Totals Panel (shared: create, show) ────────────── */
        .totals-panel {
            background: var(--bg-main);
            border-radius: var(--radius-lg);
            padding: 1.25rem 1.5rem;
            min-width: 280px;
        }

        .totals-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.4rem 0;
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
            padding-top: 0.875rem;
            margin-top: 0.625rem;
            border-top: 2px solid var(--border);
            font-size: 1rem;
        }

        /* ─── Filter Bar ─────────────────────────────────────── */
        .filter-bar {
            padding: 1.125rem 1.5rem;
            border-bottom: 1px solid var(--border-light);
            background: #fafbfd;
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
            background: #1e293b;
            color: #ffffff;
            border: none;
            border-radius: 9999px;
            padding: 4px 14px;
            font-family: "Courier New", "Lucida Console", monospace;
            font-weight: 800;
            font-size: 0.84rem;
            letter-spacing: 2px;
            line-height: 1.3;
        }

        /* ─── Empty State ─────────────────────────────────────── */
        .empty-state {
            padding: 4.5rem 1.5rem;
            text-align: center;
        }

        .empty-state-icon {
            width: 68px;
            height: 68px;
            background: var(--border-light);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            font-size: 1.65rem;
            color: var(--text-muted);
        }

        .empty-state p {
            font-size: 0.875rem;
            color: var(--text-muted);
            margin: 0;
        }

        .empty-state p + p {
            font-size: 0.8rem;
            margin-top: 0.35rem;
            color: #b0b8c4;
        }

        /* ─── Info Card (show pages) ──────────────────────────── */
        .info-section-label {
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.09em;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 0.875rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid var(--border-light);
        }

        .info-row { margin-bottom: 0.625rem; }
        .info-row:last-child { margin-bottom: 0; }

        .info-label {
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--text-muted);
            margin-bottom: 1px;
        }

        .info-value {
            font-size: 0.875rem;
            color: var(--text-primary);
            font-weight: 500;
        }

        /* ─── Animations ─────────────────────────────────────── */
        .animate-in {
            animation: fadeSlideUp 0.45s ease both;
        }

        @keyframes fadeSlideUp {
            from { opacity: 0; margin-top: 8px; }
            to   { opacity: 1; margin-top: 0; }
        }

        /* ─── Utilities ──────────────────────────────────────── */
        .outfit { font-weight: 700; letter-spacing: -0.02em; }
        .fw-800 { font-weight: 800 !important; }
        .ls-tight { letter-spacing: -0.025em; }
        .text-xs  { font-size: 0.72rem; }
        .text-sm  { font-size: 0.845rem; }
        .text-app-primary { color: var(--primary) !important; }
        .text-accent      { color: var(--accent) !important; }

        /* ─── Mobile Top Bar ────────────────────────────────── */
        .mobile-topbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 56px;
            background: var(--sidebar-bg);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 16px;
            z-index: 1001;
        }
        .mobile-menu-btn {
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            padding: 4px 8px;
            cursor: pointer;
            border-radius: var(--radius-sm);
            transition: var(--transition);
        }
        .mobile-menu-btn:hover { background: rgba(255,255,255,0.1); }
        .mobile-brand {
            color: white;
            font-weight: 700;
            font-size: 1.1rem;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .mobile-brand i { color: #3b82f6; }
        .mobile-user {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), #3b82f6);
            color: white;
            font-weight: 700;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Sidebar overlay */
        .sidebar-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        .sidebar-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        /* ─── Responsive ────────────────────────────────────── */
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                z-index: 1002;
            }
            .sidebar.open { transform: translateX(0); }

            .main-content {
                margin-left: 0 !important;
                padding: 72px 16px 24px !important;
            }

            .filter-bar { padding: 12px !important; }
            .filter-bar form { flex-direction: column; }
            .filter-bar .input-icon-wrap { max-width: 100% !important; width: 100%; }
            .filter-bar .form-select { max-width: 100% !important; width: 100%; }

            .page-title { font-size: 1.25rem !important; }
            .page-subtitle { font-size: 0.78rem !important; }

            .ot-stats-bar { grid-template-columns: repeat(3, 1fr) !important; }
        }

        @media (max-width: 575.98px) {
            .main-content { padding: 68px 12px 20px !important; }

            .d-flex.justify-content-between.align-items-start.mb-4 {
                flex-direction: column;
                gap: 12px;
            }

            .ot-stats-bar { grid-template-columns: repeat(2, 1fr) !important; }

            .table { font-size: 0.82rem; }
            .table th, .table td { padding: 0.5rem 0.6rem; }

            .items-table .price-inp { width: 80px !important; }

            .triple-totals { grid-template-columns: 1fr !important; }

            .ot-hero { padding: 1.25rem !important; }
            .ot-hero .ot-folio { font-size: 1.4rem !important; }

            .action-bar { gap: 6px; }
            .action-bar .btn-primary-premium,
            .action-bar .btn-app-secondary,
            .action-bar .btn-success-app,
            .action-bar .btn-info-app,
            .action-bar .btn-accent-app,
            .action-bar .btn-danger-app {
                padding: 0.4rem 0.75rem !important;
                font-size: 0.78rem !important;
            }
        }

        @media (min-width: 992px) {
            .mobile-topbar { display: none !important; }
            .sidebar-overlay { display: none !important; }
        }
    </style>
    @yield('styles')
</head>

<body>

    <!-- ─── Mobile Top Bar ──────────────────────────────────── -->
    <div class="mobile-topbar">
        <button class="mobile-menu-btn" id="sidebarToggle" aria-label="Abrir menu">
            <i class="bi bi-list"></i>
        </button>
        <a href="{{ route('dashboard') }}" class="mobile-brand">
            <i class="bi bi-wrench-adjustable"></i> GesTaller
        </a>
        <div class="mobile-user">
            {{ strtoupper(mb_substr(auth()->user()->name ?? 'U', 0, 1)) }}
        </div>
    </div>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- ─── Sidebar ─────────────────────────────────────────── -->
    <div class="sidebar" id="sidebar">

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
                    <i class="bi bi-speedometer2"></i> Panel General
                </a>
                <a class="nav-link {{ request()->routeIs('work-orders.index') || (request()->routeIs('work-orders.*') && !request()->routeIs('work-orders.followup')) ? 'active' : '' }}"
                    href="{{ route('work-orders.index') }}">
                    <i class="bi bi-tools"></i> Órdenes de Trabajo
                </a>
                @if(in_array($role, ['admin','recepcion']))
                <a class="nav-link {{ request()->routeIs('work-orders.followup') ? 'active' : '' }}"
                    href="{{ route('work-orders.followup') }}">
                    <i class="bi bi-clipboard-check-fill"></i> Seguimiento
                </a>
                <a class="nav-link {{ request()->routeIs('sla.*') ? 'active' : '' }}"
                    href="{{ route('sla.index') }}">
                    <i class="bi bi-stopwatch-fill"></i> Control de Tiempos
                </a>
                @endif

                @if(in_array($role, ['admin','recepcion','taller']))
                <a class="nav-link {{ request()->routeIs('clients.*') ? 'active' : '' }}"
                    href="{{ route('clients.index') }}">
                    <i class="bi bi-person-lines-fill"></i> Clientes
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
                    <i class="bi bi-shield-fill-check"></i> Aseguradoras
                </a>
                <a class="nav-link {{ request()->routeIs('reports.index') ? 'active' : '' }}"
                    href="{{ route('reports.index') }}">
                    <i class="bi bi-graph-up-arrow"></i> Reportes
                </a>
                <a class="nav-link {{ request()->routeIs('reports.insurance') ? 'active' : '' }}"
                    href="{{ route('reports.insurance') }}" style="padding-left: 2.5rem; font-size: 0.82rem;">
                    <i class="bi bi-shield-check"></i> Aseguradoras
                </a>
                <a class="nav-link {{ request()->routeIs('reports.profitability') ? 'active' : '' }}"
                    href="{{ route('reports.profitability') }}" style="padding-left: 2.5rem; font-size: 0.82rem;">
                    <i class="bi bi-cash-coin"></i> Rentabilidad
                </a>
                <a class="nav-link {{ request()->routeIs('reports.parts') ? 'active' : '' }}"
                    href="{{ route('reports.parts') }}" style="padding-left: 2.5rem; font-size: 0.82rem;">
                    <i class="bi bi-box-seam"></i> Repuestos
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
                    <i class="bi bi-shop"></i> Sucursales
                </a>
                <a class="nav-link {{ request()->routeIs('tags.*') ? 'active' : '' }}"
                    href="{{ route('tags.index') }}">
                    <i class="bi bi-bookmark-fill"></i> Etiquetas
                </a>
                <a class="nav-link {{ request()->routeIs('holidays.*') ? 'active' : '' }}"
                    href="{{ route('holidays.index') }}">
                    <i class="bi bi-calendar-event"></i> Feriados
                </a>
                <a class="nav-link {{ request()->routeIs('un-types.*') ? 'active' : '' }}"
                    href="{{ route('un-types.index') }}">
                    <i class="bi bi-list-check"></i> Tipos de UN
                </a>
                <a class="nav-link {{ request()->routeIs('service-items.*') ? 'active' : '' }}"
                    href="{{ route('service-items.index') }}">
                    <i class="bi bi-gear-wide-connected"></i> Catálogo Servicios
                </a>
                <a class="nav-link {{ request()->is('vehicle-brands*') ? 'active' : '' }}"
                    href="{{ route('vehicle-brands.index') }}">
                    <i class="bi bi-ev-front-fill"></i> Marcas / Modelos
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

    {{-- Sidebar mobile toggle --}}
    <script>
    (function() {
        const btn = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        if (!btn || !sidebar) return;

        function openSidebar() {
            sidebar.classList.add('open');
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        function closeSidebar() {
            sidebar.classList.remove('open');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        }

        btn.addEventListener('click', openSidebar);
        overlay.addEventListener('click', closeSidebar);

        // Close on nav link click (mobile)
        sidebar.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 992) closeSidebar();
            });
        });
    })();
    </script>

    @yield('scripts')
    @stack('modals')

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
