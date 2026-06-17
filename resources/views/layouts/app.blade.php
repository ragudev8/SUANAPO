<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SUANAPO - @yield('title', 'Sistema')</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    @php
        $impersonator = session('impersonator_id') ? \App\Models\Usuario::find(session('impersonator_id')) : null;
        $canImpersonate = auth()->user()->rol === 'super_admin' || $impersonator?->rol === 'super_admin';
        $viewAsUsers = $canImpersonate
            ? \App\Models\Usuario::where('activo', true)->orderBy('nombre')->get(['id', 'nombre', 'rol'])
            : collect();
    @endphp
    <nav class="anapo-navbar">
        <div class="anapo-topbar">
            <div class="anapo-topbar-left">
                <button class="anapo-icon-btn d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu" aria-controls="mobileMenu" aria-label="Abrir menu">
                    <i class="bi bi-list"></i>
                </button>
                <button class="anapo-icon-btn d-none d-md-inline-flex" type="button" data-sidebar-toggle aria-label="Ocultar menu">
                    <i class="bi bi-layout-sidebar-inset"></i>
                </button>
            </div>

            <a class="anapo-brand" href="{{ route('dashboard') }}">
                <img class="brand-logo" src="{{ asset('images/anapo-logo.png') }}" onerror="this.onerror=null;this.src='{{ asset('images/anapo-logo.svg') }}';" alt="Logo ANAPO">
                <span class="brand-title">
                    <span class="brand-main">SUANAPO</span>
                    <span class="brand-kicker">Sistema Unificado ANAPO</span>
                </span>
            </a>

            <div class="anapo-topbar-actions">
                @if ($canImpersonate)
                    <form class="view-as-form" method="post" action="{{ route('impersonation.start') }}">
                        @csrf
                        <label class="visually-hidden" for="viewAsUser">Ver como usuario</label>
                        <i class="bi bi-person-bounding-box"></i>
                        <select id="viewAsUser" class="form-select form-select-sm anapo-view-select" name="usuario_id" onchange="this.form.submit()" title="Ver como usuario">
                            @foreach ($viewAsUsers as $viewAsUser)
                                <option value="{{ $viewAsUser->id }}" @selected(auth()->id() === $viewAsUser->id)>
                                    {{ $viewAsUser->nombre }} ({{ $viewAsUser->rol_label }})
                                </option>
                            @endforeach
                        </select>
                    </form>
                    @if ($impersonator)
                        <form method="post" action="{{ route('impersonation.stop') }}">
                            @csrf
                            @method('delete')
                            <button class="anapo-icon-btn" type="submit" aria-label="Volver a super admin" title="Volver a super admin">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </button>
                        </form>
                    @endif
                @endif
                <span class="user-chip">
                    <i class="bi bi-person-circle"></i>
                    <span>{{ auth()->user()->nombre }}</span>
                </span>
                <span class="role-chip" title="Tipo de usuario">
                    <i class="bi {{ auth()->user()->rol_icon }}"></i>
                    <span>{{ auth()->user()->rol_label }}</span>
                </span>
                @if ($canImpersonate)
                    <a class="anapo-icon-btn" href="{{ route('permisos.index') }}" aria-label="Cambiar permisos" title="Cambiar permisos">
                        <i class="bi bi-shield-lock"></i>
                    </a>
                @endif
                <form method="post" action="{{ route('logout') }}">
                    @csrf
                    <button class="anapo-icon-btn" type="submit" aria-label="Cerrar sesion">
                        <i class="bi bi-box-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="offcanvas offcanvas-start anapo-offcanvas d-md-none" tabindex="-1" id="mobileMenu" aria-labelledby="mobileMenuLabel">
        <div class="offcanvas-header">
            <div class="d-flex align-items-center gap-2">
                <img class="brand-logo" src="{{ asset('images/anapo-logo.png') }}" onerror="this.onerror=null;this.src='{{ asset('images/anapo-logo.svg') }}';" alt="Logo ANAPO">
                <div>
                    <h2 class="h6 mb-0" id="mobileMenuLabel">SUANAPO</h2>
                    <div class="brand-kicker">Menu principal</div>
                </div>
            </div>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Cerrar"></button>
        </div>
        <div class="offcanvas-body">
            @include('layouts.sidebar')
        </div>
    </div>

    <div class="container-fluid app-shell">
        <div class="row g-0">
            <aside class="d-none d-md-block col-md-3 col-xl-2 sidebar p-3" data-sidebar>
                @include('layouts.sidebar')
            </aside>
            <main class="col-12 col-md-9 col-xl-10 p-3 p-md-4" data-main-content>
                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>

    <div class="modal fade" id="confirmActionModal" tabindex="-1" aria-labelledby="confirmActionTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <div>
                        <h2 class="modal-title h5" id="confirmActionTitle">
                            <i class="bi bi-question-circle me-2 text-primary"></i>Confirmar accion
                        </h2>
                        <p class="text-secondary mb-0 small">Revise la accion antes de continuar.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0" data-confirm-message>Desea continuar?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Cancelar
                    </button>
                    <button type="button" class="btn btn-primary" data-confirm-accept>
                        <i class="bi bi-check2-circle me-1"></i>Confirmar
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
