@extends('layouts.app')

@section('title', 'DiabTrack - Resumen Integral')

@section('styles')
    @vite('resources/css/visualizacion.css')
    <style>
        .dashboard-wrapper {
            border: none;
            background: transparent;
        }
        .stat-card {
            border: 1px solid rgba(0,0,0,0.03);
            transition: all 0.3s ease;
            background: white;
            border-radius: 24px;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.06);
        }
        .nav-tabs-custom {
            border-bottom: 2px solid #f0f0f0;
            gap: 2rem;
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
            background: #f8fafc;
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
            border-bottom: 1px solid #f1f5f9;
        }
        .badge-glucose {
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 700;
        }
    </style>
@endsection

@section('content')
<main class="container-fluid px-4 py-4 mt-2">
    <div class="dashboard-wrapper animate-fade-in">
        
        <!-- Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5 gap-3">
            <div>
                <h2 class="fw-extrabold mb-1 fs-3">Visualización <span class="text-diab-primary">Completa</span></h2>
                <p class="text-muted small mb-0">Análisis detallado de todos tus registros históricos</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-secondary rounded-pill px-4 btn-sm">
                    <i class="fa-solid fa-calendar-day me-2"></i> Últimos 30 días
                </button>
                <button class="btn btn-diab-primary rounded-pill px-4 btn-sm shadow-sm">
                    <i class="fa-solid fa-file-pdf me-2"></i> Exportar
                </button>
            </div>
        </div>

        <!-- Metric Cards -->
        <div class="row g-4 mb-5">
            <div class="col-12 col-md-3">
                <div class="stat-card p-4 h-100 shadow-sm">
                    <div class="extra-small fw-bold text-muted text-uppercase mb-2 letter-spacing-1">Glucosa Promedio</div>
                    <div class="d-flex align-items-baseline gap-2">
                        @php $avgGlucose = collect($glucosaData)->filter()->avg(); @endphp
                        <h2 class="fw-extrabold text-dark mb-0">{{ round($avgGlucose) ?: '--' }}</h2>
                        <span class="text-muted small">mg/DL</span>
                    </div>
                    <div class="mt-3 extra-small {{ $avgGlucose > 140 ? 'text-danger' : 'text-success' }}">
                        <i class="fa-solid {{ $avgGlucose > 140 ? 'fa-arrow-trend-up' : 'fa-check' }} me-1"></i>
                        {{ $avgGlucose > 140 ? 'Por encima del rango' : 'Rango saludable' }}
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="stat-card p-4 h-100 shadow-sm">
                    <div class="extra-small fw-bold text-muted text-uppercase mb-2 letter-spacing-1">Tiempo en Rango</div>
                    <div class="d-flex align-items-baseline gap-2">
                        <h2 class="fw-extrabold text-dark mb-0">{{ $tiempoEnRango }}%</h2>
                        <span class="text-muted small">de registros</span>
                    </div>
                    <div class="progress mt-3" style="height: 6px;">
                        <div class="progress-bar bg-success" style="width: {{ $tiempoEnRango }}%"></div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="stat-card p-4 h-100 shadow-sm">
                    <div class="extra-small fw-bold text-muted text-uppercase mb-2 letter-spacing-1">Carbs Totales (Mes)</div>
                    <div class="d-flex align-items-baseline gap-2">
                        <h2 class="fw-extrabold text-dark mb-0">{{ number_format($nutritionHistory->sum('carbs_grams')) }}</h2>
                        <span class="text-muted small">g</span>
                    </div>
                    <div class="mt-3 extra-small text-muted">Basado en {{ $nutritionHistory->count() }} registros</div>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="stat-card p-4 h-100 shadow-sm">
                    <div class="extra-small fw-bold text-muted text-uppercase mb-2 letter-spacing-1">Actividad Total</div>
                    <div class="d-flex align-items-baseline gap-2">
                        <h2 class="fw-extrabold text-dark mb-0">{{ round($activityHistory->sum('duration_minutes') / 60, 1) }}</h2>
                        <span class="text-muted small">horas</span>
                    </div>
                    <div class="mt-3 extra-small text-muted">{{ $activityHistory->count() }} sesiones registradas</div>
                </div>
            </div>
        </div>

        <!-- Main Chart -->
        <div class="diab-card p-4 p-md-5 mb-5 shadow-sm border-0">
            <h5 class="fw-bold mb-4">Análisis de Glucosa (Últimos 7 días)</h5>
            <div style="height: 350px;">
                <canvas id="mainDetailedChart"></canvas>
            </div>
        </div>

        <!-- Detailed History Tabs -->
        <div class="diab-card shadow-sm border-0 p-4 p-md-5">
            <h5 class="fw-bold mb-4">Historial Detallado</h5>
            
            <ul class="nav nav-tabs nav-tabs-custom mb-4" id="historyTabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" id="vitals-tab" data-bs-toggle="tab" data-bs-target="#vitals" type="button">Signos Vitales</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="nutrition-tab" data-bs-toggle="tab" data-bs-target="#nutrition" type="button">Alimentación</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="activity-tab" data-bs-toggle="tab" data-bs-target="#activity" type="button">Actividad Física</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="symptoms-tab" data-bs-toggle="tab" data-bs-target="#symptoms" type="button">Síntomas</button>
                </li>
            </ul>

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
                                    <th>F. Cardíaca</th>
                                    <th>Peso</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($vitalsHistory as $vital)
                                <tr>
                                    <td class="small fw-semibold">{{ $vital->created_at->format('d M, Y H:i') }}</td>
                                    <td>
                                        <span class="badge-glucose {{ $vital->glucose_level > 140 ? 'bg-danger-light text-danger' : ($vital->glucose_level < 70 ? 'bg-warning-light text-warning' : 'bg-success-light text-success') }}">
                                            {{ $vital->glucose_level }} mg/DL
                                        </span>
                                    </td>
                                    <td>{{ $vital->systolic }}/{{ $vital->diastolic }} <small class="text-muted">mmHg</small></td>
                                    <td>{{ $vital->heart_rate }} <small class="text-muted">bpm</small></td>
                                    <td>{{ $vital->weight ?: '--' }} <small class="text-muted">kg</small></td>
                                    <td>
                                        @if($vital->glucose_level > 140)
                                            <i class="fa-solid fa-circle-exclamation text-danger"></i>
                                        @elseif($vital->glucose_level < 70)
                                            <i class="fa-solid fa-droplet-slash text-warning"></i>
                                        @else
                                            <i class="fa-solid fa-circle-check text-success"></i>
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
                                    <th>Carbohidratos</th>
                                    <th>Calorías Est.</th>
                                    <th>Categorías</th>
                                    <th>Medicación</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($nutritionHistory as $log)
                                <tr>
                                    <td class="small fw-semibold">{{ \Carbon\Carbon::parse($log->consumed_at)->format('d M, Y H:i') }}</td>
                                    <td class="text-capitalize">{{ $log->meal_type }}</td>
                                    <td class="fw-bold">{{ $log->carbs_grams }}g</td>
                                    <td class="text-muted">{{ $log->carbs_grams * 4 }} kcal</td>
                                    <td>
                                        @if($log->food_categories)
                                            @foreach($log->food_categories as $cat)
                                                <span class="badge bg-light text-dark border extra-small">{{ $cat }}</span>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>
                                        @if($log->medication_taken)
                                            <span class="text-diab-primary small"><i class="fa-solid fa-pills me-1"></i> {{ $log->medication_taken }} ({{ $log->medication_dose }})</span>
                                        @else
                                            <span class="text-muted small">No registrada</span>
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
                                    <th>Actividad</th>
                                    <th>Duración</th>
                                    <th>Intensidad</th>
                                    <th>Energía</th>
                                    <th>Horario</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($activityHistory as $act)
                                <tr>
                                    <td class="small fw-semibold">{{ $act->created_at->format('d M, Y') }}</td>
                                    <td class="text-capitalize fw-bold">{{ $act->activity_type }}</td>
                                    <td><span class="badge bg-diab-primary-light text-diab-primary rounded-pill">{{ $act->duration_minutes }} min</span></td>
                                    <td class="text-capitalize">{{ $act->intensity }}</td>
                                    <td>
                                        @php
                                            $energyIcons = ['muy_baja' => '😴', 'baja' => '😐', 'normal' => '🙂', 'alta' => '😊', 'muy_alta' => '🤩'];
                                        @endphp
                                        {{ $energyIcons[$act->energy_level] ?? '🙂' }} <small class="text-capitalize">{{ str_replace('_', ' ', $act->energy_level) }}</small>
                                    </td>
                                    <td class="text-muted small">{{ $act->start_time }} - {{ $act->end_time }}</td>
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
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($symptomsHistory as $symptom)
                                <tr>
                                    <td class="small fw-semibold">{{ \Carbon\Carbon::parse($symptom->logged_at)->format('d M, Y H:i') }}</td>
                                    <td class="fw-bold">{{ $symptom->name }}</td>
                                    <td class="text-capitalize">{{ $symptom->category }}</td>
                                    <td><span class="badge bg-warning-light text-warning rounded-pill">Registrado</span></td>
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
        const ctx = document.getElementById('mainDetailedChart');
        if (!ctx) return;

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($glucosaLabels),
                datasets: [{
                    label: 'Nivel de Glucosa',
                    data: @json($glucosaData),
                    borderColor: '#00B4D8',
                    backgroundColor: 'rgba(0, 180, 216, 0.1)',
                    borderWidth: 3,
                    pointRadius: 5,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#00B4D8',
                    pointBorderWidth: 2,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        padding: 12,
                        cornerRadius: 12,
                        backgroundColor: '#0F172A'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        grid: { color: 'rgba(0,0,0,0.03)' },
                        ticks: { font: { size: 11 } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 11 } }
                    }
                }
            }
        });
    });
</script>
@endsection
