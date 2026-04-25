@extends('layouts.app')

@section('title', 'DiabTrack - Resumen Integral')

@section('styles')
    @vite('resources/css/visualizacion.css')
    <style>
        .stat-card {
            border: 1px solid rgba(255, 255, 255, 0.45);
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.8) !important;
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border-radius: 20px !important;
            overflow: hidden;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.05);
        }
        .diab-card {
            background: rgba(255, 255, 255, 0.75) !important;
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.4) !important;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.05);
            border-radius: 20px !important;
        }
        .stat-card.border-4 {
            border-width: 1px !important;
            border-left-width: 6px !important;
        }

        .info-icon {
            cursor: help;
            font-size: 0.85rem;
            transition: all 0.2s ease;
        }
        .info-icon:hover {
            color: var(--diab-primary) !important;
            opacity: 1 !important;
        }
        .nav-tabs-custom {
            border-bottom: 2px solid #f0f0f0;
            gap: 2rem;
            margin-bottom: 0 !important;
        }
        .nav-tabs-custom .nav-link {
            border: none;
            color: var(--diab-text-secondary);
            font-weight: 600;
            padding: 1rem 0;
            position: relative;
            background: transparent;
        }
        .nav-tabs-custom .nav-link.active {
            color: var(--diab-primary);
            background: transparent !important;
            border: none;
        }
        .nav-tabs-custom .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            height: 2px;
            background: var(--diab-primary);
        }
        .history-table th {
            background: rgba(0, 0, 0, 0.03);
            color: var(--diab-text-secondary);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border: none;
            padding: 1rem;
        }
        .history-table td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid rgba(0, 0, 0, 0.03);
            background: transparent !important;
        }
        .history-table {
            background: transparent !important;
            margin-bottom: 0;
        }
        .badge-glucose {
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 700;
        }
        .chart-container-sm {
            position: relative;
            height: 220px;
            width: 100%;
        }
        .insight-card {
            background: rgba(255, 255, 255, 0.25);
            border-radius: 16px;
            padding: 1.25rem;
            border: 1px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(5px);
        }
        @media (max-width: 768px) {
            .nav-tabs-custom {
                gap: 0.5rem;
                display: flex;
                flex-wrap: nowrap;
                overflow-x: auto;
                padding-bottom: 0;
            }
            .nav-tabs-custom .nav-link {
                white-space: nowrap;
                font-size: 0.85rem;
            }
            .stat-card {
                padding: 1.25rem !important;
            }
        }
    </style>
@endsection

