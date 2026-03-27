@extends('layouts.tracking')

@section('title', 'Historial de Registros')

@section('styles')
<style>
    .history-card {
        background: white;
        border-radius: 20px;
        padding: 20px;
        margin-bottom: 15px;
        border-left: 5px solid var(--primary);
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: transform 0.2s;
    }
    .history-card:hover {
        transform: scale(1.01);
    }
    .val-pill {
        background: #e0f7fa;
        color: #00838f;
        padding: 5px 15px;
        border-radius: 20px;
        font-weight: bold;
    }
    .btn-delete {
        color: #ef5350;
        background: transparent;
        border: none;
        cursor: pointer;
        padding: 10px;
        border-radius: 50%;
        transition: background 0.2s;
    }
    .btn-delete:hover {
        background: #ffebee;
    }
    .nav-tabs {
        border-bottom: 2px solid #eee;
        margin-bottom: 30px;
        gap: 10px;
    }
    .nav-link {
        border: none !important;
        color: #888;
        font-weight: 600;
        padding: 10px 20px;
        border-radius: 12px !important;
    }
    .nav-link.active {
        background-color: var(--primary) !important;
        color: white !important;
    }
</style>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h2>Historial de Registros</h2>
        <a href="{{ route('registro.signos') }}" class="btn-cyan-solid" style="text-decoration:none; padding:10px 20px; border-radius:15px; font-weight:600;">Nuevo Registro</a>
    </div>
</div>

<ul class="nav nav-tabs" id="historyTabs" role="tablist">
    <li class="nav-item">
        <button class="nav-link active" id="vitals-tab" data-bs-toggle="tab" data-bs-target="#vitals" type="button">Signos Vitales</button>
    </li>
    <li class="nav-item">
        <button class="nav-link" id="nutricion-tab" data-bs-toggle="tab" data-bs-target="#nutricion" type="button">Nutrición</button>
    </li>
    <li class="nav-item">
        <button class="nav-link" id="sintomas-tab" data-bs-toggle="tab" data-bs-target="#sintomas" type="button">Síntomas</button>
    </li>
    <li class="nav-item">
        <button class="nav-link" id="actividad-tab" data-bs-toggle="tab" data-bs-target="#actividad" type="button">Actividad</button>
    </li>
</ul>

