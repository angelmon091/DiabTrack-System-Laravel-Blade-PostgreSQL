<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DiabTrack - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/ingreso_de_datos_global.css') }}">
    @yield('styles')
</head>
<body>

    <header class="navbar">
        <div class="navbar-content">
            <div class="brand-area">
                <a href="{{ route('dashboard') }}" class="logo-small"><h2 class="logo-small">D<span>ia</span>bTrack</h2></a>
            </div>
            
            <div class="nav-search d-none d-lg-block">
                <input type="text" class="form-control" placeholder="Buscar...">
            </div>

            <nav class="nav-menu">
                <a href="{{ route('dashboard') }}" class="nav-item">
                    <i class="fa-solid fa-house"></i>
                    <span>Inicio</span>
                </a>
                <a href="{{ route('registro.historial') }}" class="nav-item {{ request()->routeIs('registro.historial') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-column"></i>
                    <span>Resumen</span>
                </a>
                <a href="{{ route('registro.signos') }}" class="nav-item active">
                    <i class="fa-solid fa-plus"></i>
                    <span>Nuevo</span>
                </a>
            </nav>

            <div class="user-section">
                <a href="#" class="nav-item me-3">
                    <i class="fa-solid fa-bell notification"></i>
                </a>
                <div class="user-card">
                    <div class="user-text d-none d-sm-block">
                        <span class="user-name">{{ auth()->user()->name }}</span>
                        <span class="user-email">{{ auth()->user()->email }}</span>
                    </div>
                    <div class="user-avatar">
                        <img src="{{ asset('img/medios/etc/yo.jpg') }}" alt="yo">
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="container">
        <h1>Registro de Datos</h1>

        <section class="categories">
            <a href="{{ route('registro.signos') }}" class="card {{ request()->routeIs('registro.signos') ? 'active-card' : '' }}">Signos Vitales</a>
            <a href="{{ route('registro.sintomas') }}" class="card {{ request()->routeIs('registro.sintomas') ? 'active-card' : '' }}">Síntomas</a>
            <a href="{{ route('registro.nutricion') }}" class="card {{ request()->routeIs('registro.nutricion') ? 'active-card' : '' }}">Nutrición</a>
            <a href="{{ route('registro.movimiento') }}" class="card {{ request()->routeIs('registro.movimiento') ? 'active-card' : '' }}">Movimiento</a>
        </section>

        @yield('content')

    </main>

    <footer class="site-footer">
        <div class="footer-content">
            <div class="links">
                <a href="https://youtube.com">Políticas de Privacidad</a>
                <a href="#">Términos y Condiciones</a>
                <a href="#">Desarrolladores</a>
            </div>
            <div class="social-icons">
                <a href="https://youtube.com"><i class="fa-brands fa-instagram"></i></a>
                <i class="fa-brands fa-facebook"></i>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
