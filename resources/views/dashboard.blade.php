@extends('layouts.app')

@section('title', 'DiabTrack - Dashboard')

@section('content')
    <main class="container-fluid py-4 px-md-5">
        <div class="row g-4">

            <aside class="col-12 col-xl-3 order-2 order-xl-1">
                <div class="diab-card p-4 mb-4 animate-fade-in">
                    <div class="tool-header mb-4 d-flex align-items-center text-diab-primary">
                        <span class="fw-bold">Gestión DiabTrack</span>
                    </div>

                    <div class="d-flex flex-column gap-2">
                        <a href="{{ route('tracking.nutrition.index') }}" class="action-item">
                            <div class="action-icon orange"><i class="fa-solid fa-robot"></i></div>
                            <div class="ms-3">
                                <strong class="d-block">Nutrición IA</strong>
                                <p class="mb-0 extra-small text-muted">Planificación de comidas</p>
                            </div>
                        </a>
                        <a href="{{ route('tracking.summary') }}" class="action-item">
                            <div class="action-icon blue"><i class="fa-solid fa-chart-line"></i></div>
                            <div class="ms-3">
                                <strong class="d-block">Gráficos</strong>
                                <p class="mb-0 extra-small text-muted">Análisis de tendencias</p>
                            </div>
                        </a>
                        <a href="{{ route('tracking.vital.create') }}" class="action-item">
                            <div class="action-icon green"><i class="fa-solid fa-plus"></i></div>
                            <div class="ms-3">
                                <strong class="d-block">Registrar</strong>
                                <p class="mb-0 extra-small text-muted">Añadir entrada diaria</p>
                            </div>
                        </a>
                        <a href="{{ route('profile.edit') }}" class="action-item">
                            <div class="action-icon gray"><i class="fa-solid fa-sliders"></i></div>
                            <div class="ms-3">
                                <strong class="d-block">Ajustes</strong>
                                <p class="mb-0 extra-small text-muted">Configurar perfil</p>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Tip del Día -->
                <div class="diab-card p-4 mb-4 animate-fade-in" style="animation-delay: 0.1s;">
                    <h6 class="fw-bold mb-3 text-diab-text-secondary text-uppercase letter-spacing-1 small">Tip del Día</h6>
                    <div class="d-flex align-items-start">
                        <i class="fa-regular fa-lightbulb text-diab-primary fs-5 me-3 mt-1"></i>
                        <p class="mb-0 small text-muted text-justify" style="line-height: 1.5;">{{ $tipDelDia }}</p>
                    </div>
                </div>

                <!-- Síntomas Hoy -->
                <div class="diab-card p-4 mb-4 animate-fade-in" style="animation-delay: 0.15s;">
                    <h6 class="fw-bold mb-3 text-diab-text-secondary text-uppercase letter-spacing-1 small">Síntomas Hoy</h6>
                    <div class="d-flex align-items-center">
                        <div class="act-icon me-3" style="background: {{ $sintomasHoy > 0 ? 'var(--diab-danger-light)' : 'var(--diab-success-light)' }}; color: {{ $sintomasHoy > 0 ? 'var(--diab-danger)' : 'var(--diab-success)' }};">
                            <i class="fa-solid {{ $sintomasHoy > 0 ? 'fa-triangle-exclamation' : 'fa-shield-heart' }}"></i>
                        </div>
                        <div>
                            <h4 class="fw-extrabold mb-0">{{ $sintomasHoy }}</h4>
                            <span class="text-muted extra-small">{{ $sintomasHoy == 1 ? 'síntoma reportado' : 'síntomas reportados' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Actividad Reciente -->
                <div class="diab-card p-4 mb-4 d-none d-xl-block animate-fade-in" style="animation-delay: 0.2s;">
                    <h6 class="fw-bold mb-3 text-diab-text-secondary text-uppercase letter-spacing-1 small">Actividad Reciente</h6>
                    <div class="d-flex align-items-center">
                        <i class="fa-solid fa-clock-rotate-left text-diab-success fs-5 me-3"></i>
                        <div>
                            <strong class="d-block small text-dark mb-1">Última Medición</strong>
                            <span class="text-muted extra-small">
                                {{ $ultimaMedicion ? $ultimaMedicion->created_at->diffForHumans() : 'Sin registros' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Compartir Acceso -->
                <div class="diab-card p-4 mb-4 animate-fade-in" style="animation-delay: 0.25s;">
                    <h6 class="fw-bold mb-3 text-diab-text-secondary text-uppercase letter-spacing-1 small">Compartir Acceso</h6>
                    <p class="extra-small text-muted mb-3">Genera un código para que tu médico o cuidador pueda ver tus datos.</p>
                    
                    @if(session('invite_code'))
                        <div class="text-center p-3 rounded-3 mb-3 animate-pulse" style="background: rgba(0, 194, 224, 0.1); border: 1px dashed var(--diab-primary);">
                            <span class="extra-small text-muted d-block mb-1">Código Temporal (24h)</span>
                            <strong class="fs-4 letter-spacing-2 text-diab-primary">{{ session('invite_code') }}</strong>
                        </div>
                    @endif

                    <form action="{{ route('dashboard.invite') }}" method="POST" class="d-flex flex-column gap-2">
                        @csrf
                        <div class="d-flex gap-2">
                            <select name="role" class="form-select extra-small py-1" required>
                                <option value="caregiver">Cuidador</option>
                                <option value="doctor">Médico</option>
                            </select>
                            <button type="submit" class="btn-diab-primary extra-small py-1 px-3 shadow-none">
                                <i class="fa-solid fa-key me-1"></i> Generar
                            </button>
                        </div>
                    </form>
                </div>

            </aside>

            <section class="col-12 col-xl-9">
                <div class="d-flex justify-content-between align-items-center mb-4 animate-fade-in">
                    <h3 class="fw-bold mb-0 fs-4">Resumen de Datos <span class="text-diab-primary">Total</span></h3>
                    <div class="text-muted small d-none d-sm-block glass-effect px-3 py-1 rounded-pill border">
                        {{ date('d M, Y') }}</div>
                </div>

                @if(session('status'))
                    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                        <i class="fa-solid fa-circle-check me-2"></i>{{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- Tarjeta de Recordatorio Mensual de Peso --}}
                @if($needsWeightUpdate)
                <div class="diab-card p-4 mb-4 animate-fade-in" style="border-left: 5px solid var(--diab-primary); animation-delay: 0.15s;">
                    <form action="{{ route('dashboard.weight.store') }}" method="POST">
                        @csrf
                        <div class="d-flex flex-column flex-sm-row align-items-sm-center gap-3">
                            <div class="d-flex align-items-center flex-grow-1">
                                <div class="act-icon me-3" style="background: var(--diab-primary-light); color: var(--diab-primary); flex-shrink: 0;">
                                    <i class="fa-solid fa-weight-scale"></i>
                                </div>
                                <div>
                                    <strong class="d-block text-dark">Actualización Mensual de Peso</strong>
                                    <p class="text-muted extra-small mb-0">
                                        @if($ultimoPesoValor)
                                            Último registro: <strong>{{ $ultimoPesoValor }} kg</strong> — Hace más de 30 días
                                        @else
                                            Aún no has registrado tu peso este mes
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2 flex-shrink-0">
                                <div class="input-group" style="max-width: 180px;">
                                    <input type="number" name="weight" step="0.1" min="20" max="350" 
                                           class="form-control form-control-sm border-0 shadow-sm" 
                                           placeholder="{{ $ultimoPesoValor ?? 'Peso' }}" 
                                           required 
                                           style="background: var(--diab-bg); border-radius: 12px 0 0 12px !important; font-weight: 600;">
                                    <span class="input-group-text border-0 shadow-sm small fw-bold" 
                                          style="background: var(--diab-bg); border-radius: 0 12px 12px 0 !important;">kg</span>
                                </div>
                                <button type="submit" class="btn btn-sm text-white fw-bold shadow-sm px-3" 
                                        style="background: linear-gradient(135deg, var(--diab-primary), var(--diab-primary-hover)); border-radius: 12px; white-space: nowrap;">
                                    <i class="fa-solid fa-check me-1"></i> Guardar
                                </button>
                            </div>
                        </div>
                        @error('weight')
                            <p class="text-danger small mt-2 mb-0"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</p>
                        @enderror
                    </form>
                </div>
                @endif

                {{-- Hero Row: Glucosa + Tendencia --}}
                <div class="row g-4 mb-4">
                    <div class="col-12 col-lg-5">
                        @php
                            $heroBg = 'var(--diab-primary-light)';
                            $heroRadial = 'rgba(0, 180, 216, 0.15)';
                            if ($ultimaMedicion && $ultimaMedicion->glucose_level) {
                                $g = $ultimaMedicion->glucose_level;
                                if ($g > 140) { 
                                    $heroBg = 'var(--diab-danger-light)'; 
                                    $heroRadial = 'rgba(234, 84, 85, 0.15)'; 
                                }
                                elseif ($g < 70) { 
                                    $heroBg = 'var(--diab-warning-light)'; 
                                    $heroRadial = 'rgba(255, 159, 67, 0.15)'; 
                                }
                                else { 
                                    $heroBg = 'var(--diab-success-light)'; 
                                    $heroRadial = 'rgba(40, 199, 111, 0.15)'; 
                                }
                            }
                        @endphp
                        <div class="diab-card glucosa-hero p-4 h-100 d-flex flex-column justify-content-center align-items-center animate-fade-in" 
                             style="--hero-bg: {{ $heroBg }}; --hero-radial: {{ $heroRadial }}; animation-delay: 0.2s;">
                            <div class="text-center w-100">
                                <span class="text-diab-text-secondary fw-bold small mb-2 d-block text-uppercase letter-spacing-1">Última Medición de Glucosa</span>
                                <div class="d-flex align-items-baseline justify-content-center">
                                    <h1 class="display-3 fw-extrabold mb-0 text-dark">
                                        {{ $ultimaMedicion->glucose_level ?? '--' }}
                                    </h1>
                                    <span class="ms-2 fs-5 text-muted">mg/dL</span>
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

                    <div class="col-12 col-lg-7">
                        <div class="diab-card p-4 h-100 d-flex flex-column animate-fade-in" style="animation-delay: 0.3s;">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold mb-0 text-diab-text-secondary text-uppercase letter-spacing-1" style="font-size: 0.8rem;">Tendencia Semanal</h6>
                            </div>
                            <div class="flex-grow-1 position-relative" style="min-height: 180px;">
                                @if(collect($glucosaData)->filter()->isNotEmpty())
                                    <canvas id="glucosaChart" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0;"></canvas>
                                @else
                                    <div class="text-center h-100 d-flex flex-column align-items-center justify-content-center">
                                        <div class="act-icon gray shadow-sm mb-2"><i class="fa-solid fa-chart-line"></i></div>
                                        <p class="text-muted small mb-0">Sin suficientes datos de glucosa esta semana</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Métricas Fusionadas - Rediseño Compacto --}}
                <div class="row g-4 mb-4">
                    <!-- A1c + Calorías -->
                    <div class="col-12 col-md-4">
                        <div class="diab-card p-4 h-100 animate-fade-in" style="animation-delay: 0.3s;">
                            <div class="d-flex flex-column h-100 justify-content-between">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div data-bs-toggle="tooltip" title="Un cálculo de cómo ha estado tu azúcar en los últimos 3 meses.">
                                        <span class="extra-small fw-bold text-muted text-uppercase letter-spacing-1 d-block mb-1">A1c Estimada <i class="fa-solid fa-circle-info ms-1 opacity-50"></i></span>
                                        <h3 class="fw-extrabold mb-0 text-dark">{{ $ultimaHba1c ? number_format($ultimaHba1c->hba1c, 1) . '%' : '--' }}</h3>
                                    </div>
                                    <div class="act-icon fire shadow-sm"><i class="fa-solid fa-dna"></i></div>
                                </div>
                                <div class="bg-light rounded-4 p-3 border border-white shadow-sm">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="fw-bold extra-small text-diab-danger"><i class="fa-solid fa-fire me-1"></i> Calorías</span>
                                        <span class="fw-bold extra-small text-dark">{{ $porcentajeCalorias }}%</span>
                                    </div>
                                    <div class="progress-container bg-white border mb-2" style="height: 5px;">
                                        <div class="progress-bar-custom shadow-sm" style="width: {{ $porcentajeCalorias }}%; background: var(--diab-danger) !important;"></div>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted" style="font-size: 0.65rem;">Hoy: <strong>{{ $caloriasHoy }}</strong></span>
                                        <span class="text-muted" style="font-size: 0.65rem;">Meta: {{ $metaCalorias }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Carbohidratos + Actividad -->
                    <div class="col-12 col-md-4">
                        <div class="diab-card p-4 h-100 animate-fade-in" style="animation-delay: 0.4s;">
                            <div class="d-flex flex-column h-100 justify-content-between">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div data-bs-toggle="tooltip" title="La cantidad total de carbohidratos que has comido hoy.">
                                        <span class="extra-small fw-bold text-muted text-uppercase letter-spacing-1 d-block mb-1">Carbohidratos <i class="fa-solid fa-circle-info ms-1 opacity-50"></i></span>
                                        <h3 class="fw-extrabold mb-0 text-dark">{{ $carbsHoy }}g</h3>
                                    </div>
                                    <div class="act-icon move shadow-sm"><i class="fa-solid fa-bread-slice"></i></div>
                                </div>
                                <div class="bg-light rounded-4 p-3 border border-white shadow-sm">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="fw-bold extra-small text-diab-warning"><i class="fa-solid fa-bolt me-1"></i> Actividad</span>
                                        <span class="fw-bold extra-small text-dark">{{ $porcentajeActividad }}%</span>
                                    </div>
                                    <div class="progress-container bg-white border mb-2" style="height: 5px;">
                                        <div class="progress-bar-custom shadow-sm" style="width: {{ $porcentajeActividad }}%; background: var(--diab-warning) !important;"></div>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted" style="font-size: 0.65rem;">Hoy: <strong>{{ $actividadMinutos }}m</strong></span>
                                        <span class="text-muted" style="font-size: 0.65rem;">Meta: {{ $metaActividad }}m</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tiempo en Rango + Pasos -->
                    <div class="col-12 col-md-4">
                        <div class="diab-card p-4 h-100 animate-fade-in" style="animation-delay: 0.5s;">
                            <div class="d-flex flex-column h-100 justify-content-between">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div data-bs-toggle="tooltip" title="Porcentaje de veces que tu azúcar estuvo normal (ni muy alta ni muy baja).">
                                        <span class="extra-small fw-bold text-muted text-uppercase letter-spacing-1 d-block mb-1">Tiempo en Rango <i class="fa-solid fa-circle-info ms-1 opacity-50"></i></span>
                                        <h3 class="fw-extrabold mb-0 text-dark">{{ $tiempoEnRango }}%</h3>
                                    </div>
                                    <div class="act-icon feet shadow-sm"><i class="fa-solid fa-clock-rotate-left"></i></div>
                                </div>
                                <div class="bg-light rounded-4 p-3 border border-white shadow-sm">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="fw-bold extra-small text-diab-primary"><i class="fa-solid fa-shoe-prints me-1"></i> Pasos</span>
                                        <span class="fw-bold extra-small text-dark">{{ $porcentajePasos }}%</span>
                                    </div>
                                    <div class="progress-container bg-white border mb-2" style="height: 5px;">
                                        <div class="progress-bar-custom shadow-sm" style="width: {{ $porcentajePasos }}%; background: var(--diab-primary) !important;"></div>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted" style="font-size: 0.65rem;">Hoy: <strong>{{ number_format($pasosEstimados) }}</strong></span>
                                        <span class="text-muted" style="font-size: 0.65rem;">Meta: {{ number_format($metaPasos/1000, 1) }}k</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Historial Reciente --}}
                <div class="diab-card p-4 mb-4 animate-fade-in" style="animation-delay: 0.6s;">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h6 class="fw-bold mb-0 text-diab-text-secondary text-uppercase letter-spacing-1" style="font-size: 0.8rem;">Últimas Mediciones de Glucosa</h6>
                        @if(count($recentLogs ?? []) > 0)
                            <a href="{{ route('tracking.summary') }}" class="btn btn-link btn-sm text-diab-primary text-decoration-none fw-bold small">
                                Ver historial completo <i class="fa-solid fa-arrow-right ms-1"></i>
                            </a>
                        @endif
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr style="font-size: 0.7rem; color: var(--diab-text-secondary); text-transform: uppercase; letter-spacing: 0.5px;">
                                    <th class="border-0 rounded-start px-4">Fecha y Hora</th>
                                    <th class="border-0">Glucosa</th>
                                    <th class="border-0">Momento</th>
                                    <th class="border-0">HbA1c (%)</th>
                                    <th class="border-0 rounded-end px-4 text-center">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentLogs ?? [] as $log)
                                    <tr style="font-size: 0.85rem;">
                                        <td class="px-4 text-dark fw-medium">
                                            {{ $log->created_at->format('d M, Y') }} 
                                            <span class="text-muted small ms-1">{{ $log->created_at->format('H:i') }}</span>
                                        </td>
                                        <td class="fw-extrabold text-dark">{{ $log->glucose_level }} <span class="text-muted fw-normal" style="font-size: 0.7rem;">mg/dL</span></td>
                                        <td>
                                            <span class="badge rounded-pill bg-light text-dark border px-3 py-2 fw-semibold" style="font-size: 0.7rem;">
                                                <i class="fa-regular fa-clock me-1 text-diab-primary"></i> {{ $log->measurement_moment ?? 'Ayunas' }}
                                            </span>
                                        </td>
                                        <td class="text-muted">{{ $log->hba1c ? $log->hba1c . '%' : '--' }}</td>
                                        <td class="text-center">
                                            @php
                                                $statusClass = 'success';
                                                $statusText = 'En Rango';
                                                if($log->glucose_level > 140) { $statusClass = 'danger'; $statusText = 'Alto'; }
                                                elseif($log->glucose_level < 70) { $statusClass = 'warning'; $statusText = 'Bajo'; }
                                            @endphp
                                            <span class="badge rounded-pill bg-{{ $statusClass }}-light text-{{ $statusClass }} px-3 py-2 border border-{{ $statusClass }} opacity-75" style="font-size: 0.7rem; min-width: 80px;">
                                                <i class="fa-solid fa-circle me-1" style="font-size: 0.5rem;"></i> {{ $statusText }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <div class="welcome-illustration mb-3 mx-auto animate-float">
                                                <div class="icon-circle bg-diab-primary-light text-diab-primary mx-auto" style="width: 60px; height: 60px; border-radius: 15px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                                                    <i class="fa-solid fa-notes-medical"></i>
                                                </div>
                                            </div>
                                            <h5 class="fw-bold text-dark mb-2">Tu bitácora está lista</h5>
                                            <p class="text-muted small mx-auto mb-4" style="max-width: 380px;">
                                                Aún no tienes registros de glucosa. Comienza tu seguimiento hoy para visualizar tus tendencias y mejorar tu salud.
                                            </p>
                                            <a href="{{ route('tracking.vital.create') }}" class="btn-diab-primary px-4 py-2 fw-bold text-decoration-none rounded-pill shadow-sm small">
                                                <i class="fa-solid fa-plus me-1"></i> Registrar Glucosa
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Goals Overview - Informative and non-redundant --}}
                <div class="diab-card p-4 animate-fade-in" style="animation-delay: 0.7s; background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(240,249,255,0.5) 100%); border: 1px solid rgba(0, 180, 216, 0.1);">
                    <div class="d-flex align-items-center mb-4">
                        <div class="act-icon gray me-3 shadow-none" style="background: var(--diab-primary-light); color: var(--diab-primary);">
                            <i class="fa-solid fa-bullseye"></i>
                        </div>
                        <h6 class="fw-bold mb-0 text-dark small text-uppercase letter-spacing-1">Resumen de Objetivos Saludables</h6>
                    </div>
                    
                    <div class="row g-4">
                        <div class="col-12 col-md-4">
                            <div class="p-3 rounded-4 bg-white shadow-sm border border-light h-100">
                                <span class="extra-small text-muted d-block mb-1">Rango Glucosa</span>
                                <strong class="text-dark">{{ $user->patientProfile->target_glucose_min ?? 70 }} - {{ $user->patientProfile->target_glucose_max ?? 140 }}</strong>
                                <span class="extra-small text-muted ms-1">mg/dL</span>
                                <div class="progress mt-2" style="height: 4px;">
                                    <div class="progress-bar bg-success" style="width: 100%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="p-3 rounded-4 bg-white shadow-sm border border-light h-100">
                                <span class="extra-small text-muted d-block mb-1">Meta Calórica</span>
                                <strong class="text-dark">{{ number_format($metaCalorias) }}</strong>
                                <span class="extra-small text-muted ms-1">kcal/día</span>
                                <div class="progress mt-2" style="height: 4px;">
                                    <div class="progress-bar bg-danger" style="width: 100%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="p-3 rounded-4 bg-white shadow-sm border border-light h-100">
                                <span class="extra-small text-muted d-block mb-1">Actividad Física</span>
                                <strong class="text-dark">{{ $metaActividad }}</strong>
                                <span class="extra-small text-muted ms-1">min/día</span>
                                <div class="progress mt-2" style="height: 4px;">
                                    <div class="progress-bar bg-warning" style="width: 100%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <p class="mt-4 mb-0 extra-small text-muted italic">
                        <i class="fa-solid fa-circle-info me-1 opacity-50"></i> Estos objetivos son personalizados según tu perfil. Puedes ajustarlos en la configuración de salud.
                    </p>
                </div>
            </section>
        </div>
    </main>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const canvas = document.getElementById('glucosaChart');
        if (!canvas) return;

        const labels = @json($glucosaLabels);
        const data = @json($glucosaData);

                const primaryColor = getComputedStyle(document.documentElement).getPropertyValue('--diab-primary').trim() || '#00B4D8';
                const primaryLight = getComputedStyle(document.documentElement).getPropertyValue('--diab-primary-light').trim() || 'rgba(0, 180, 216, 0.08)';

                new Chart(canvas, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Glucosa (mg/dL)',
                            data: data,
                            borderColor: primaryColor,
                            backgroundColor: primaryLight,
                            borderWidth: 2.5,
                            pointBackgroundColor: primaryColor,
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    tension: 0.4,
                    fill: true,
                    spanGaps: true,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0F172A',
                        titleFont: { family: 'Inter', size: 11 },
                        bodyFont: { family: 'Inter', size: 12, weight: 600 },
                        padding: 10,
                        cornerRadius: 10,
                        callbacks: {
                            label: function(ctx) {
                                return ctx.parsed.y + ' mg/dL';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        suggestedMin: 60,
                        suggestedMax: 200,
                        grid: { color: 'rgba(0,0,0,0.03)' },
                        ticks: {
                            font: { family: 'Inter', size: 10 },
                            color: '#94A3B8'
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { family: 'Inter', size: 10 },
                            color: '#94A3B8'
                        }
                    }
                }
            }
        });
    });
</script>
@endsection