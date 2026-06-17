<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SUANAPO - Ingreso</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body class="login-screen">
    <main class="container min-vh-100 d-flex align-items-center justify-content-center">
        <div class="card login-card shadow border-0 overflow-hidden" style="max-width: 430px; width: 100%;">
            <div class="card-body p-4">
                <div class="text-center mb-3">
                    <img class="brand-logo-lg mb-2" src="{{ asset('images/anapo-logo.png') }}" onerror="this.onerror=null;this.src='{{ asset('images/anapo-logo.svg') }}';" alt="Logo ANAPO">
                    <h1 class="h4 mb-1">SUANAPO</h1>
                    <p class="text-secondary mb-0">Sistema Unificado Academia Nacional de Policia</p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger py-2">{{ $errors->first() }}</div>
                @endif

                <form method="post" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3">
                    <label class="form-label" for="email"><i class="bi bi-envelope me-1"></i>Correo</label>
                        <input class="form-control" id="email" name="email" type="email" value="{{ old('email') }}" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="password"><i class="bi bi-key me-1"></i>Contrasena</label>
                        <input class="form-control" id="password" name="password" type="password" required>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" id="remember" name="remember" type="checkbox" value="1">
                        <label class="form-check-label" for="remember">Mantener sesion</label>
                    </div>
                    <button class="btn btn-success w-100" type="submit"><i class="bi bi-box-arrow-in-right me-1"></i>Entrar</button>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