@section('content')
<main class="container-fluid py-4 px-md-5 mt-2">
        
        <!-- Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5 gap-3">
            <div>
                <h2 class="fw-extrabold mb-1 fs-3">Visualización <span class="text-diab-primary">Integral</span></h2>
                <p class="text-muted small mb-0">Análisis detallado de todos tus registros históricos</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-secondary rounded-pill px-3 px-md-4 btn-sm">
                    <i class="fa-solid fa-calendar-day me-2"></i> <span class="d-none d-sm-inline">Historial</span> Completo
                </button>
                <button class="btn btn-diab-primary rounded-pill px-3 px-md-4 btn-sm shadow-sm">
                    <i class="fa-solid fa-file-pdf me-2"></i> Reporte Médico
                </button>
            </div>
        </div>

        <!-- Metric Cards Row 1: Glucose & Ranges -->
        <div class="row g-3 g-md-4 mb-4">
            <div class="col-6 col-md-3">
                <div class="stat-card p-4 h-100 shadow-sm border-start border-4 border-primary">
                    <div class="extra-small fw-bold text-muted text-uppercase mb-2 letter-spacing-1 d-flex align-items-center justify-content-between">
                        <span>Glucosa Promedio</span>
                        <i class="fa-solid fa-circle-info info-icon opacity-50" data-bs-toggle="tooltip" title="El promedio de tus niveles de azúcar en la sangre en los últimos días."></i>
                    </div>
                    <div class="d-flex align-items-baseline gap-1">
                        <h2 class="fw-extrabold text-dark mb-0">{{ $avgGlucose ?: '--' }}</h2>
                        <span class="text-muted extra-small">mg/DL</span>
                    </div>
                    <div class="mt-3 extra-small {{ $avgGlucose > 140 ? 'text-danger' : 'text-success' }}">
                        <i class="fa-solid {{ $avgGlucose > 140 ? 'fa-arrow-trend-up' : 'fa-check' }} me-1"></i>
                        <span class="d-none d-lg-inline">{{ $avgGlucose > 140 ? 'Sobre el rango' : 'En rango meta' }}</span>
                        <span class="d-inline d-lg-none">{{ $avgGlucose > 140 ? 'Alto' : 'Normal' }}</span>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card p-4 h-100 shadow-sm border-start border-4 border-success">
                    <div class="extra-small fw-bold text-muted text-uppercase mb-2 letter-spacing-1 d-flex align-items-center justify-content-between">
                        <span>Tiempo en Rango</span>
                        <i class="fa-solid fa-circle-info info-icon opacity-50" data-bs-toggle="tooltip" title="Mide cuántas veces tu azúcar salió normal (ni muy alta ni muy baja). Lo ideal es que al menos 7 de cada 10 veces estés en el nivel adecuado."></i>
                    </div>
                    <div class="d-flex align-items-baseline gap-1">
                        <h2 class="fw-extrabold text-dark mb-0">{{ $tiempoEnRango }}%</h2>
                        <span class="text-muted extra-small">Meta: 70% o más</span>
                    </div>
                    <div class="progress mt-3" style="height: 6px; border-radius: 10px;">
                        <div class="progress-bar bg-success" style="width: {{ $tiempoEnRango }}%"></div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card p-4 h-100 shadow-sm border-start border-4 border-primary">
                    <div class="extra-small fw-bold text-muted text-uppercase mb-2 letter-spacing-1 d-flex align-items-center justify-content-between">
                        <span>HbA1c Estimada</span>
                        <i class="fa-solid fa-circle-info info-icon opacity-50" data-bs-toggle="tooltip" title="Un cálculo de cómo ha estado tu azúcar en los últimos 3 meses en promedio."></i>
                    </div>
                    <div class="d-flex align-items-baseline gap-1">
                        <h2 class="fw-extrabold text-dark mb-0">{{ $ultimaHba1c ? number_format($ultimaHba1c->hba1c, 1) : '--' }}</h2>
                        <span class="text-muted extra-small">%</span>
                    </div>
                    <p class="text-muted extra-small mt-3 mb-0">Basado en últimos 90 días</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card p-4 h-100 shadow-sm border-start border-4 border-secondary">
                    <div class="extra-small fw-bold text-muted text-uppercase mb-2 letter-spacing-1 d-flex align-items-center justify-content-between">
                        <span>Peso Actual</span>
                        <i class="fa-solid fa-circle-info info-icon opacity-50" data-bs-toggle="tooltip" title="Tu último peso registrado."></i>
                    </div>
                    <div class="d-flex align-items-baseline gap-1">
                        <h2 class="fw-extrabold text-dark mb-0">{{ $totalWeight }}</h2>
                        <span class="text-muted extra-small">kg</span>
                    </div>
                    <div class="mt-3 extra-small text-muted">
                        <i class="fa-solid fa-scale-balanced me-1"></i> {{ $weightCount }} mediciones
                    </div>
                </div>
            </div>
        </div>

        <!-- Metric Cards Row 2: Vitals & Activity -->
        <div class="row g-3 g-md-4 mb-5">
            <div class="col-6 col-md-3">
                <div class="stat-card p-4 h-100 shadow-sm border-start border-4 border-info">
                    <div class="extra-small fw-bold text-muted text-uppercase mb-2 letter-spacing-1 d-flex align-items-center justify-content-between">
                        <span>Presión Media</span>
                        <i class="fa-solid fa-circle-info info-icon opacity-50" data-bs-toggle="tooltip" title="El promedio de tu presión arterial (presión de la sangre)."></i>
                    </div>
                    <div class="d-flex align-items-baseline gap-1">
                        <h2 class="fw-extrabold text-dark mb-0">{{ $avgSystolic }}/{{ $avgDiastolic }}</h2>
                        <span class="text-muted extra-small">mmHg</span>
                    </div>
                    <p class="text-muted extra-small mt-3 mb-0">Estado: <span class="text-info fw-bold">Estable</span></p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card p-4 h-100 shadow-sm border-start border-4 border-info">
                    <div class="extra-small fw-bold text-muted text-uppercase mb-2 letter-spacing-1 d-flex align-items-center justify-content-between">
                        <span>Frecuencia Cardiaca</span>
                        <i class="fa-solid fa-circle-info info-icon opacity-50" data-bs-toggle="tooltip" title="El promedio de los latidos de tu corazón por minuto."></i>
                    </div>
                    <div class="d-flex align-items-baseline gap-1">
                        <h2 class="fw-extrabold text-dark mb-0">{{ $avgHeartRate }}</h2>
                        <span class="text-muted extra-small">bpm (Prom)</span>
                    </div>
                    <div class="mt-3 extra-small text-muted">
                        <i class="fa-solid fa-heart-pulse text-danger me-1"></i> Ritmo regular
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card p-4 h-100 shadow-sm border-start border-4 border-warning">
                    <div class="extra-small fw-bold text-muted text-uppercase mb-2 letter-spacing-1 d-flex align-items-center justify-content-between">
                        <span>Carbs Totales</span>
                        <i class="fa-solid fa-circle-info info-icon opacity-50" data-bs-toggle="tooltip" title="La cantidad total de carbohidratos (harinas, azúcares) que has comido hoy."></i>
                    </div>
                    <div class="d-flex align-items-baseline gap-1">
                        <h2 class="fw-extrabold text-dark mb-0">{{ number_format($nutritionHistory->sum('carbs_grams')) }}</h2>
                        <span class="text-muted extra-small">g registrados</span>
                    </div>
                    <p class="text-muted extra-small mt-3 mb-0">{{ $medicationCount }} tomas de medicación</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card p-4 h-100 shadow-sm border-start border-4 border-success">
                    <div class="extra-small fw-bold text-muted text-uppercase mb-2 letter-spacing-1 d-flex align-items-center justify-content-between">
                        <span>Actividad Total</span>
                        <i class="fa-solid fa-circle-info info-icon opacity-50" data-bs-toggle="tooltip" title="El tiempo total que te has movido o hecho ejercicio hoy."></i>
                    </div>
                    <div class="d-flex align-items-baseline gap-1">
                        <h2 class="fw-extrabold text-dark mb-0">{{ round($totalActivityMinutes / 60, 1) }}</h2>
                        <span class="text-muted extra-small">horas totales</span>
                    </div>
                    <p class="text-muted extra-small mt-3 mb-0">{{ $symptomsCount }} síntomas reportados</p>
                </div>
            </div>
        </div>

        <!-- Main Charts Row -->
        <div class="row g-4 mb-5">
            <div class="col-12 col-lg-8">
                <div class="diab-card p-4 p-md-5 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0">Dinámica de Glucosa (30 días)</h5>
                        <div class="d-flex align-items-center gap-3">
                            <div class="badge bg-diab-primary-light text-diab-primary rounded-pill px-3 py-2">Tendencia Temporal</div>
                            <i class="fa-solid fa-circle-info info-icon opacity-50" data-bs-toggle="tooltip" title="Este gráfico te muestra de forma fácil si tu azúcar ha subido o bajado en el último mes."></i>
                        </div>
                    </div>
                    <div style="height: 320px;">
                        <canvas id="mainDetailedChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="diab-card p-4 p-md-5 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0">Composición de Dieta</h5>
                        <i class="fa-solid fa-circle-info info-icon opacity-50" data-bs-toggle="tooltip" title="Te muestra visualmente qué tipo de comida estás comiendo más (ej. si comes más proteínas o más carbohidratos)."></i>
                    </div>
                    <div class="chart-container-sm mb-4">
                        <canvas id="dietCompositionChart"></canvas>
                    </div>
                    <div class="mt-4">
                        <div class="insight-card">
                            <h6 class="fw-bold small mb-2"><i class="fa-solid fa-lightbulb text-warning me-2"></i>Observación IA</h6>
                            <p class="extra-small text-muted mb-0">Tus niveles de glucosa tienden a subir un 15% tras comidas altas en cereales. Considera balancear con más fibra.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Secondary Charts Row -->
        <div class="row g-4 mb-5">
            <div class="col-12 col-md-6">
                <div class="diab-card p-4 p-md-5">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0">Frecuencia de Síntomas</h5>
                        <i class="fa-solid fa-circle-info info-icon opacity-50" data-bs-toggle="tooltip" title="Cuáles han sido los síntomas o malestares que más has sentido últimamente."></i>
                    </div>
                    <div class="chart-container-sm">
                        <canvas id="symptomsFrequencyChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="diab-card p-4 p-md-5">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0">Balance de Energía y Sueño</h5>
                        <i class="fa-solid fa-circle-info info-icon opacity-50" data-bs-toggle="tooltip" title="Te ayuda a ver cómo se relaciona lo que duermes y te mueves con tu nivel de energía en el día."></i>
                    </div>
                    <div class="chart-container-sm">
                        <canvas id="energySleepChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed History Tabs -->
        <div class="diab-card shadow-sm border-0">
            <div class="px-4 pt-4 px-md-5 pt-md-5">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0">Explorador de Datos Históricos</h5>
                    <i class="fa-solid fa-circle-info info-icon opacity-50" data-bs-toggle="tooltip" title="Aquí puedes ver una lista completa con todos los datos que has guardado."></i>
                </div>
                <ul class="nav nav-tabs nav-tabs-custom" id="historyTabs" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active" id="vitals-tab" data-bs-toggle="tab" data-bs-target="#vitals" type="button">Signos Vitales</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="nutrition-tab" data-bs-toggle="tab" data-bs-target="#nutrition" type="button">Nutrición</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="activity-tab" data-bs-toggle="tab" data-bs-target="#activity" type="button">Actividad</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="symptoms-tab" data-bs-toggle="tab" data-bs-target="#symptoms" type="button">Síntomas</button>
                    </li>
                </ul>
            </div>
            <hr class="m-0 opacity-10">
            <div class="p-4 p-md-5">
                <div class="tab-content" id="historyTabsContent">
                    <!-- Vitals -->
                    <div class="tab-pane fade show active" id="vitals">
                        <div class="table-responsive">
                            <table class="table history-table">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Glucosa</th>
                                        <th>Presión</th>
                                        <th class="d-none d-md-table-cell">FC</th>
                                        <th>Peso</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($vitalsHistory as $vital)
                                    <tr>
                                        <td class="small fw-semibold">{{ $vital->created_at->format('d M, H:i') }}</td>
                                        <td>
                                            <span class="badge-glucose {{ $vital->glucose_level > 140 ? 'bg-danger-light text-danger' : ($vital->glucose_level < 70 ? 'bg-warning-light text-warning' : 'bg-success-light text-success') }}">
                                                {{ $vital->glucose_level ?? '--' }}
                                            </span>
                                        </td>
                                        <td>{{ $vital->systolic && $vital->diastolic ? $vital->systolic . '/' . $vital->diastolic : '--' }}</td>
                                        <td class="d-none d-md-table-cell">{{ $vital->heart_rate ? $vital->heart_rate . ' bpm' : '--' }}</td>
                                        <td class="fw-bold">{{ $vital->weight ? $vital->weight . ' kg' : '--' }}</td>
                                        <td>
                                            @if($vital->glucose_level)
                                                @if($vital->glucose_level > 140)
                                                    <i class="fa-solid fa-circle-exclamation text-danger"></i>
                                                @elseif($vital->glucose_level < 70)
                                                    <i class="fa-solid fa-droplet-slash text-warning"></i>
                                                @else
                                                    <i class="fa-solid fa-circle-check text-success"></i>
                                                @endif
                                            @else
                                                <span class="text-muted small">N/A</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Nutrition -->
                    <div class="tab-pane fade" id="nutrition">
                        <div class="table-responsive">
                            <table class="table history-table">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Comida</th>
                                        <th>Carbs</th>
                                        <th class="d-none d-md-table-cell">Kcal</th>
                                        <th class="d-none d-md-table-cell">Categorías</th>
                                        <th>Medic.</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($nutritionHistory as $log)
                                    <tr>
                                        <td class="small fw-semibold">{{ \Carbon\Carbon::parse($log->consumed_at)->format('d M, H:i') }}</td>
                                        <td class="text-capitalize small">{{ $log->meal_type }}</td>
                                        <td class="fw-bold">{{ $log->carbs_grams }}g</td>
                                        <td class="text-muted d-none d-md-table-cell">{{ $log->carbs_grams * 4 }}</td>
                                        <td class="d-none d-md-table-cell">
                                            @if($log->food_categories)
                                                @foreach($log->food_categories as $cat)
                                                    <span class="badge bg-light text-dark border extra-small">{{ $cat }}</span>
                                                @endforeach
                                            @endif
                                        </td>
                                        <td>
                                            @if($log->medication_taken)
                                                <span class="text-diab-primary extra-small fw-bold" title="{{ $log->medication_taken }}"><i class="fa-solid fa-pills"></i> Sí</span>
                                            @else
                                                <span class="text-muted extra-small">No</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Activity -->
                    <div class="tab-pane fade" id="activity">
                        <div class="table-responsive">
                            <table class="table history-table">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Tipo</th>
                                        <th>Duración</th>
                                        <th class="d-none d-md-table-cell">Intensidad</th>
                                        <th>Energía</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($activityHistory as $act)
                                    <tr>
                                        <td class="small fw-semibold">{{ $act->created_at->format('d M') }}</td>
                                        <td class="text-capitalize fw-bold small">{{ $act->activity_type }}</td>
                                        <td><span class="badge bg-diab-primary-light text-diab-primary rounded-pill">{{ $act->duration_minutes }} min</span></td>
                                        <td class="text-capitalize d-none d-md-table-cell small">{{ $act->intensity }}</td>
                                        <td>
                                            @php
                                                $energyIcons = [
                                                    'muy_baja' => '<i class="fa-solid fa-battery-empty text-danger"></i>',
                                                    'baja' => '<i class="fa-solid fa-battery-quarter text-warning"></i>',
                                                    'normal' => '<i class="fa-solid fa-battery-half text-info"></i>',
                                                    'alta' => '<i class="fa-solid fa-battery-three-quarters text-success"></i>',
                                                    'muy_alta' => '<i class="fa-solid fa-battery-full text-success"></i>',
                                                ];
                                            @endphp
                                            {!! $energyIcons[$act->energy_level] ?? '<i class="fa-solid fa-battery-half text-info"></i>' !!}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Symptoms -->
                    <div class="tab-pane fade" id="symptoms">
                        <div class="table-responsive">
                            <table class="table history-table">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Síntoma</th>
                                        <th>Categoría</th>
                                        <th>Hora</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($symptomsHistory as $symptom)
                                    <tr>
                                        <td class="small fw-semibold">{{ \Carbon\Carbon::parse($symptom->logged_at)->format('d M') }}</td>
                                        <td class="fw-bold small">{{ $symptom->name }}</td>
                                        <td class="text-capitalize small">{{ $symptom->category }}</td>
                                        <td class="small text-muted">{{ \Carbon\Carbon::parse($symptom->logged_at)->format('H:i') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

</main>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const primaryColor = '#00B4D8';
        const successColor = '#28C76F';
        const dangerColor = '#EA5455';
        const warningColor = '#FF9F43';
        const infoColor = '#00CFE8';

        // Main Glucose Trend Chart (30 days logic simplified for view)
        const mainCtx = document.getElementById('mainDetailedChart');
        if (mainCtx) {
            new Chart(mainCtx, {
                type: 'line',
                data: {
                    labels: @json($glucosaLabels),
                    datasets: [{
                        label: 'Glucosa',
                        data: @json($glucosaData),
                        borderColor: primaryColor,
                        backgroundColor: 'rgba(0, 180, 216, 0.1)',
                        borderWidth: 3,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: { backgroundColor: '#0F172A', padding: 12, cornerRadius: 8 }
                    },
                    scales: {
                        y: { grid: { color: 'rgba(0,0,0,0.03)' }, ticks: { font: { size: 10 } } },
                        x: { grid: { display: false }, ticks: { font: { size: 10 } } }
                    }
                }
            });
        }

        // Diet Composition Pie Chart
        const dietCtx = document.getElementById('dietCompositionChart');
        if (dietCtx) {
            new Chart(dietCtx, {
                type: 'doughnut',
                data: {
                    labels: @json($foodCategoryLabels),
                    datasets: [{
                        data: @json($foodCategoryData),
                        backgroundColor: [primaryColor, successColor, warningColor, dangerColor, infoColor, '#64748B', '#48CAE4', '#90E0EF'],
                        borderWidth: 2,
                        borderColor: '#ffffff',
                        cutout: '65%'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { 
                            display: true, 
                            position: 'bottom',
                            labels: { usePointStyle: true, font: { size: 9 } }
                        }
                    }
                }
            });
        }

        // Symptoms Frequency Bar Chart
        const sympCtx = document.getElementById('symptomsFrequencyChart');
        if (sympCtx) {
            // Processing symptoms data for chart
            const symptoms = @json($symptomsHistory);
            const counts = {};
            symptoms.forEach(s => counts[s.name] = (counts[s.name] || 0) + 1);
            const labels = Object.keys(counts).slice(0, 6);
            const data = Object.values(counts).slice(0, 6);

            new Chart(sympCtx, {
                type: 'bar',
                data: {
                    labels: labels.length ? labels : ['Sin datos'],
                    datasets: [{
                        data: data.length ? data : [0],
                        backgroundColor: 'rgba(234, 84, 85, 0.7)',
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: 'y',
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.03)' } },
                        y: { grid: { display: false }, ticks: { font: { size: 10 } } }
                    }
                }
            });
        }

        // Energy and Sleep (Mocked from Activity Data Trend)
        const energyCtx = document.getElementById('energySleepChart');
        if (energyCtx) {
            new Chart(energyCtx, {
                type: 'radar',
                data: {
                    labels: ['Energía Mañana', 'Energía Tarde', 'Energía Noche', 'Calidad Sueño', 'Fuerza'],
                    datasets: [{
                        label: 'Nivel Actual',
                        data: [8, 6, 7, 8, 7],
                        backgroundColor: 'rgba(0, 207, 232, 0.2)',
                        borderColor: infoColor,
                        pointBackgroundColor: infoColor
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        r: { beginAtZero: true, max: 10, ticks: { display: false } }
                    },
                    plugins: { legend: { display: false } }
                }
            });
        }
    });

    // Inicializar Tooltips de Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
@endsection
