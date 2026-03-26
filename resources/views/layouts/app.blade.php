<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'DiabTrack - Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/design-system.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboardc.css') }}">
    @yield('styles')
</head>
<body class="animate-fade-in">

    <header class="navbar shadow-sm border-bottom glass-effect sticky-top py-2">
        <div class="navbar-content container-fluid px-md-5">
            <a href="{{ route('dashboard') }}" class="diab-logo text-decoration-none">
                D<span>ia</span>bTrack
            </a>
            
            <div class="nav-search d-none d-lg-block">
                <input type="text" class="form-control" placeholder="Buscar...">
            </div>

            <nav class="nav-menu">
                <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-house"></i>
                    <span>Inicio</span>
                </a>
                <a href="{{ route('dashboard') }}" class="nav-item">
                    <i class="fa-solid fa-chart-column"></i>
                    <span>Resumen</span>
                </a>
                <a href="{{ route('tracking.vital.create') }}" class="nav-item {{ request()->routeIs('tracking.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-plus"></i>
                    <span>Nuevo</span>
                </a>
            </nav>

            <div class="user-section d-flex align-items-center">
                <a href="#" class="nav-item me-3 text-muted">
                    <i class="fa-solid fa-bell notification fs-5"></i>
                </a>
                <div class="user-card border bg-white shadow-sm p-1 ps-3 rounded-pill d-flex align-items-center">
                    <div class="user-text d-none d-sm-block me-2">
                        <span class="user-name fw-bold small d-block">{{ auth()->user()->name }}</span>
                        <span class="user-email text-muted extra-small" style="font-size: 0.7rem;">{{ auth()->user()->email }}</span>
                    </div>
                    <div class="user-avatar rounded-circle overflow-hidden shadow-sm" style="width: 36px; height: 36px;">
                        <img src="{{ asset('img/medios/etc/yo.jpg') }}" alt="User" class="w-100 h-100 object-fit-cover">
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="ms-2 pe-2 border-start ps-2">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 text-danger" title="Cerrar Sesión">
                            <i class="fa-solid fa-power-off"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    @yield('content')

    <footer class="site-footer bg-white border-top py-5 mt-auto">
        <div class="container-fluid px-md-5">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start mb-4 mb-md-0">
                    <a href="{{ route('dashboard') }}" class="diab-logo text-decoration-none mb-3">
                        D<span>ia</span>bTrack
                    </a>
                    <div class="footer-links d-flex gap-4 justify-content-center justify-content-md-start">
                        <a href="#" class="text-muted text-decoration-none small">Políticas</a>
                        <a href="#" class="text-muted text-decoration-none small">Términos</a>
                        <a href="#" class="text-muted text-decoration-none small">Ayuda</a>
                    </div>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <div class="social-icons fs-4 d-flex gap-3 justify-content-center justify-content-md-end mb-3">
                        <a href="#" class="text-muted hover-diab-primary"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" class="text-muted hover-diab-primary"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#" class="text-muted hover-diab-primary"><i class="fa-brands fa-twitter"></i></a>
                    </div>
                    <p class="text-muted small mb-0">&copy; {{ date('Y') }} DiabTrack App. Cuidando tu salud.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
