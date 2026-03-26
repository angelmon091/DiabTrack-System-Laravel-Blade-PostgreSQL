@extends('layouts.app')

@section('title', 'DiabTrack - Visualización Detallada')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/visualizacion.css') }}">
@endsection

@section('content')
<main class="container-fluid py-4 px-md-5">

    {{-- ===== PAGE HEADER ===== --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 animate-fade-in">
        <div>
            <h3 class="fw-extrabold mb-1 fs-4">Visualización <span class="text-diab-primary">Detallada</span></h3>
            <p class="text-muted small mb-0">Análisis completo de tus métricas de salud</p>
        </div>
        <div class="d-flex gap-2 mt-3 mt-md-0">
            <button class="btn btn-diab-secondary btn-sm px-3">
                <i class="fa-solid fa-clock me-1"></i> Últimos 7 días ▾
            </button>
            <button class="btn btn-diab-primary btn-sm px-3">
                <i class="fa-solid fa-download me-1"></i> Exportar
            </button>
        </div>
    </div>

    {{-- ============================================================
         SECCIÓN 1: GLUCOSA EN SANGRE (de signos.html)
         ============================================================ --}}
    <div class="diab-card p-4 mb-4 animate-fade-in" style="animation-delay: 0.1s;">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
            <div>
                <h5 class="fw-bold mb-1"><i class="fa-solid fa-droplet text-diab-primary me-2"></i>Glucosa en Sangre</h5>
                <p class="text-muted extra-small mb-0">Tendencia detallada con valores mínimos, máximos y promedios</p>
            </div>
            <div class="viz-time-pills mt-2 mt-md-0">
                <span class="viz-time-pill">Hoy</span>
                <span class="viz-time-pill active">1S</span>
                <span class="viz-time-pill">1M</span>
                <span class="viz-time-pill">3M</span>
                <span class="viz-time-pill">6M</span>
            </div>
        </div>

        {{-- Stats Row --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="viz-detail-stat">
                    <span class="viz-detail-label">Actual</span>
                    <span class="viz-detail-value text-diab-primary">98</span>
                    <span class="viz-detail-unit">mg/DL</span>
                    <span class="badge bg-diab-success-light text-diab-success mt-1">Normal</span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="viz-detail-stat">
                    <span class="viz-detail-label">Promedio 7 Días</span>
                    <span class="viz-detail-value">125</span>
                    <span class="viz-detail-unit">mg/DL</span>
                    <span class="badge bg-diab-warning-light text-diab-warning mt-1">↑ +3.2%</span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="viz-detail-stat">
                    <span class="viz-detail-label">Mínimo</span>
                    <span class="viz-detail-value text-diab-info">72</span>
                    <span class="viz-detail-unit">mg/DL</span>
                    <span class="extra-small text-muted mt-1">24 Ene, 06:30</span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="viz-detail-stat">
                    <span class="viz-detail-label">Máximo</span>
                    <span class="viz-detail-value text-diab-danger">189</span>
                    <span class="viz-detail-unit">mg/DL</span>
                    <span class="extra-small text-muted mt-1">22 Ene, 14:15</span>
                </div>
            </div>
        </div>

        {{-- Glucose by Measurement Moment --}}
        <h6 class="fw-bold small text-diab-text-secondary text-uppercase mb-3">
            <i class="fa-solid fa-clock me-1"></i> Promedios por Momento de Medición
        </h6>
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="viz-moment-card">
                    <img src="{{ asset('img/medios/iconos/wb_sunny.png') }}" alt="Ayunas" class="viz-moment-icon">
                    <span class="viz-moment-label">Ayunas</span>
                    <span class="viz-moment-value">95</span>
                    <span class="viz-detail-unit">mg/DL</span>
                    <span class="badge bg-diab-success-light text-diab-success">Normal</span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="viz-moment-card">
                    <img src="{{ asset('img/medios/iconos/no_meals_ouline.png') }}" alt="Antes" class="viz-moment-icon">
                    <span class="viz-moment-label">Antes de Comer</span>
                    <span class="viz-moment-value">102</span>
                    <span class="viz-detail-unit">mg/DL</span>
                    <span class="badge bg-diab-success-light text-diab-success">Normal</span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="viz-moment-card">
                    <img src="{{ asset('img/medios/iconos/restaurant.png') }}" alt="Después" class="viz-moment-icon">
                    <span class="viz-moment-label">Después de Comer</span>
                    <span class="viz-moment-value">158</span>
                    <span class="viz-detail-unit">mg/DL</span>
                    <span class="badge bg-diab-warning-light text-diab-warning">Elevado</span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="viz-moment-card">
                    <img src="{{ asset('img/medios/iconos/bedtime.png') }}" alt="Dormir" class="viz-moment-icon">
                    <span class="viz-moment-label">Al Dormir</span>
                    <span class="viz-moment-value">118</span>
                    <span class="viz-detail-unit">mg/DL</span>
                    <span class="badge bg-diab-success-light text-diab-success">Normal</span>
                </div>
            </div>
        </div>

        {{-- Large Chart --}}
        <div class="viz-chart-area viz-chart-lg">
            <div class="viz-graph-lines">
                <div class="viz-graph-line blue"></div>
                <div class="viz-graph-line green"></div>
                <div class="viz-graph-line orange"></div>
            </div>
            <div class="viz-bars">
                <div class="viz-bar" style="height: 30%;"></div>
                <div class="viz-bar" style="height: 55%;"></div>
                <div class="viz-bar" style="height: 40%;"></div>
                <div class="viz-bar" style="height: 70%;"></div>
                <div class="viz-bar" style="height: 25%;"></div>
                <div class="viz-bar" style="height: 80%;"></div>
                <div class="viz-bar" style="height: 45%;"></div>
                <div class="viz-bar" style="height: 60%;"></div>
                <div class="viz-bar" style="height: 35%;"></div>
                <div class="viz-bar" style="height: 50%;"></div>
            </div>
            <div class="viz-range-zone"></div>
        </div>
        <div class="viz-legend mt-3">
            <div class="viz-legend-item"><div class="viz-legend-dot" style="background: var(--diab-primary);"></div> Glucosa</div>
            <div class="viz-legend-item"><div class="viz-legend-dot" style="background: var(--diab-success);"></div> Rango objetivo (70-130)</div>
            <div class="viz-legend-item"><div class="viz-legend-dot" style="background: var(--diab-warning);"></div> Tendencia</div>
        </div>
    </div>

    {{-- ============================================================
         SECCIÓN 2: A1c + Tiempo en Rango (de signos.html)
         ============================================================ --}}
    <div class="row g-4 mb-4">
        {{-- A1c --}}
        <div class="col-12 col-lg-6">
            <div class="diab-card p-4 h-100 animate-fade-in" style="animation-delay: 0.2s;">
                <h5 class="fw-bold mb-3"><i class="fa-solid fa-dna text-diab-info me-2"></i>Hemoglobina Glicosilada (A1c)</h5>
                <div class="row g-3 mb-3">
                    <div class="col-4">
                        <div class="viz-detail-stat">
                            <span class="viz-detail-label">Actual</span>
                            <span class="viz-detail-value text-diab-primary">6.7<span class="viz-detail-unit">%</span></span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="viz-detail-stat">
                            <span class="viz-detail-label">Previo</span>
                            <span class="viz-detail-value">7.1<span class="viz-detail-unit">%</span></span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="viz-detail-stat">
                            <span class="viz-detail-label">Cambio</span>
                            <span class="viz-detail-value text-diab-success">-0.4<span class="viz-detail-unit">%</span></span>
                        </div>
                    </div>
                </div>
                <div class="viz-history-list">
                    <div class="viz-history-item">
                        <span class="viz-history-date">Ene 2026</span>
                        <div class="flex-grow-1 mx-3">
                            <div class="progress-container" style="height: 8px;">
                                <div class="progress-bar-custom" style="width: 67%; background: linear-gradient(90deg, var(--diab-primary), var(--diab-success));"></div>
                            </div>
                        </div>
                        <span class="fw-bold small">6.7%</span>
                    </div>
                    <div class="viz-history-item">
                        <span class="viz-history-date">Oct 2025</span>
                        <div class="flex-grow-1 mx-3">
                            <div class="progress-container" style="height: 8px;">
                                <div class="progress-bar-custom" style="width: 71%; background: linear-gradient(90deg, var(--diab-warning), var(--diab-danger));"></div>
                            </div>
                        </div>
                        <span class="fw-bold small">7.1%</span>
                    </div>
                    <div class="viz-history-item">
                        <span class="viz-history-date">Jul 2025</span>
                        <div class="flex-grow-1 mx-3">
                            <div class="progress-container" style="height: 8px;">
                                <div class="progress-bar-custom" style="width: 74%; background: linear-gradient(90deg, var(--diab-warning), var(--diab-danger));"></div>
                            </div>
                        </div>
                        <span class="fw-bold small">7.4%</span>
                    </div>
                    <div class="viz-history-item">
                        <span class="viz-history-date">Abr 2025</span>
                        <div class="flex-grow-1 mx-3">
                            <div class="progress-container" style="height: 8px;">
                                <div class="progress-bar-custom" style="width: 78%; background: linear-gradient(90deg, var(--diab-danger), #d33);"></div>
                            </div>
                        </div>
                        <span class="fw-bold small">7.8%</span>
                    </div>
                </div>
                <p class="extra-small text-muted mt-3 mb-0"><i class="fa-solid fa-circle-info me-1"></i>Meta: &lt; 7.0% · Última medición: 23 Ene 2026</p>
            </div>
        </div>

        {{-- Tiempo en Rango --}}
        <div class="col-12 col-lg-6">
            <div class="diab-card p-4 h-100 animate-fade-in" style="animation-delay: 0.3s;">
                <h5 class="fw-bold mb-3"><i class="fa-solid fa-bullseye text-diab-success me-2"></i>Tiempo en Rango — Distribución</h5>
                <div class="row align-items-center">
                    <div class="col-5 text-center">
                        <div class="viz-donut-wrapper viz-donut-lg mx-auto">
                            <div class="viz-donut"></div>
                            <div class="viz-donut-label">70%</div>
                        </div>
                    </div>
                    <div class="col-7">
                        <div class="viz-range-breakdown">
                            <div class="viz-range-row">
                                <div class="viz-range-color" style="background: var(--diab-danger);"></div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between"><span class="small fw-semibold">Muy Alto (&gt;250)</span><span class="small fw-bold text-diab-danger">5%</span></div>
                                    <div class="progress-container mt-1" style="height: 4px;"><div class="progress-bar-custom" style="width: 5%; background: var(--diab-danger);"></div></div>
                                </div>
                            </div>
                            <div class="viz-range-row">
                                <div class="viz-range-color" style="background: var(--diab-warning);"></div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between"><span class="small fw-semibold">Alto (181-250)</span><span class="small fw-bold text-diab-warning">10%</span></div>
                                    <div class="progress-container mt-1" style="height: 4px;"><div class="progress-bar-custom" style="width: 10%; background: var(--diab-warning);"></div></div>
                                </div>
                            </div>
                            <div class="viz-range-row">
                                <div class="viz-range-color" style="background: var(--diab-primary);"></div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between"><span class="small fw-semibold">En Rango (70-180)</span><span class="small fw-bold text-diab-primary">70%</span></div>
                                    <div class="progress-container mt-1" style="height: 4px;"><div class="progress-bar-custom" style="width: 70%; background: var(--diab-primary);"></div></div>
                                </div>
                            </div>
                            <div class="viz-range-row">
                                <div class="viz-range-color" style="background: var(--diab-info);"></div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between"><span class="small fw-semibold">Bajo (54-69)</span><span class="small fw-bold text-diab-info">10%</span></div>
                                    <div class="progress-container mt-1" style="height: 4px;"><div class="progress-bar-custom" style="width: 10%; background: var(--diab-info);"></div></div>
                                </div>
                            </div>
                            <div class="viz-range-row">
                                <div class="viz-range-color" style="background: #94A3B8;"></div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between"><span class="small fw-semibold">Muy Bajo (&lt;54)</span><span class="small fw-bold">5%</span></div>
                                    <div class="progress-container mt-1" style="height: 4px;"><div class="progress-bar-custom" style="width: 5%; background: #94A3B8;"></div></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="extra-small text-muted mt-3 mb-0"><i class="fa-solid fa-circle-info me-1"></i>Meta: &gt; 70% en rango · Últimos 14 días</p>
            </div>
        </div>
    </div>

    {{-- ============================================================
         SECCIÓN 3: HISTORIAL DE SIGNOS VITALES (de signos.html)
         ============================================================ --}}
    <div class="diab-card p-4 mb-4 animate-fade-in" style="animation-delay: 0.4s;">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
            <h5 class="fw-bold mb-1"><i class="fa-solid fa-heart-pulse text-diab-danger me-2"></i>Historial de Signos Vitales</h5>
            <a href="{{ route('tracking.vital.create') }}" class="btn btn-diab-primary btn-sm mt-2 mt-md-0">
                <i class="fa-solid fa-plus me-1"></i> Nuevo Registro
            </a>
        </div>
        <div class="table-responsive">
            <table class="custom-table w-100">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Momento</th>
                        <th>Glucosa</th>
                        <th>Presión</th>
                        <th>Frecuencia</th>
                        <th>A1c</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="fw-semibold small">27 Ene, 08:42</td>
                        <td><span class="badge bg-diab-primary-light text-diab-primary">Ayunas</span></td>
                        <td class="fw-bold">98 <span class="text-muted extra-small">mg/DL</span></td>
                        <td>120/80 <span class="text-muted extra-small">mmHg</span></td>
                        <td>75 <span class="text-muted extra-small">bpm</span></td>
                        <td>6.7%</td>
                        <td><span class="badge bg-diab-success-light text-diab-success">Normal</span></td>
                    </tr>
                    <tr>
                        <td class="fw-semibold small">26 Ene, 13:20</td>
                        <td><span class="badge bg-diab-warning-light text-diab-warning">Después</span></td>
                        <td class="fw-bold">145 <span class="text-muted extra-small">mg/DL</span></td>
                        <td>125/82 <span class="text-muted extra-small">mmHg</span></td>
                        <td>82 <span class="text-muted extra-small">bpm</span></td>
                        <td>—</td>
                        <td><span class="badge bg-diab-warning-light text-diab-warning">Elevado</span></td>
                    </tr>
                    <tr>
                        <td class="fw-semibold small">26 Ene, 07:15</td>
                        <td><span class="badge bg-diab-primary-light text-diab-primary">Ayunas</span></td>
                        <td class="fw-bold">105 <span class="text-muted extra-small">mg/DL</span></td>
                        <td>118/78 <span class="text-muted extra-small">mmHg</span></td>
                        <td>70 <span class="text-muted extra-small">bpm</span></td>
                        <td>—</td>
                        <td><span class="badge bg-diab-success-light text-diab-success">Normal</span></td>
                    </tr>
                    <tr>
                        <td class="fw-semibold small">25 Ene, 20:00</td>
                        <td><span class="badge bg-diab-info-light text-diab-info">Al Dormir</span></td>
                        <td class="fw-bold">132 <span class="text-muted extra-small">mg/DL</span></td>
                        <td>122/80 <span class="text-muted extra-small">mmHg</span></td>
                        <td>68 <span class="text-muted extra-small">bpm</span></td>
                        <td>—</td>
                        <td><span class="badge bg-diab-success-light text-diab-success">Normal</span></td>
                    </tr>
                    <tr>
                        <td class="fw-semibold small">25 Ene, 12:45</td>
                        <td><span class="badge bg-diab-warning-light text-diab-warning">Después</span></td>
                        <td class="fw-bold">189 <span class="text-muted extra-small">mg/DL</span></td>
                        <td>130/85 <span class="text-muted extra-small">mmHg</span></td>
                        <td>88 <span class="text-muted extra-small">bpm</span></td>
                        <td>—</td>
                        <td><span class="badge bg-diab-danger-light text-diab-danger">Alto</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- ============================================================
         SECCIÓN 4: SÍNTOMAS (de sintomas.html)
         ============================================================ --}}
    <div class="diab-card p-4 mb-4 animate-fade-in" style="animation-delay: 0.45s;">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
            <div>
                <h5 class="fw-bold mb-1"><i class="fa-solid fa-stethoscope text-diab-warning me-2"></i>Síntomas Reportados</h5>
                <p class="text-muted extra-small mb-0">Frecuencia de síntomas en los últimos 7 días</p>
            </div>
        </div>

        <div class="row g-4">
            {{-- Síntomas Físicos --}}
            <div class="col-12 col-md-6 col-lg-3">
                <div class="viz-symptom-group">
                    <h6 class="viz-symptom-title"><i class="fa-solid fa-hand-dots me-1"></i> Físicos</h6>
                    <div class="viz-symptom-item">
                        <span class="small">Sudoración fría</span>
                        <div class="d-flex align-items-center gap-2 ms-auto">
                            <div class="progress-container flex-grow-1" style="height: 5px; width: 50px;">
                                <div class="progress-bar-custom" style="width: 60%; background: var(--diab-warning);"></div>
                            </div>
                            <span class="extra-small fw-bold">3x</span>
                        </div>
                    </div>
                    <div class="viz-symptom-item">
                        <span class="small">Temblores</span>
                        <div class="d-flex align-items-center gap-2 ms-auto">
                            <div class="progress-container flex-grow-1" style="height: 5px; width: 50px;">
                                <div class="progress-bar-custom" style="width: 40%; background: var(--diab-info);"></div>
                            </div>
                            <span class="extra-small fw-bold">2x</span>
                        </div>
                    </div>
                    <div class="viz-symptom-item">
                        <span class="small">Hambre repentina</span>
                        <div class="d-flex align-items-center gap-2 ms-auto">
                            <div class="progress-container flex-grow-1" style="height: 5px; width: 50px;">
                                <div class="progress-bar-custom" style="width: 80%; background: var(--diab-danger);"></div>
                            </div>
                            <span class="extra-small fw-bold">4x</span>
                        </div>
                    </div>
                    <div class="viz-symptom-item">
                        <span class="small">Palpitaciones</span>
                        <div class="d-flex align-items-center gap-2 ms-auto">
                            <div class="progress-container flex-grow-1" style="height: 5px; width: 50px;">
                                <div class="progress-bar-custom" style="width: 20%; background: var(--diab-primary);"></div>
                            </div>
                            <span class="extra-small fw-bold">1x</span>
                        </div>
                    </div>
                    <div class="viz-symptom-item viz-symptom-zero">
                        <span class="small">Ansiedad</span>
                        <span class="extra-small fw-bold text-muted">0x</span>
                    </div>
                </div>
            </div>

            {{-- Síntomas Nocturnos --}}
            <div class="col-12 col-md-6 col-lg-3">
                <div class="viz-symptom-group">
                    <h6 class="viz-symptom-title"><i class="fa-solid fa-moon me-1"></i> Nocturnos</h6>
                    <div class="viz-symptom-item">
                        <span class="small">Pesadillas</span>
                        <div class="d-flex align-items-center gap-2 ms-auto">
                            <div class="progress-container flex-grow-1" style="height: 5px; width: 50px;">
                                <div class="progress-bar-custom" style="width: 20%; background: var(--diab-info);"></div>
                            </div>
                            <span class="extra-small fw-bold">1x</span>
                        </div>
                    </div>
                    <div class="viz-symptom-item">
                        <span class="small">Sudoración Nocturna</span>
                        <div class="d-flex align-items-center gap-2 ms-auto">
                            <div class="progress-container flex-grow-1" style="height: 5px; width: 50px;">
                                <div class="progress-bar-custom" style="width: 40%; background: var(--diab-warning);"></div>
                            </div>
                            <span class="extra-small fw-bold">2x</span>
                        </div>
                    </div>
                    <div class="viz-symptom-item viz-symptom-zero">
                        <span class="small">Dolor de cabeza</span>
                        <span class="extra-small fw-bold text-muted">0x</span>
                    </div>
                    <div class="viz-symptom-item viz-symptom-zero">
                        <span class="small">Cansancio al despertar</span>
                        <span class="extra-small fw-bold text-muted">0x</span>
                    </div>
                </div>
            </div>

            {{-- Síntomas Neurológicos --}}
            <div class="col-12 col-md-6 col-lg-3">
                <div class="viz-symptom-group">
                    <h6 class="viz-symptom-title"><i class="fa-solid fa-brain me-1"></i> Neurológicos</h6>
                    <div class="viz-symptom-item">
                        <span class="small">Visión borrosa</span>
                        <div class="d-flex align-items-center gap-2 ms-auto">
                            <div class="progress-container flex-grow-1" style="height: 5px; width: 50px;">
                                <div class="progress-bar-custom" style="width: 20%; background: var(--diab-warning);"></div>
                            </div>
                            <span class="extra-small fw-bold">1x</span>
                        </div>
                    </div>
                    <div class="viz-symptom-item">
                        <span class="small">Mareo</span>
                        <div class="d-flex align-items-center gap-2 ms-auto">
                            <div class="progress-container flex-grow-1" style="height: 5px; width: 50px;">
                                <div class="progress-bar-custom" style="width: 40%; background: var(--diab-warning);"></div>
                            </div>
                            <span class="extra-small fw-bold">2x</span>
                        </div>
                    </div>
                    <div class="viz-symptom-item viz-symptom-zero"><span class="small">Confusión</span><span class="extra-small fw-bold text-muted">0x</span></div>
                    <div class="viz-symptom-item viz-symptom-zero"><span class="small">Dificultad hablar</span><span class="extra-small fw-bold text-muted">0x</span></div>
                    <div class="viz-symptom-item viz-symptom-zero"><span class="small">Debilidad</span><span class="extra-small fw-bold text-muted">0x</span></div>
                </div>
            </div>

            {{-- Síntomas Atípicos --}}
            <div class="col-12 col-md-6 col-lg-3">
                <div class="viz-symptom-group">
                    <h6 class="viz-symptom-title"><i class="fa-solid fa-triangle-exclamation me-1"></i> Atípicos</h6>
                    <div class="viz-symptom-item">
                        <span class="small">Cambios de Humor</span>
                        <div class="d-flex align-items-center gap-2 ms-auto">
                            <div class="progress-container flex-grow-1" style="height: 5px; width: 50px;">
                                <div class="progress-bar-custom" style="width: 60%; background: var(--diab-warning);"></div>
                            </div>
                            <span class="extra-small fw-bold">3x</span>
                        </div>
                    </div>
                    <div class="viz-symptom-item viz-symptom-zero"><span class="small">Náuseas</span><span class="extra-small fw-bold text-muted">0x</span></div>
                    <div class="viz-symptom-item viz-symptom-zero"><span class="small">Hormigueo</span><span class="extra-small fw-bold text-muted">0x</span></div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================
         SECCIÓN 5: NUTRICIÓN DETALLADA (de nutricion.html)
         ============================================================ --}}
    <div class="diab-card p-4 mb-4 animate-fade-in" style="animation-delay: 0.5s;">
        <h5 class="fw-bold mb-3"><i class="fa-solid fa-utensils text-diab-warning me-2"></i>Nutrición — Registro Detallado</h5>

        <div class="row g-4">
            {{-- Carbohidratos Semanales --}}
            <div class="col-12 col-lg-5">
                <h6 class="fw-bold small text-diab-text-secondary text-uppercase mb-3">Carbohidratos por Día</h6>
                <div class="viz-nutrition-grid">
                    <div class="viz-nutrition-day">
                        <span class="viz-day-label">Lun</span>
                        <div class="viz-day-bar-wrapper"><div class="viz-day-bar" style="height: 70%;"></div></div>
                        <span class="viz-day-value">140g</span>
                    </div>
                    <div class="viz-nutrition-day">
                        <span class="viz-day-label">Mar</span>
                        <div class="viz-day-bar-wrapper"><div class="viz-day-bar" style="height: 85%;"></div></div>
                        <span class="viz-day-value">170g</span>
                    </div>
                    <div class="viz-nutrition-day">
                        <span class="viz-day-label">Mié</span>
                        <div class="viz-day-bar-wrapper"><div class="viz-day-bar" style="height: 60%;"></div></div>
                        <span class="viz-day-value">120g</span>
                    </div>
                    <div class="viz-nutrition-day">
                        <span class="viz-day-label">Jue</span>
                        <div class="viz-day-bar-wrapper"><div class="viz-day-bar" style="height: 95%;"></div></div>
                        <span class="viz-day-value">190g</span>
                    </div>
                    <div class="viz-nutrition-day">
                        <span class="viz-day-label">Vie</span>
                        <div class="viz-day-bar-wrapper"><div class="viz-day-bar viz-day-bar-over" style="height: 100%;"></div></div>
                        <span class="viz-day-value text-diab-danger">210g</span>
                    </div>
                    <div class="viz-nutrition-day">
                        <span class="viz-day-label">Sáb</span>
                        <div class="viz-day-bar-wrapper"><div class="viz-day-bar" style="height: 75%;"></div></div>
                        <span class="viz-day-value">150g</span>
                    </div>
                    <div class="viz-nutrition-day viz-day-active">
                        <span class="viz-day-label">Dom</span>
                        <div class="viz-day-bar-wrapper"><div class="viz-day-bar" style="height: 90%;"></div></div>
                        <span class="viz-day-value fw-bold">180g</span>
                    </div>
                </div>
                <div class="d-flex justify-content-between mt-2 px-1">
                    <span class="extra-small text-muted">Promedio: 166g/día</span>
                    <span class="extra-small text-muted">Meta: 200g/día</span>
                </div>
            </div>

            {{-- Tipo de Comida + Alimentos + Impacto --}}
            <div class="col-12 col-lg-7">
                <div class="row g-3">
                    {{-- Distribución por Tipo de Comida --}}
                    <div class="col-12 col-md-6">
                        <h6 class="fw-bold small text-diab-text-secondary text-uppercase mb-3">Por Tipo de Comida</h6>
                        <div class="d-flex flex-column gap-2">
                            <div class="viz-food-row">
                                <span class="small fw-semibold">🌅 Desayuno</span>
                                <div class="flex-grow-1 mx-2">
                                    <div class="progress-container" style="height: 6px;">
                                        <div class="progress-bar-custom" style="width: 30%; background: var(--diab-primary);"></div>
                                    </div>
                                </div>
                                <span class="extra-small fw-bold">45g</span>
                            </div>
                            <div class="viz-food-row">
                                <span class="small fw-semibold">☀️ Almuerzo</span>
                                <div class="flex-grow-1 mx-2">
                                    <div class="progress-container" style="height: 6px;">
                                        <div class="progress-bar-custom" style="width: 45%; background: var(--diab-warning);"></div>
                                    </div>
                                </div>
                                <span class="extra-small fw-bold">65g</span>
                            </div>
                            <div class="viz-food-row">
                                <span class="small fw-semibold">🌙 Cena</span>
                                <div class="flex-grow-1 mx-2">
                                    <div class="progress-container" style="height: 6px;">
                                        <div class="progress-bar-custom" style="width: 35%; background: var(--diab-info);"></div>
                                    </div>
                                </div>
                                <span class="extra-small fw-bold">50g</span>
                            </div>
                            <div class="viz-food-row">
                                <span class="small fw-semibold">🍎 Snack</span>
                                <div class="flex-grow-1 mx-2">
                                    <div class="progress-container" style="height: 6px;">
                                        <div class="progress-bar-custom" style="width: 15%; background: var(--diab-accent);"></div>
                                    </div>
                                </div>
                                <span class="extra-small fw-bold">20g</span>
                            </div>
                        </div>
                    </div>

                    {{-- Alimentos Más Frecuentes --}}
                    <div class="col-12 col-md-6">
                        <h6 class="fw-bold small text-diab-text-secondary text-uppercase mb-3">Alimentos Frecuentes</h6>
                        <div class="d-flex flex-column gap-2">
                            <div class="viz-food-tag"><i class="fa-solid fa-bread-slice text-diab-warning me-2"></i> Pan/Cereales <span class="badge bg-diab-warning-light text-diab-warning ms-auto">12x</span></div>
                            <div class="viz-food-tag"><i class="fa-solid fa-drumstick-bite text-diab-danger me-2"></i> Proteínas <span class="badge bg-diab-danger-light text-diab-danger ms-auto">10x</span></div>
                            <div class="viz-food-tag"><i class="fa-solid fa-carrot text-diab-success me-2"></i> Vegetales <span class="badge bg-diab-success-light text-diab-success ms-auto">8x</span></div>
                            <div class="viz-food-tag"><i class="fa-solid fa-apple-whole text-diab-danger me-2"></i> Fruta <span class="badge bg-diab-danger-light text-diab-danger ms-auto">6x</span></div>
                            <div class="viz-food-tag"><i class="fa-solid fa-cheese text-diab-info me-2"></i> Lácteos <span class="badge bg-diab-info-light text-diab-info ms-auto">5x</span></div>
                            <div class="viz-food-tag"><i class="fa-solid fa-bottle-droplet text-muted me-2"></i> Grasas/Aceites <span class="badge bg-diab-primary-light text-diab-primary ms-auto">3x</span></div>
                        </div>
                    </div>

                    {{-- Impacto Glucémico --}}
                    <div class="col-12 col-md-6">
                        <h6 class="fw-bold small text-diab-text-secondary text-uppercase mb-3">Impacto Glucémico</h6>
                        <div class="d-flex flex-column gap-2">
                            <div class="viz-impact-row">
                                <div class="viz-impact-dot" style="background: var(--diab-success);"></div>
                                <span class="small">Bajo</span>
                                <span class="small fw-bold ms-auto">4 comidas</span>
                            </div>
                            <div class="viz-impact-row">
                                <div class="viz-impact-dot" style="background: var(--diab-primary);"></div>
                                <span class="small">Medio</span>
                                <span class="small fw-bold ms-auto">8 comidas</span>
                            </div>
                            <div class="viz-impact-row">
                                <div class="viz-impact-dot" style="background: var(--diab-warning);"></div>
                                <span class="small">Alto</span>
                                <span class="small fw-bold ms-auto">3 comidas</span>
                            </div>
                            <div class="viz-impact-row">
                                <div class="viz-impact-dot" style="background: var(--diab-danger);"></div>
                                <span class="small">Muy alto</span>
                                <span class="small fw-bold ms-auto">1 comida</span>
                            </div>
                        </div>
                    </div>

                    {{-- Medicación Pre-comida --}}
                    <div class="col-12 col-md-6">
                        <h6 class="fw-bold small text-diab-text-secondary text-uppercase mb-3">Medicación Pre-comida</h6>
                        <div class="d-flex flex-column gap-2">
                            <div class="viz-med-row">
                                <i class="fa-solid fa-syringe text-diab-primary me-2"></i>
                                <div>
                                    <span class="small fw-semibold d-block">Insulina Rápida</span>
                                    <span class="extra-small text-muted">Avg: 4 unidades · 8 veces/semana</span>
                                </div>
                            </div>
                            <div class="viz-med-row">
                                <i class="fa-solid fa-pills text-diab-warning me-2"></i>
                                <div>
                                    <span class="small fw-semibold d-block">Medicamento Oral</span>
                                    <span class="extra-small text-muted">1 pastilla · 7 veces/semana</span>
                                </div>
                            </div>
                            <div class="viz-med-row">
                                <i class="fa-solid fa-ban text-muted me-2"></i>
                                <div>
                                    <span class="small fw-semibold d-block">Sin Medicación</span>
                                    <span class="extra-small text-muted">6 comidas sin medicación</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================
         SECCIÓN 6: MOVIMIENTO DETALLADO (de movimiento.html)
         ============================================================ --}}
    <div class="diab-card p-4 mb-4 animate-fade-in" style="animation-delay: 0.6s;">
        <h5 class="fw-bold mb-3"><i class="fa-solid fa-person-running text-diab-success me-2"></i>Movimiento — Registro Detallado</h5>

        <div class="row g-4">
            {{-- Resumen Semanal --}}
            <div class="col-12 col-lg-4">
                <h6 class="fw-bold small text-diab-text-secondary text-uppercase mb-3">Resumen Semanal</h6>
                <div class="d-flex flex-column gap-3">
                    <div class="viz-activity-row">
                        <div class="act-icon fire shadow-sm"><i class="fa-solid fa-fire"></i></div>
                        <div class="flex-grow-1 ms-3">
                            <div class="d-flex justify-content-between mb-1">
                                <strong class="small">Calorías</strong>
                                <span class="small fw-bold text-diab-danger">2500 / 800kcal</span>
                            </div>
                            <div class="progress-container" style="height: 8px;">
                                <div class="progress-bar-custom" style="width: 75%; background: var(--diab-danger);"></div>
                            </div>
                        </div>
                    </div>
                    <div class="viz-activity-row">
                        <div class="act-icon move shadow-sm"><i class="fa-solid fa-bolt"></i></div>
                        <div class="flex-grow-1 ms-3">
                            <div class="d-flex justify-content-between mb-1">
                                <strong class="small">Minutos Activos</strong>
                                <span class="small fw-bold text-diab-warning">45 / 60 min</span>
                            </div>
                            <div class="progress-container" style="height: 8px;">
                                <div class="progress-bar-custom" style="width: 75%; background: var(--diab-warning);"></div>
                            </div>
                        </div>
                    </div>
                    <div class="viz-activity-row">
                        <div class="act-icon feet shadow-sm"><i class="fa-solid fa-shoe-prints"></i></div>
                        <div class="flex-grow-1 ms-3">
                            <div class="d-flex justify-content-between mb-1">
                                <strong class="small">Pasos</strong>
                                <span class="small fw-bold text-diab-primary">6987 / 8000</span>
                            </div>
                            <div class="progress-container" style="height: 8px;">
                                <div class="progress-bar-custom" style="width: 87%; background: var(--diab-primary);"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tipo de Actividad --}}
            <div class="col-12 col-lg-4">
                <h6 class="fw-bold small text-diab-text-secondary text-uppercase mb-3">Tipos de Actividad</h6>
                <div class="d-flex flex-column gap-2">
                    <div class="viz-activity-type">
                        <div class="viz-activity-type-icon">
                            <img src="{{ asset('img/medios/iconos/directions_walk.png') }}" alt="Aeróbico">
                        </div>
                        <div class="flex-grow-1">
                            <span class="small fw-bold d-block">Aeróbico/Cardio</span>
                            <span class="extra-small text-muted">Caminar, correr, nadar</span>
                        </div>
                        <div class="text-end">
                            <span class="small fw-bold d-block text-diab-primary">4x</span>
                            <span class="extra-small text-muted">120 min</span>
                        </div>
                    </div>
                    <div class="viz-activity-type">
                        <div class="viz-activity-type-icon">
                            <img src="{{ asset('img/medios/iconos/fitness_center.png') }}" alt="Fuerza">
                        </div>
                        <div class="flex-grow-1">
                            <span class="small fw-bold d-block">Fuerza/Pesas</span>
                            <span class="extra-small text-muted">Máquinas, pesas libres</span>
                        </div>
                        <div class="text-end">
                            <span class="small fw-bold d-block text-diab-warning">2x</span>
                            <span class="extra-small text-muted">60 min</span>
                        </div>
                    </div>
                    <div class="viz-activity-type">
                        <div class="viz-activity-type-icon">
                            <img src="{{ asset('img/medios/iconos/yoga.png') }}" alt="Flexibilidad">
                        </div>
                        <div class="flex-grow-1">
                            <span class="small fw-bold d-block">Flexibilidad</span>
                            <span class="extra-small text-muted">Yoga, estiramientos</span>
                        </div>
                        <div class="text-end">
                            <span class="small fw-bold d-block text-diab-success">1x</span>
                            <span class="extra-small text-muted">30 min</span>
                        </div>
                    </div>
                    <div class="viz-activity-type">
                        <div class="viz-activity-type-icon">
                            <img src="{{ asset('img/medios/iconos/cleaning_services.png') }}" alt="Diaria">
                        </div>
                        <div class="flex-grow-1">
                            <span class="small fw-bold d-block">Vida Diaria</span>
                            <span class="extra-small text-muted">Limpieza, jardinería</span>
                        </div>
                        <div class="text-end">
                            <span class="small fw-bold d-block text-diab-info">3x</span>
                            <span class="extra-small text-muted">90 min</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Intensidad + Duración --}}
            <div class="col-12 col-lg-4">
                <h6 class="fw-bold small text-diab-text-secondary text-uppercase mb-3">Nivel de Intensidad</h6>
                <div class="d-flex flex-column gap-2 mb-4">
                    <div class="viz-intensity-row">
                        <span class="small">Muy ligera</span>
                        <div class="flex-grow-1 mx-2">
                            <div class="progress-container" style="height: 6px;">
                                <div class="progress-bar-custom" style="width: 15%; background: #94A3B8;"></div>
                            </div>
                        </div>
                        <span class="extra-small fw-bold">2 sesiones</span>
                    </div>
                    <div class="viz-intensity-row">
                        <span class="small">Ligera</span>
                        <div class="flex-grow-1 mx-2">
                            <div class="progress-container" style="height: 6px;">
                                <div class="progress-bar-custom" style="width: 30%; background: var(--diab-primary);"></div>
                            </div>
                        </div>
                        <span class="extra-small fw-bold">3 sesiones</span>
                    </div>
                    <div class="viz-intensity-row">
                        <span class="small">Moderada</span>
                        <div class="flex-grow-1 mx-2">
                            <div class="progress-container" style="height: 6px;">
                                <div class="progress-bar-custom" style="width: 45%; background: var(--diab-success);"></div>
                            </div>
                        </div>
                        <span class="extra-small fw-bold">4 sesiones</span>
                    </div>
                    <div class="viz-intensity-row">
                        <span class="small">Intensa</span>
                        <div class="flex-grow-1 mx-2">
                            <div class="progress-container" style="height: 6px;">
                                <div class="progress-bar-custom" style="width: 20%; background: var(--diab-warning);"></div>
                            </div>
                        </div>
                        <span class="extra-small fw-bold">1 sesión</span>
                    </div>
                    <div class="viz-intensity-row">
                        <span class="small">Máxima</span>
                        <div class="flex-grow-1 mx-2">
                            <div class="progress-container" style="height: 6px;">
                                <div class="progress-bar-custom" style="width: 0%; background: var(--diab-danger);"></div>
                            </div>
                        </div>
                        <span class="extra-small fw-bold text-muted">0 sesiones</span>
                    </div>
                </div>

                <h6 class="fw-bold small text-diab-text-secondary text-uppercase mb-3">Duración Total</h6>
                <div class="viz-detail-stat">
                    <span class="viz-detail-label">Esta Semana</span>
                    <span class="viz-detail-value text-diab-success">300</span>
                    <span class="viz-detail-unit">minutos (5h)</span>
                    <span class="badge bg-diab-success-light text-diab-success mt-1">Meta: 150 min ✓</span>
                </div>
            </div>
        </div>
    </div>

</main>
@endsection
