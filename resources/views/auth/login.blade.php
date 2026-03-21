<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GesTaller | Acceso al Sistema</title>
    <link href="/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/vendor/bootstrap-icons/bootstrap-icons.css">

    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --accent: #f97316;
            --dark: #0f172a;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
            height: 100vh;
            display: flex;
            overflow: hidden;
        }

        /* ─── Left Panel (decorative) ─── */
        .login-hero {
            flex: 1;
            background: linear-gradient(135deg, var(--dark) 0%, #1e293b 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 4rem;
            position: relative;
            overflow: hidden;
        }

        .login-hero::before {
            content: '';
            position: absolute;
            top: -30%;
            right: -20%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.15) 0%, transparent 70%);
            border-radius: 50%;
        }

        .login-hero::after {
            content: '';
            position: absolute;
            bottom: -20%;
            left: -10%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(249, 115, 22, 0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .hero-content {
            position: relative;
            z-index: 1;
            text-align: center;
            max-width: 420px;
        }

        .hero-icon-ring {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            box-shadow: 0 20px 60px rgba(37, 99, 235, 0.3);
            animation: pulse-ring 3s ease-in-out infinite;
        }

        @keyframes pulse-ring {
            0%, 100% { box-shadow: 0 20px 60px rgba(37, 99, 235, 0.3); transform: scale(1); }
            50% { box-shadow: 0 20px 80px rgba(37, 99, 235, 0.45); transform: scale(1.03); }
        }

        .hero-icon-ring i {
            font-size: 3rem;
            color: white;
        }

        .hero-title {
            font-size: 2.2rem;
            font-weight: 800;
            color: white;
            letter-spacing: -0.03em;
            margin-bottom: 0.75rem;
        }

        .hero-subtitle {
            font-size: 1rem;
            color: #94a3b8;
            line-height: 1.6;
            margin-bottom: 2.5rem;
        }

        .hero-features {
            list-style: none;
            padding: 0;
            text-align: left;
        }

        .hero-features li {
            color: #cbd5e1;
            font-size: 0.9rem;
            padding: 0.6rem 0;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }

        .hero-features li:last-child { border-bottom: none; }

        .hero-features li i {
            color: var(--accent);
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
        }

        /* ─── Right Panel (form) ─── */
        .login-panel {
            width: 480px;
            min-width: 480px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 3rem 3.5rem;
            background: white;
        }

        .login-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 2.5rem;
        }

        .login-brand-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--primary), #3b82f6);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.3rem;
        }

        .login-brand-name {
            font-weight: 800;
            font-size: 1.4rem;
            color: var(--dark);
            letter-spacing: -0.02em;
        }

        .login-brand-tag {
            font-size: 0.7rem;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-weight: 600;
        }

        .login-heading {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.25rem;
        }

        .login-subheading {
            font-size: 0.9rem;
            color: #94a3b8;
            margin-bottom: 2rem;
        }

        .form-label {
            font-size: 0.82rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 0.4rem;
        }

        .input-icon-group {
            position: relative;
        }

        .input-icon-group i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1rem;
            transition: color 0.2s;
        }

        .input-icon-group input {
            padding-left: 2.75rem;
        }

        .input-icon-group:focus-within i {
            color: var(--primary);
        }

        .form-control {
            padding: 0.75rem 1rem;
            border-radius: 0.75rem;
            border: 1.5px solid #e2e8f0;
            background-color: #f8fafc;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            background-color: white;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.08);
        }

        .btn-login {
            background: linear-gradient(135deg, var(--primary), #3b82f6);
            border: none;
            color: white;
            padding: 0.85rem;
            border-radius: 0.75rem;
            font-weight: 700;
            font-size: 0.95rem;
            width: 100%;
            margin-top: 0.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(37, 99, 235, 0.35);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .login-footer {
            margin-top: 2.5rem;
            text-align: center;
            font-size: 0.78rem;
            color: #cbd5e1;
        }

        .login-footer span { color: var(--accent); font-weight: 700; }

        /* ─── Responsive ─── */
        @media (max-width: 900px) {
            .login-hero { display: none; }
            .login-panel {
                width: 100%;
                min-width: unset;
                max-width: 480px;
                margin: 0 auto;
            }
            body { justify-content: center; background: white; }
        }

        /* ─── Animate in ─── */
        .fade-up {
            animation: fadeUp 0.6s ease-out both;
        }

        .fade-up-d1 { animation-delay: 0.1s; }
        .fade-up-d2 { animation-delay: 0.2s; }
        .fade-up-d3 { animation-delay: 0.3s; }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>

<body>

    {{-- ═══ Left Hero Panel ═══ --}}
    <div class="login-hero">
        <div class="hero-content fade-up">
            <div class="hero-icon-ring">
                <i class="bi bi-wrench-adjustable"></i>
            </div>
            <h1 class="hero-title">GesTaller</h1>
            <p class="hero-subtitle">
                Plataforma integral para la gestión de órdenes de trabajo, repuestos y seguimiento de tu taller automotriz.
            </p>
            <ul class="hero-features">
                <li><i class="bi bi-tools"></i> Órdenes de trabajo con trazabilidad completa</li>
                <li><i class="bi bi-speedometer2"></i> Dashboard de rendimiento en tiempo real</li>
                <li><i class="bi bi-box-seam"></i> Control de repuestos y proveedores</li>
                <li><i class="bi bi-graph-up-arrow"></i> Reportes de rentabilidad</li>
                <li><i class="bi bi-shield-fill-check"></i> Gestión de seguros y liquidadores</li>
            </ul>
        </div>
    </div>

    {{-- ═══ Right Form Panel ═══ --}}
    <div class="login-panel">
        <div class="login-brand fade-up">
            <div class="login-brand-icon">
                <i class="bi bi-wrench-adjustable"></i>
            </div>
            <div>
                <div class="login-brand-name">GesTaller</div>
                <div class="login-brand-tag">Taller Automotriz</div>
            </div>
        </div>

        <h2 class="login-heading fade-up fade-up-d1">Bienvenido</h2>
        <p class="login-subheading fade-up fade-up-d1">Ingresa tus credenciales para acceder al sistema</p>

        @if(session('error'))
        <div class="alert alert-danger border-0 py-2 px-3 mb-3 fade-up" style="border-radius:0.75rem;font-size:0.85rem;">
            <i class="bi bi-exclamation-circle me-1"></i>{{ session('error') }}
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3 fade-up fade-up-d2">
                <label for="email" class="form-label">Correo electrónico</label>
                <div class="input-icon-group">
                    <i class="bi bi-envelope"></i>
                    <input type="email" id="email" name="email"
                        class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email') }}" required autofocus
                        placeholder="tu@correo.cl">
                </div>
                @error('email')
                    <div class="invalid-feedback d-block" style="font-size:0.8rem;">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3 fade-up fade-up-d2">
                <label for="password" class="form-label">Contraseña</label>
                <div class="input-icon-group">
                    <i class="bi bi-lock"></i>
                    <input type="password" id="password" name="password"
                        class="form-control" required
                        placeholder="Ingresa tu contraseña">
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3 fade-up fade-up-d3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label small text-secondary" for="remember">
                        Recordarme
                    </label>
                </div>
            </div>

            <button type="submit" class="btn-login fade-up fade-up-d3">
                <i class="bi bi-box-arrow-in-right"></i>
                Iniciar Sesión
            </button>
        </form>

        <div class="login-footer fade-up fade-up-d3">
            <span>GesTaller</span> &mdash; Sistema de Gestión de Taller &copy; {{ date('Y') }}
        </div>
    </div>

</body>

</html>
