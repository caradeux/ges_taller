<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ges_Taller | Acceso al Sistema</title>
    <link href="/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/vendor/bootstrap-icons/bootstrap-icons.css">

    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --sidebar-bg: #0f172a;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
            background: radial-gradient(circle at 10% 20%, rgba(37, 99, 235, 0.05) 0%, transparent 40%),
                radial-gradient(circle at 90% 80%, rgba(37, 99, 235, 0.05) 0%, transparent 40%),
                #f8fafc;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            overflow: hidden;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            background: white;
            padding: 3rem 2.5rem;
            border-radius: 1.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .brand {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 2rem;
            color: var(--sidebar-bg);
            margin-bottom: 2rem;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }

        .brand i {
            color: var(--primary);
        }

        .form-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #64748b;
            margin-bottom: 0.5rem;
        }

        .form-control {
            padding: 0.8rem 1rem;
            border-radius: 0.75rem;
            border: 1px solid #e2e8f0;
            background-color: #f8fafc;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            background-color: white;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        .btn-login {
            background: var(--primary);
            border: none;
            color: white;
            padding: 0.8rem;
            border-radius: 0.75rem;
            font-weight: 600;
            width: 100%;
            margin-top: 1.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2);
        }

        .btn-login:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.25);
        }

        .login-footer {
            margin-top: 2rem;
            text-align: center;
            font-size: 0.85rem;
            color: #94a3b8;
        }
    </style>
</head>

<body>
    <div class="login-card">
        <div class="brand">
            <i class="bi bi-shield-shaded"></i>
            <span>Ges_Taller</span>
        </div>

        <h4 class="outfit fw-bold text-center mb-4">¡Bienvenido de nuevo!</h4>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-4">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}" required autofocus placeholder="correo@ejemplo.cl">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <label for="password" class="form-label mb-0">Contraseña</label>
                    <a href="#" class="text-primary text-decoration-none small fw-medium">¿Olvidaste tu clave?</a>
                </div>
                <input type="password" id="password" name="password" class="form-control" required
                    placeholder="••••••••">
            </div>

            <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label small text-secondary" for="remember">
                    Mantener sesión iniciada
                </label>
            </div>

            <button type="submit" class="btn-login">
                Iniciar Sesión
            </button>
        </form>

        <div class="login-footer">
            Ges_Taller v2.0 &bull; Sistema Pro v2026
        </div>
    </div>
</body>

</html>