@extends('layouts.app')

@section('title', 'DiabTrack - Dashboard')

@section('content')
    <main class="container-fluid py-4 px-md-5">
        <div class="row g-4">

            <aside class="col-12 col-xl-3">
                <div class="diab-card p-4 mb-4 shadow-sm animate-fade-in">
                    <div class="tool-header mb-4 d-flex align-items-center text-diab-primary">
                        <i class="fa-solid fa-gear me-2"></i>
                        <span class="fw-bold">Gestión DiabTrack</span>
                    </div>

                    <div class="d-flex flex-column gap-2">
                        <a href="#" class="action-item diab-card-hover">
                            <div class="action-icon orange"><i class="fa-solid fa-robot"></i></div>
                            <div class="ms-3">
                                <strong class="d-block">Nutrición IA</strong>
                                <p class="mb-0 extra-small text-muted">Planificación de comidas</p>
                            </div>
                        </a>
                        <a href="#" class="action-item diab-card-hover">
                            <div class="action-icon blue"><i class="fa-solid fa-chart-line"></i></div>
                            <div class="ms-3">
                                <strong class="d-block">Gráficos</strong>
                                <p class="mb-0 extra-small text-muted">Análisis de tendencias</p>
                            </div>
                        </a>
                        <a href="#" class="action-item diab-card-hover">
                            <div class="action-icon green"><i class="fa-solid fa-plus"></i></div>
                            <div class="ms-3">
                                <strong class="d-block">Registrar</strong>
                                <p class="mb-0 extra-small text-muted">Añadir entrada diaria</p>
                            </div>
                        </a>
                        <a href="#" class="action-item diab-card-hover">
                            <div class="action-icon gray"><i class="fa-solid fa-sliders"></i></div>
                            <div class="ms-3">
                                <strong class="d-block">Ajustes</strong>
                                <p class="mb-0 extra-small text-muted">Configurar perfil</p>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="diab-card p-4 d-none d-xl-block animate-fade-in" style="animation-delay: 0.1s;">
                    <h6 class="fw-bold mb-3 text-center text-diab-secondary">Métricas Semanales</h6>
                    <div class="rounded-4 overflow-hidden shadow-sm border">
                        <img src="{{ asset('img/medios/etc/Rectangle 35.png') }}" alt="Gráfico" class="w-100 h-auto">
                    </div>
                </div>
            </aside>

            <section class="col-12 col-xl-9">
                <div class="d-flex justify-content-between align-items-center mb-4 animate-fade-in">
                    <h3 class="fw-bold mb-0 fs-4">Resumen de Datos <span class="text-diab-primary">Total</span></h3>
                    <div class="text-muted small d-none d-sm-block bg-white px-3 py-1 rounded-pill border">
                        {{ date('d M, Y') }}</div>
                </div>

                <div class="diab-card glucosa-hero p-4 p-md-5 mb-4 animate-fade-in" style="animation-delay: 0.2s;">
                    <div class="row align-items-center">
                        <div class="col-12 col-md-6 text-center text-md-start">
                            <span
                                class="text-diab-text-secondary fw-bold small mb-2 d-block text-uppercase letter-spacing-1">Glucosa
                                en Ayunas</span>
                            <div class="d-flex align-items-baseline justify-content-center justify-content-md-start">
                                <h1 class="display-1 fw-extrabold mb-0 text-dark">
                                    {{ $ultimaMedicion->glucose_level ?? '--' }}
                                </h1>
                                <span class="ms-2 fs-4 text-muted">mg/DL</span>
                            </div>

                            @if($ultimaMedicion && $ultimaMedicion->glucose_level > 140)
                                <div class="vital-trend-pill mt-3 d-inline-block shadow-sm text-danger border-danger">
                                    <i class="fa-solid fa-triangle-exclamation me-1"></i> Nivel Elevado
                                </div>
                            @elseif($ultimaMedicion && $ultimaMedicion->glucose_level < 70)
                                <div class="vital-trend-pill mt-3 d-inline-block shadow-sm text-warning border-warning">
                                    <i class="fa-solid fa-droplet-slash me-1"></i> Nivel Bajo
                                </div>
                            @else
                                <div class="vital-trend-pill mt-3 d-inline-block shadow-sm">
                                    <i class="fa-solid fa-circle-check me-1"></i> En rango aceptable
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row g-4 mb-5">
                    <div class="col-12 col-md-4">
                        <div class="diab-card p-4 diab-card-hover animate-fade-in" style="animation-delay: 0.3s;">
                            <div class="stat-top mb-2 small fw-bold text-diab-text-secondary">
                                <i class="fa-solid fa-dna text-diab-info me-2"></i> A1c Estimada
                            </div>
                            <h2 class="fw-extrabold mb-1">6.7%</h2>
                            <p class="text-muted extra-small mb-0">Últimos 90 días</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="diab-card p-4 diab-card-hover animate-fade-in" style="animation-delay: 0.4s;">
                            <div class="stat-top mb-2 small fw-bold text-diab-text-secondary">
                                <i class="fa-solid fa-bread-slice text-diab-warning me-2"></i> Carbohidratos
                            </div>
                            <h2 class="fw-extrabold mb-1">180g</h2>
                            <p class="text-muted extra-small mb-0">Meta diaria: 200g</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="diab-card p-4 diab-card-hover animate-fade-in" style="animation-delay: 0.5s;">
                            <div class="stat-top mb-2 small fw-bold text-diab-text-secondary">
                                <i class="fa-solid fa-clock-rotate-left text-diab-success me-2"></i> Tiempo en Rango
                            </div>
                            <h2 class="fw-extrabold mb-0">85%</h2>
                            <p class="text-muted extra-small mb-0">Excelente control</p>
                        </div>
                    </div>
                </div>

                <h5 class="fw-bold mb-4 fs-6 text-diab-text-secondary text-uppercase letter-spacing-1">Aspectos Importantes
                </h5>

                <div class="d-flex flex-column gap-3 mb-4">
                    <div class="diab-card p-3 p-md-4 animate-fade-in" style="animation-delay: 0.6s;">
                        <div class="row align-items-center g-3">
                            <div class="col-12 col-md-3">
                                <div class="d-flex align-items-center">
                                    <div class="act-icon fire me-3 shadow-sm"><i class="fa-solid fa-fire"></i></div>
                                    <div>
                                        <strong class="d-block small">Calorías</strong>
                                        <span class="text-muted extra-small">2500 / 800kcal</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-9 col-md-8 px-md-4">
                                <div class="progress-container shadow-sm border-0">
                                    <div class="progress-bar-custom bg-diab-danger shadow"
                                        style="width: 75%; background-color: var(--diab-danger) !important;"></div>
                                </div>
                            </div>
                            <div class="col-3 col-md-1 text-end fw-bold small text-diab-danger">75%</div>
                        </div>
                    </div>

                    <div class="diab-card p-3 p-md-4 animate-fade-in" style="animation-delay: 0.7s;">
                        <div class="row align-items-center g-3">
                            <div class="col-12 col-md-3">
                                <div class="d-flex align-items-center">
                                    <div class="act-icon move me-3 shadow-sm"><i class="fa-solid fa-bolt"></i></div>
                                    <div>
                                        <strong class="d-block small">Actividad</strong>
                                        <span class="text-muted extra-small">450 / 800kcal</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-9 col-md-8 px-md-4">
                                <div class="progress-container shadow-sm border-0">
                                    <div class="progress-bar-custom shadow"
                                        style="width: 50%; background-color: var(--diab-warning) !important;"></div>
                                </div>
                            </div>
                            <div class="col-3 col-md-1 text-end fw-bold small text-diab-warning">50%</div>
                        </div>
                    </div>

                    <div class="diab-card p-3 p-md-4 animate-fade-in" style="animation-delay: 0.8s;">
                        <div class="row align-items-center g-3">
                            <div class="col-12 col-md-3">
                                <div class="d-flex align-items-center">
                                    <div class="act-icon feet me-3 shadow-sm"><i class="fa-solid fa-shoe-prints"></i></div>
                                    <div>
                                        <strong class="d-block small">Pasos</strong>
                                        <span class="text-muted extra-small">6987 / 8000</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-9 col-md-8 px-md-4">
                                <div class="progress-container shadow-sm border-0">
                                    <div class="progress-bar-custom shadow"
                                        style="width: 87%; background-color: var(--diab-primary) !important;"></div>
                                </div>
                            </div>
                            <div class="col-3 col-md-1 text-end fw-bold small text-diab-primary">87%</div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
    </div>
    </main>
@endsection