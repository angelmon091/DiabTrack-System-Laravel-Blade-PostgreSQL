@extends('layouts.app')

@section('title', 'DiabTrack - Panel Médico')

@section('content')
<main class="container-fluid py-4 px-md-5">
    <div class="row g-4">

        {{-- Sidebar --}}
        <aside class="col-12 col-xl-3 order-2 order-xl-1">
            <div class="diab-card p-4 mb-4 animate-fade-in">
                <div class="tool-header mb-4 d-flex align-items-center text-diab-info">
                    <i class="fa-solid fa-stethoscope me-2"></i>
                    <span class="fw-bold">Panel Médico</span>
                </div>

                <div class="d-flex flex-column gap-2">
                    <a href="{{ route('doctor.link') }}" class="action-item">
                        <div class="action-icon blue"><i class="fa-solid fa-link"></i></div>
                        <div class="ms-3">
                            <strong class="d-block">Vincular Paciente</strong>
                            <p class="mb-0 extra-small text-muted">Conecta usando un código</p>
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

            <div class="diab-card p-4 mb-4 animate-fade-in" style="animation-delay: 0.1s;">
                <h6 class="fw-bold mb-3 text-muted text-uppercase letter-spacing-1 small">Tu Información</h6>
                <div class="d-flex flex-column gap-2">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted small">Especialidad:</span>
                        <span class="fw-bold small">{{ auth()->user()->doctorProfile->specialty ?? '--' }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted small">Cédula:</span>
                        <span class="fw-bold small">{{ auth()->user()->doctorProfile->license_number ?? '--' }}</span>
                    </div>
                </div>
            </div>
        </aside>

        {{-- Contenido Principal --}}
        <section class="col-12 col-xl-9 order-1 order-xl-2">
            <div class="mb-4 animate-fade-in">
                <h2 class="fw-extrabold mb-1 fs-2">Dr. <span class="text-diab-primary">{{ auth()->user()->name }}</span></h2>
                <p class="text-muted">Panel de seguimiento de pacientes.</p>
            </div>

            @if(session('status'))
                <div class="alert alert-success border-0 bg-success bg-opacity-10 animate-fade-in mb-4">
                    <i class="fa-solid fa-circle-check me-2 text-success"></i>
                    <span class="text-success fw-medium">{{ session('status') }}</span>
                </div>
            @endif

            @if($patients->isEmpty())
                <div class="diab-card p-5 text-center animate-fade-in">
                    <div class="admin-card-icon-wrapper mx-auto bg-diab-info-light mb-4">
                        <i class="fa-solid fa-user-plus fs-2 text-diab-info"></i>
                    </div>
                    <h4 class="fw-bold mb-2">Aún no tienes pacientes vinculados</h4>
                    <p class="text-muted mb-4">Pide a tu paciente que genere un <strong>código de invitación</strong> desde su panel y luego ingrésalo aquí.</p>
                    <a href="{{ route('doctor.link') }}" class="btn-diab-primary">
                        <i class="fa-solid fa-link me-2"></i>Vincular Paciente
                    </a>
                </div>
            @else
                <div class="row g-4">
                    @foreach($patients as $patient)
                        <div class="col-12 col-md-6 col-xl-4 animate-fade-in">
                            <a href="{{ route('doctor.patient.show', $patient) }}" class="text-decoration-none">
                                <div class="diab-card p-4 h-100 transition-hover">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="rounded-circle overflow-hidden shadow-sm flex-shrink-0" style="width: 50px; height: 50px;">
                                            @php
                                                $gender = strtolower($patient->patientProfile?->gender ?? '');
                                                $avatar = $patient->avatar;
                                            @endphp
                                            @if($avatar && str_starts_with($avatar, 'http'))
                                                <img src="{{ $avatar }}" class="w-100 h-100 object-fit-cover" alt="{{ $patient->name }}">
                                            @elseif($avatar)
                                                <img src="{{ asset('storage/' . $avatar) }}" class="w-100 h-100 object-fit-cover" alt="{{ $patient->name }}">
                                            @else
                                                @if($gender === 'femenino')
                                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center text-white" style="background: linear-gradient(135deg, var(--diab-danger) 0%, #C0392B 100%);">
                                                        <i class="fa-solid fa-person-dress"></i>
                                                    </div>
                                                @else
                                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center text-white" style="background: linear-gradient(135deg, var(--diab-primary) 0%, var(--diab-primary-hover) 100%);">
                                                        <i class="fa-solid fa-user-tie"></i>
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                        <div class="ms-3">
                                            <strong class="d-block">{{ $patient->name }}</strong>
                                            <span class="text-muted extra-small">{{ $patient->patientProfile?->diabetes_type ?? '--' }}</span>
                                        </div>
                                    </div>
                                    <div class="row g-2 mb-3">
                                        @php
                                            $lastVital = $patient->vitalSigns->sortByDesc('created_at')->first();
                                        @endphp
                                        <div class="col-4">
                                            <div class="p-2 rounded-3 text-center" style="background: rgba(0,194,224,0.08);">
                                                <span class="extra-small text-muted d-block">Glucosa</span>
                                                <strong class="text-diab-primary small">{{ $lastVital->glucose_level ?? '--' }}</strong>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="p-2 rounded-3 text-center bg-diab-success-light">
                                                <span class="extra-small text-muted d-block">Presión</span>
                                                <strong class="small text-diab-success">{{ $lastVital ? ($lastVital->systolic.'/'.$lastVital->diastolic) : '--' }}</strong>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="p-2 rounded-3 text-center bg-diab-warning-light">
                                                <span class="extra-small text-muted d-block">Peso</span>
                                                <strong class="small text-diab-warning">{{ $patient->patientProfile?->weight ?? '--' }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex gap-1">
                                        <span class="badge bg-info bg-opacity-10 text-info extra-small">
                                            Meta: {{ $patient->patientProfile?->target_glucose_min ?? '70' }}-{{ $patient->patientProfile?->target_glucose_max ?? '180' }} mg/dL
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>
    </div>
</main>
@endsection
