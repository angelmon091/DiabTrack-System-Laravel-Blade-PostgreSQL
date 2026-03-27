@extends('layouts.tracking')

@section('title', 'Registro de Datos - Nutrición')

@section('content')
<form action="{{ route('registro.nutricion.store') }}" method="POST" class="nutrition-layout">
    @csrf
    
    <div class="nutrition-group">
        <h3>Tipo de Comida</h3>
        <label class="checkbox-item"><input type="checkbox" name="meal_type[]" value="Desayuno"> Desayuno</label>
        <label class="checkbox-item"><input type="checkbox" name="meal_type[]" value="Almuerzo"> Almuerzo</label>
        <label class="checkbox-item"><input type="checkbox" name="meal_type[]" value="Cena"> Cena</label>
        <label class="checkbox-item"><input type="checkbox" name="meal_type[]" value="Snack/Colación"> Snack/Colación</label>
        <label class="checkbox-item"><input type="checkbox" name="meal_type[]" value="Corrección"> Corrección <i style="color:#888; margin-left:5px; font-weight:400;">(solo insulina)</i></label>
    </div>

    <div class="nutrition-group">
        <h3>Carbohidratos Totales</h3>
        <input type="number" name="carbs" class="pill-input @error('carbs') is-invalid @enderror" placeholder="Ingrese la cantidad en gramos" value="{{ old('carbs') }}">
        @error('carbs')
            <div class="text-danger small ms-3" style="margin-top: -5px; margin-bottom: 10px;">{{ $message }}</div>
        @enderror
        
        <h3 style="margin-top: 20px;">Hora</h3>
        <input type="time" name="meal_time" class="pill-input" value="{{ date('H:i') }}">
    </div>

    <div class="nutrition-group">
        <h3>Alimentos Ingeridos</h3>
        <label class="checkbox-item"><input type="checkbox" name="foods[]" value="Pan/Cereales"> Pan/Cereales</label>
        <label class="checkbox-item"><input type="checkbox" name="foods[]" value="Proteínas"> Proteínas <i style="color:#888; margin-left:5px; font-weight:400;">(carne/huevo)</i></label>
        <label class="checkbox-item"><input type="checkbox" name="foods[]" value="Vegetales"> Vegetales</label>
        <label class="checkbox-item"><input type="checkbox" name="foods[]" value="Fruta"> Fruta</label>
        <label class="checkbox-item"><input type="checkbox" name="foods[]" value="Lácteos"> Lácteos</label>
        <label class="checkbox-item"><input type="checkbox" name="foods[]" value="Grasas/Aceites"> Grasas/Aceites</label>
    </div>

    <div class="nutrition-group">
        <h3>¿Tomó medicación antes de comer?</h3>
        <label class="checkbox-item"><input type="checkbox" name="medication[]" value="Insulina Rápida"> Insulina Rápida</label>
        <label class="checkbox-item"><input type="checkbox" name="medication[]" value="Medicamento Oral"> Medicamento Oral</label>
        <label class="checkbox-item"><input type="checkbox" name="medication[]" value="Ninguno"> Ninguno</label>
        
        <input type="text" name="medication_amount" class="pill-input" placeholder="Ingrese la cantidad (n unidades/n pastilla)" style="margin-top:10px;">
        <input type="time" name="medication_time" class="pill-input" value="{{ date('H:i') }}">
    </div>

     <div class="nutrition-group">
        <h3>Impacto Glucémico Esperado</h3>
        <label class="checkbox-item"><input type="checkbox" name="glycemic_impact" value="Bajo"> Bajo <i style="color:#888; margin-left:5px; font-weight:400;">(ej. ensalada con proteína)</i></label>
        <label class="checkbox-item"><input type="checkbox" name="glycemic_impact" value="Medio"> Medio <i style="color:#888; margin-left:5px; font-weight:400;">(ej. comida balanceada)</i></label>
        <label class="checkbox-item"><input type="checkbox" name="glycemic_impact" value="Alto"> Alto <i style="color:#888; margin-left:5px; font-weight:400;">(ej. pizza/pasta/postre)</i></label>
        <label class="checkbox-item"><input type="checkbox" name="glycemic_impact" value="Muy alto"> Muy alto <i style="color:#888; margin-left:5px; font-weight:400;">(ej. buffet/dulces)</i></label>
    </div>

    <div class="form-actions">
        <button type="reset" class="btn-borrar">Borrar</button>
        <button type="submit" class="btn-guardar">Guardar</button>
    </div>

</form>
@endsection
