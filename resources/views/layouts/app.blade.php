<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Gestiona tu salud y controla tu diabetes con el dashboard inteligente de DiabTrack. Visualiza tus progresos y registros diarios.">
    <meta name="keywords" content="diabetes dashboard, seguimiento salud, control glucemia, registro diabetes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Meta etiquetas para Redes Sociales -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ config('app.url') }}">
    <meta property="og:title" content="DiabTrack - Dashboard de Salud">
    <meta property="og:description" content="Gestiona tu salud de manera efectiva con DiabTrack. Registra signos vitales, alimentación y síntomas.">
    <meta property="og:image" content="{{ asset('og-image.jpg') }}">
    
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:title" content="DiabTrack - Tu Dashboard de Salud">
    <meta property="twitter:image" content="{{ asset('og-image.jpg') }}">

    <title>@yield('title', 'DiabTrack - Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @vite(['resources/css/design-system.css', 'resources/css/dashboardc.css', 'resources/js/app.js'])
    @yield('styles')
</head>
<body class="animate-fade-in">
    <div class="main-content-push">
        <div class="content-body">
            <header class="navbar shadow-sm border-bottom glass-effect sticky-md-top py-2">
                <div class="navbar-content container-fluid px-md-5 d-flex justify-content-between align-items-center">
                    <a href="{{ route('dashboard') }}" class="diab-logo text-decoration-none">
                        D<span>ia</span>bTrack
                    </a>
                    
                    <div class="nav-search d-none d-lg-block">
                        <input type="text" class="form-control" placeholder="Buscar...">
                    </div>

                    <!-- Desktop Navigation (hidden on mobile via CSS) -->
                    <nav class="nav-menu d-none d-md-flex">
                        <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="fa-solid fa-house"></i>
                            <span>Inicio</span>
                        </a>
                        <a href="{{ route('tracking.summary') }}" class="nav-item {{ request()->routeIs('tracking.summary') ? 'active' : '' }}">
                            <i class="fa-solid fa-chart-column"></i>
                            <span>Resumen</span>
                        </a>
                        <a href="{{ route('tracking.vital.create') }}" class="nav-item {{ request()->routeIs('tracking.*') && !request()->routeIs('tracking.summary') ? 'active' : '' }}">
                            <i class="fa-solid fa-plus"></i>
                            <span>Nuevo</span>
                        </a>
                    </nav>

                <div class="user-section d-flex align-items-center">
                        <a href="#" class="nav-item me-3 text-muted">
                            <i class="fa-solid fa-bell notification fs-5"></i>
                        </a>
                        <div class="user-card border bg-white shadow-sm p-1 ps-3 rounded-pill d-flex align-items-center">
                            <div class="user-text d-none d-xl-block me-2">
                                <span class="user-name fw-bold small d-block">{{ auth()->user()->name }}</span>
                                <span class="user-email text-muted extra-small" style="font-size: 0.7rem;">{{ auth()->user()->email }}</span>
                            </div>
                            <div class="user-avatar rounded-circle overflow-hidden shadow-sm" style="width: 36px; height: 36px;">
                                @php
                                    $gender = strtolower(auth()->user()->patientProfile->gender ?? '');
                                @endphp
                                @if($gender === 'femenino')
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center text-white" style="background: linear-gradient(135deg, #FF6B6B, #C0392B);">
                                        <i class="fa-solid fa-person-dress fs-5"></i>
                                    </div>
                                @elseif($gender === 'masculino')
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center text-white" style="background: linear-gradient(135deg, #4A90E2, #2980B9);">
                                        <i class="fa-solid fa-user-tie fs-5"></i>
                                    </div>
                                @else
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center text-white bg-secondary">
                                        <i class="fa-solid fa-user fs-5"></i>
                                    </div>
                                @endif
                            </div>
                            <form method="POST" action="{{ route('logout') }}" class="ms-2 pe-2 border-start ps-2">
                                @csrf
                                <button type="submit" class="btn btn-link p-0 text-danger" title="Cerrar Sesión">
                                    <i class="fa-solid fa-right-from-bracket"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            @yield('content')
        </div>

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
    </div>

    <!-- Mobile Navigation (fixed bottom, shown only on mobile via CSS) -->
    <nav class="nav-menu d-flex d-md-none">
        <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-house"></i>
            <span>Inicio</span>
        </a>
        <a href="{{ route('tracking.summary') }}" class="nav-item {{ request()->routeIs('tracking.summary') ? 'active' : '' }}">
            <i class="fa-solid fa-chart-column"></i>
            <span>Resumen</span>
        </a>
        <a href="{{ route('tracking.vital.create') }}" class="nav-item {{ request()->routeIs('tracking.*') && !request()->routeIs('tracking.summary') ? 'active' : '' }}">
            <i class="fa-solid fa-plus"></i>
            <span>Nuevo</span>
        </a>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
    @yield('scripts')
</body>
</html>
