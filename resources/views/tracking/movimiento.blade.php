@extends('layouts.tracking')

@php
    $isEdit = isset($activityEntry);
    $route = $isEdit ? route('registro.movimiento.update', $activityEntry->id) : route('registro.movimiento.store');
    $activities = $isEdit ? explode(', ', $activityEntry->activity_type) : [];
@endphp

@section('title', $isEdit ? 'Editar Movimiento' : 'Registro de Datos - Movimiento')

@section('content')
<form action="{{ $route }}" method="POST" class="movement-layout">
    @csrf
    @if($isEdit) @method('PUT') @endif
    
    <div class="movement-group">
        <h3>Tipo de Actividad</h3>
        <label class="checkbox-item"><input type="checkbox" name="activities[]" value="Caminata" {{ in_array('Caminata', $activities) ? 'checked' : '' }}> Caminata</label>
        <label class="checkbox-item"><input type="checkbox" name="activities[]" value="Correr/Trotar" {{ in_array('Correr/Trotar', $activities) ? 'checked' : '' }}> Correr/Trotar</label>
        <label class="checkbox-item"><input type="checkbox" name="activities[]" value="Bicicleta" {{ in_array('Bicicleta', $activities) ? 'checked' : '' }}> Bicicleta</label>
        <label class="checkbox-item"><input type="checkbox" name="activities[]" value="Natación" {{ in_array('Natación', $activities) ? 'checked' : '' }}> Natación</label>
        <label class="checkbox-item"><input type="checkbox" name="activities[]" value="Fuerza/Gimnasio" {{ in_array('Fuerza/Gimnasio', $activities) ? 'checked' : '' }}> Fuerza/Gimnasio</label>
        <label class="checkbox-item"><input type="checkbox" name="activities[]" value="Yoga/Estiramiento" {{ in_array('Yoga/Estiramiento', $activities) ? 'checked' : '' }}> Yoga/Estiramiento</label>
    </div>

    <div class="movement-group">
        <h3>Intensidad</h3>
        <select name="intensity" class="pill-input">
            <option value="Leve" {{ ($isEdit && $activityEntry->intensity == 'Leve') ? 'selected' : '' }}>Leve (todavía puedes hablar cómodamente)</option>
            <option value="Moderada" {{ ($isEdit && $activityEntry->intensity == 'Moderada') ? 'selected' : '' }}>Moderada (hablar es un poco difícil)</option>
            <option value="Vigorosa" {{ ($isEdit && $activityEntry->intensity == 'Vigorosa') ? 'selected' : '' }}>Vigorosa (no puedes mantener una conversación)</option>
        </select>

        <h3 style="margin-top: 20px;">Tiempo de inicio</h3>
        <input type="time" name="start_time" id="start_time" class="pill-input" value="{{ $isEdit ? $activityEntry->start_time : date('H:i') }}">

        <h3 style="margin-top: 20px;">Tiempo de término</h3>
        <input type="time" name="end_time" id="end_time" class="pill-input" value="{{ $isEdit ? $activityEntry->end_time : date('H:i', strtotime('+30 minutes')) }}">
    </div>

    <div class="form-actions">
        <a href="{{ $isEdit ? route('registro.historial') : route('dashboard') }}" class="btn-borrar" style="text-decoration:none; padding-top:15px; text-align:center;">Cancelar</a>
        <button type="submit" class="btn-guardar">{{ $isEdit ? 'Actualizar' : 'Guardar' }}</button>
    </div>

</form>
@endsection