<div class="tab-content" id="historyTabsContent">
    <!-- Vitals -->
    <div class="tab-pane fade show active" id="vitals" role="tabpanel">
        @if($vitals->isEmpty())
            <p class="text-center py-5 text-muted">Aún no tienes registros de signos vitales.</p>
        @else
            @foreach($vitals as $signo)
                <div class="history-card" style="border-left-color: #00bcd4;">
                    <div>
                        <span class="text-muted small d-block">{{ $signo->created_at->format('d M Y, H:i') }}</span>
                        <strong class="d-block">{{ $signo->measurement_moment }}</strong>
                    </div>
                    <div class="d-flex align-items-center gap-4">
                        <div class="text-center">
                            <span class="val-pill">{{ $signo->glucose_level }} mg/dL</span>
                            <small class="d-block text-muted">Glucosa</small>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('registro.signos.edit', $signo->id) }}" class="btn-delete" style="color:#00bcd4; background: #e0f7fa;" title="Editar"><i class="fa-solid fa-pen-to-square"></i></a>
                            <form action="{{ route('registro.signos.destroy', $signo->id) }}" method="POST" onsubmit="return confirm('¿Eliminar registro?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-delete"><i class="fa-solid fa-trash-can"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <!-- Nutricion -->
    <div class="tab-pane fade" id="nutricion" role="tabpanel">
        @if($nutrition->isEmpty())
            <p class="text-center py-5 text-muted">Aún no tienes registros de nutrición.</p>
        @else
            @foreach($nutrition as $nutri)
                <div class="history-card" style="border-left-color: #ff9800;">
                    <div>
                        <span class="text-muted small d-block">{{ $nutri->created_at->format('d M Y') }} - {{ $nutri->consumed_at }}</span>
                        <strong class="d-block text-uppercase small">{{ $nutri->meal_type }}</strong>
                    </div>
                    <div class="d-flex align-items-center gap-4">
                        <div class="text-center">
                            <span class="val-pill" style="background:#fff3e0; color:#e65100;">{{ $nutri->carbs_grams }}g</span>
                            <small class="d-block text-muted">Carbohidratos</small>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('registro.nutricion.edit', $nutri->id) }}" class="btn-delete" style="color:#ff9800; background: #fff3e0;" title="Editar"><i class="fa-solid fa-pen-to-square"></i></a>
                            <form action="{{ route('registro.nutricion.destroy', $nutri->id) }}" method="POST" onsubmit="return confirm('¿Eliminar registro?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-delete"><i class="fa-solid fa-trash-can"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <!-- Sintomas -->
    <div class="tab-pane fade" id="sintomas" role="tabpanel">
        @if($symptomsEntries->isEmpty())
            <p class="text-center py-5 text-muted">Aún no tienes registros de síntomas.</p>
        @else
            @foreach($symptomsEntries as $symptom)
                <div class="history-card" style="border-left-color: #f44336;">
                    <div>
                        <span class="text-muted small d-block">{{ $symptom->pivot->logged_at ? \Carbon\Carbon::parse($symptom->pivot->logged_at)->format('d M Y, H:i') : '' }}</span>
                        <strong class="d-block">{{ $symptom->name }}</strong>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <span class="badge rounded-pill bg-light text-dark p-2 px-3">{{ match($symptom->category) {
                            'physical' => 'Físico',
                            'nocturnal' => 'Nocturno',
                            'neurological' => 'Neurológico',
                            'atypical' => 'Atípico',
                            default => $symptom->category
                        } }}</span>
                        
                        <div class="d-flex gap-2">
                            <a href="{{ route('registro.sintomas.edit', ['symptom_id' => $symptom->id, 'logged_at' => $symptom->pivot->logged_at]) }}" class="btn-delete" style="color:#f44336; background: #ffebee;" title="Editar"><i class="fa-solid fa-pen-to-square"></i></a>
                            <form action="{{ route('registro.sintomas.destroy') }}" method="POST" onsubmit="return confirm('¿Eliminar este síntoma del historial?')">
                                @csrf @method('DELETE')
                                <input type="hidden" name="symptom_id" value="{{ $symptom->id }}">
                                <input type="hidden" name="logged_at" value="{{ $symptom->pivot->logged_at }}">
                                <button type="submit" class="btn-delete"><i class="fa-solid fa-trash-can"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <!-- Actividad -->
    <div class="tab-pane fade" id="actividad" role="tabpanel">
        @if($activity->isEmpty())
            <p class="text-center py-5 text-muted">Aún no tienes registros de actividad.</p>
        @else
            @foreach($activity as $act)
                <div class="history-card" style="border-left-color: #4caf50;">
                    <div>
                        <span class="text-muted small d-block">{{ $act->created_at->format('d M Y') }} ({{ $act->start_time }} - {{ $act->end_time }})</span>
                        <strong class="d-block">{{ $act->activity_type }}</strong>
                    </div>
                    <div class="d-flex align-items-center gap-4">
                        <div class="text-center">
                            <span class="val-pill" style="background:#e8f5e9; color:#1b5e20;">{{ $act->duration_minutes }} min</span>
                            <small class="d-block text-muted">{{ $act->intensity }}</small>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('registro.movimiento.edit', $act->id) }}" class="btn-delete" style="color:#4caf50; background: #e8f5e9;" title="Editar"><i class="fa-solid fa-pen-to-square"></i></a>
                            <form action="{{ route('registro.movimiento.destroy', $act->id) }}" method="POST" onsubmit="return confirm('¿Eliminar registro?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-delete"><i class="fa-solid fa-trash-can"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>

@endsection
