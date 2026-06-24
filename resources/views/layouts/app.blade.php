<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Gestiona tu salud y controla tu diabetes con el dashboard inteligente de DiabTrack. Visualiza tus progresos y registros diarios.">
    <meta name="keywords" content="diabetes dashboard, seguimiento salud, control glucemia, registro diabetes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    
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
                    
                    @if(auth()->user()->isPatient())
                    <div class="nav-search d-none d-lg-block">
                        <input type="text" class="form-control" placeholder="Buscar...">
                    </div>
                    @endif

                    <!-- Desktop Navigation (hidden on mobile via CSS) -->
                    <nav class="nav-menu d-none d-md-flex">
                        @if(auth()->user()->isPatient())
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
                        @endif
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
                            <a href="{{ route('profile.edit') }}" class="user-avatar rounded-circle overflow-hidden shadow-sm d-block" style="width: 36px; height: 36px; cursor: pointer;">
                                @php
                                    $user = auth()->user();
                                    $gender = strtolower($user->patientProfile?->gender ?? '');
                                    $avatar = $user->avatar;
                                @endphp
                                @if($avatar && str_starts_with($avatar, 'http'))
                                    <img src="{{ $avatar }}" alt="User" class="w-100 h-100 object-fit-cover">
                                @elseif($avatar && trim($avatar) !== '')
                                    <img src="{{ asset('storage/' . $avatar) }}" alt="User" class="w-100 h-100 object-fit-cover">
                                @elseif($gender === 'femenino')
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center text-white" style="background: linear-gradient(135deg, #FF6B6B, #C0392B);">
                                        <i class="fa-solid fa-person-dress fs-5"></i>
                                    </div>
                                @elseif($gender === 'masculino')
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center text-white" style="background: linear-gradient(135deg, #4A90E2, #2980B9);">
                                        <i class="fa-solid fa-user-tie fs-5"></i>
                                    </div>
                                @else
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center text-white" style="background: linear-gradient(135deg, #95a5a6, #7f8c8d);">
                                        <i class="fa-solid fa-user fs-5"></i>
                                    </div>
                                @endif
                            </a>
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
        @if(auth()->user()->isPatient())
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
        @endif
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // ── DiabTrack SweetAlert theme ────────────────────────────────────────
        const DiabSwal = Swal.mixin({
            customClass: {
                popup:          'diabswal-popup',
                title:          'diabswal-title',
                htmlContainer:  'diabswal-html',
                confirmButton:  'diabswal-btn diabswal-btn-confirm',
                cancelButton:   'diabswal-btn diabswal-btn-cancel',
                icon:           'diabswal-icon',
            },
            buttonsStyling: false,
            showClass: {
                popup: 'animate__animated animate__fadeInDown animate__faster'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp animate__faster'
            }
        });

        // Inject DiabSwal styles once
        (function() {
            const style = document.createElement('style');
            style.textContent = `
                .diabswal-popup {
                    border-radius: 24px !important;
                    font-family: 'Inter', sans-serif !important;
                    background: #FFFFFF !important;
                    box-shadow: 0 20px 40px -10px rgba(0,0,0,0.12), 0 8px 16px -4px rgba(0,180,216,0.08) !important;
                    padding: 2rem !important;
                    border: 1px solid rgba(0,180,216,0.12) !important;
                }
                .diabswal-title {
                    font-family: 'Inter', sans-serif !important;
                    font-weight: 700 !important;
                    font-size: 1.2rem !important;
                    color: #0F172A !important;
                    letter-spacing: -0.02em !important;
                }
                .diabswal-html {
                    font-family: 'Inter', sans-serif !important;
                    font-size: 0.9rem !important;
                    color: #64748B !important;
                }
                .diabswal-btn {
                    display: inline-flex !important;
                    align-items: center !important;
                    justify-content: center !important;
                    padding: 0.6rem 1.6rem !important;
                    border-radius: 12px !important;
                    font-family: 'Inter', sans-serif !important;
                    font-size: 0.875rem !important;
                    font-weight: 600 !important;
                    border: none !important;
                    cursor: pointer !important;
                    transition: all 0.25s cubic-bezier(0.4,0,0.2,1) !important;
                    letter-spacing: -0.01em !important;
                }
                .diabswal-btn-confirm {
                    background: linear-gradient(135deg, #00B4D8 0%, #0096C7 100%) !important;
                    color: #fff !important;
                    box-shadow: 0 4px 14px rgba(0,180,216,0.35) !important;
                }
                .diabswal-btn-confirm:hover {
                    background: linear-gradient(135deg, #0096C7 0%, #0077A8 100%) !important;
                    box-shadow: 0 6px 20px rgba(0,180,216,0.45) !important;
                    transform: translateY(-1px) !important;
                }
                .diabswal-btn-cancel {
                    background: rgba(0,180,216,0.08) !important;
                    color: #00B4D8 !important;
                    border: 1px solid rgba(0,180,216,0.2) !important;
                }
                .diabswal-btn-cancel:hover {
                    background: rgba(0,180,216,0.15) !important;
                    transform: translateY(-1px) !important;
                }
                .swal2-actions { gap: 0.6rem !important; }
                .diabswal-icon.swal2-success { border-color: #28C76F !important; }
                .diabswal-icon.swal2-success [class^="swal2-success-line"] { background: #28C76F !important; }
                .diabswal-icon.swal2-success .swal2-success-ring { border-color: rgba(40,199,111,0.25) !important; }
                .diabswal-icon.swal2-error { border-color: #EA5455 !important; }
                .diabswal-icon.swal2-error [class^="swal2-x-mark-line"] { background: #EA5455 !important; }
                .diabswal-icon.swal2-warning { border-color: #FF9F43 !important; color: #FF9F43 !important; }
                .diabswal-icon.swal2-info { border-color: #00CFE8 !important; color: #00CFE8 !important; }
                .diabswal-icon.swal2-question { border-color: #00B4D8 !important; color: #00B4D8 !important; }
            `;
            document.head.appendChild(style);
        })();
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Intercept health tracking forms
            document.querySelectorAll('form.tracking-form-layout').forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn) submitBtn.disabled = true;

                    const formData = new FormData(form);
                    const action = form.getAttribute('action');
                    const method = form.getAttribute('method') || 'POST';

                    fetch(action, {
                        method: method,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: formData
                    })
                    .then(response => {
                        if (submitBtn) submitBtn.disabled = false;
                        
                        if (response.status === 422) {
                            return response.json().then(data => {
                                let errorHtml = '<ul class="text-start" style="list-style-type: none; padding-left: 0; margin-bottom: 0;">';
                                Object.values(data.errors).forEach(errArray => {
                                    errArray.forEach(err => {
                                        errorHtml += `<li><i class="fa-solid fa-circle-xmark text-danger me-2"></i>${err}</li>`;
                                    });
                                });
                                errorHtml += '</ul>';
                                
                                DiabSwal.fire({
                                    icon: 'error',
                                    title: 'Errores de Validación',
                                    html: errorHtml,
                                });
                            });
                        } else if (!response.ok) {
                            throw new Error('Server error');
                        } else {
                            return response.json().then(data => {
                                DiabSwal.fire({
                                    icon: 'success',
                                    title: '¡Guardado!',
                                    text: data.message || 'El registro se guardó correctamente.',
                                }).then(() => {
                                    form.reset();
                                    
                                    // Trigger range inputs oninput events to reset text values
                                    form.querySelectorAll('input[type="range"]').forEach(range => {
                                        if (range.oninput) range.oninput();
                                    });
                                });
                            });
                        }
                    })
                    .catch(err => {
                        if (submitBtn) submitBtn.disabled = false;
                        DiabSwal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Hubo un problema al guardar el registro. Inténtalo de nuevo.',
                        });
                    });
                });
            });
        });
    </script>
    @yield('scripts')
</body>
</html>
