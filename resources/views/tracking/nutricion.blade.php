@extends('layouts.tracking')

@php
    $isEdit = isset($nutritionEntry);
    $route = $isEdit ? route('registro.nutricion.update', $nutritionEntry->id) : route('registro.nutricion.store');
    $mealTypes = $isEdit ? explode(', ', $nutritionEntry->meal_type) : [];
    $foods = $isEdit ? ($nutritionEntry->food_categories ?? []) : [];
    $meds = $isEdit ? explode(', ', $nutritionEntry->medication_taken) : [];
@endphp

@section('title', $isEdit ? 'Editar Nutrición' : 'Registro de Datos - Nutrición')

@section('content')
<form action="{{ $route }}" method="POST" class="nutrition-layout">
    @csrf
    @if($isEdit) @method('PUT') @endif
    
    <div class="nutrition-group">
        <h3>Tipo de Comida</h3>
        <label class="checkbox-item"><input type="checkbox" name="meal_type[]" value="Desayuno" {{ in_array('Desayuno', $mealTypes) ? 'checked' : '' }}> Desayuno</label>
        <label class="checkbox-item"><input type="checkbox" name="meal_type[]" value="Almuerzo" {{ in_array('Almuerzo', $mealTypes) ? 'checked' : '' }}> Almuerzo</label>
        <label class="checkbox-item"><input type="checkbox" name="meal_type[]" value="Cena" {{ in_array('Cena', $mealTypes) ? 'checked' : '' }}> Cena</label>
        <label class="checkbox-item"><input type="checkbox" name="meal_type[]" value="Snack/Colación" {{ in_array('Snack/Colación', $mealTypes) ? 'checked' : '' }}> Snack/Colación</label>
        <label class="checkbox-item"><input type="checkbox" name="meal_type[]" value="Corrección" {{ in_array('Corrección', $mealTypes) ? 'checked' : '' }}> Corrección</label>
    </div>

    <div class="nutrition-group">
        <h3>Carbohidratos Totales</h3>
        <input type="number" name="carbs" class="pill-input @error('carbs') is-invalid @enderror" placeholder="Ingrese la cantidad en gramos" value="{{ $isEdit ? $nutritionEntry->carbs_grams : old('carbs') }}">
        @error('carbs')
            <div class="text-danger small ms-3">{{ $message }}</div>
        @enderror
        
        <h3 style="margin-top: 20px;">Hora</h3>
        <input type="time" name="meal_time" class="pill-input" value="{{ $isEdit ? \Carbon\Carbon::parse($nutritionEntry->consumed_at)->format('H:i') : date('H:i') }}">
    </div>

    <div class="nutrition-group">
        <h3>Alimentos Ingeridos</h3>
        <label class="checkbox-item"><input type="checkbox" name="foods[]" value="Pan/Cereales" {{ in_array('Pan/Cereales', $foods) ? 'checked' : '' }}> Pan/Cereales</label>
        <label class="checkbox-item"><input type="checkbox" name="foods[]" value="Proteínas" {{ in_array('Proteínas', $foods) ? 'checked' : '' }}> Proteínas</label>
        <label class="checkbox-item"><input type="checkbox" name="foods[]" value="Vegetales" {{ in_array('Vegetales', $foods) ? 'checked' : '' }}> Vegetales</label>
        <label class="checkbox-item"><input type="checkbox" name="foods[]" value="Fruta" {{ in_array('Fruta', $foods) ? 'checked' : '' }}> Fruta</label>
    </div>

    <div class="nutrition-group">
        <h3>Medicación</h3>
        <label class="checkbox-item"><input type="checkbox" name="medication[]" value="Insulina Rápida" {{ in_array('Insulina Rápida', $meds) ? 'checked' : '' }}> Insulina Rápida</label>
        <label class="checkbox-item"><input type="checkbox" name="medication[]" value="Medicamento Oral" {{ in_array('Medicamento Oral', $meds) ? 'checked' : '' }}> Medicamento Oral</label>
        <input type="text" name="medication_amount" class="pill-input mt-2" placeholder="Dosis" value="{{ $isEdit ? $nutritionEntry->medication_dose : '' }}">
    </div>

    <div class="form-actions">
        <a href="{{ $isEdit ? route('registro.historial') : route('dashboard') }}" class="btn-borrar" style="text-decoration:none; padding-top:15px; text-align:center;">Cancelar</a>
        <button type="submit" class="btn-guardar">{{ $isEdit ? 'Actualizar' : 'Guardar' }}</button>
    </div>

</form>
@endsection
