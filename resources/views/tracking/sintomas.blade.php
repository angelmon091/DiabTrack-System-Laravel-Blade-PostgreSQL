@extends('layouts.tracking')

@section('title', 'Registro de Datos - Síntomas')

@section('content')
<form action="{{ route('registro.sintomas.store') }}" method="POST" class="symptoms-layout">
    @csrf
    
    @foreach($symptoms as $category => $categorySymptoms)
    <div class="symptom-group">
        <h3>{{ match($category) {
            'physical' => 'Síntomas Físicos',
            'nocturnal' => 'Síntomas Nocturnos',
            'neurological' => 'Síntomas Neurológicos',
            'atypical' => 'Síntomas Atípicos',
            default => $category
        } }}</h3>
        @foreach($categorySymptoms as $symptom)
        <label class="checkbox-item"><input type="checkbox" name="symptoms[]" value="{{ $symptom->id }}"> {{ $symptom->name }}</label>
        @endforeach
    </div>
    @endforeach

    <div class="form-actions">
        <button type="reset" class="btn-borrar">Borrar</button>
        <button type="submit" class="btn-guardar">Guardar</button>
    </div>

</form>
@endsection
