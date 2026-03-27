@extends('layouts.tracking')

@php
    $isEdit = isset($signo);
    $route = $isEdit ? route('registro.signos.update', $signo->id) : route('registro.signos.store');
@endphp

@section('title', $isEdit ? 'Editar Signo Vital' : 'Registro de Datos - Signos Vitales')

@section('content')
<form action="{{ $route }}" method="POST" class="main-content">
    @csrf
    @if($isEdit) @method('PUT') @endif
    
    <section class="form-section">
        <div class="input-group">
            <label>Nivel de Glucosa: <strong id="glucose_count">{{ $isEdit ? $signo->glucose_level : '120' }}</strong> mg/dL</label>
            <input type="range" name="glucose_level" min="40" max="300" value="{{ $isEdit ? $signo->glucose_level : '120' }}" id="glucose_range">
        </div>

        <div class="input-group">
            <label>Presión Arterial:</label>
            <div class="double-select">
                <select name="systolic">
                    <option value="">Sistólica (mmHg)</option>
                    @for ($i = 70; $i <= 200; $i += 5)
                        <option value="{{ $i }}" {{ ($isEdit && $signo->systolic == $i) ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
                <select name="diastolic">
                    <option value="">Diastólica (mmHg)</option>
                    @for ($i = 40; $i <= 130; $i += 5)
                        <option value="{{ $i }}" {{ ($isEdit && $signo->diastolic == $i) ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
        </div>

        <div class="input-group">
            <label>Frecuencia Cardiaca: <strong id="heart_count">{{ $isEdit ? $signo->heart_rate : '75' }}</strong> bpm</label>
            <input type="range" name="heart_rate" min="40" max="200" value="{{ $isEdit ? $signo->heart_rate : '75' }}" id="heart_range">
        </div>

        <div class="input-group">
            <label>Hemoglobina Glicosilada:</label>
            <div class="double-select">
                <select name="hba1c_period">
                    <option value="">Lapso de Tiempo</option>
                    <option value="90">Últimos 90 días</option>
                    <option value="60">Últimos 60 días</option>
                </select>
                <input type="number" step="0.1" name="hba1c" class="pill-input mt-0" placeholder="% de HbA1c" value="{{ $isEdit ? $signo->hba1c : '' }}" style="flex: 1; height: 50px;">
            </div>
        </div>
    </section>

    <aside class="timing-panel">
        <h3>Momento de la Medición</h3>
        <div class="timing-grid">
            <input type="hidden" name="measurement_moment" id="measurement_moment" value="{{ $isEdit ? $signo->measurement_moment : 'Ayunas' }}">
            
            @php
                $moments = [
                    ['Ayunas', 'wb_sunny.png'],
                    ['Antes de Comer', 'no_meals_ouline.png'],
                    ['Después de Comer', 'restaurant.png'],
                    ['Al Dormir', 'bedtime.png']
                ];
            @endphp

            @foreach($moments as $m)
                <button type="button" class="time-btn {{ ($isEdit ? $signo->measurement_moment == $m[0] : $m[0] == 'Ayunas') ? 'active-btn' : '' }}" onclick="setMoment('{{ $m[0] }}', this)">
                    <img src="{{ asset('img/medios/iconos/' . $m[1]) }}" alt="{{ $m[0] }}">
                    <span>{{ $m[0] }}</span>
                </button>
            @endforeach
        </div>
    </aside>

    <div class="form-actions">
        <a href="{{ $isEdit ? route('registro.historial') : route('dashboard') }}" class="btn-borrar" style="text-decoration:none; text-align:center; padding-top:15px;">Cancelar</a>
        <button type="submit" class="btn-guardar">{{ $isEdit ? 'Actualizar' : 'Guardar' }}</button>
    </div>
</form>
@endsection

@section('scripts')
<script>
    document.getElementById('glucose_range').addEventListener('input', function() {
        document.getElementById('glucose_count').innerText = this.value;
    });
    document.getElementById('heart_range').addEventListener('input', function() {
        document.getElementById('heart_count').innerText = this.value;
    });

    function setMoment(moment, btn) {
        document.getElementById('measurement_moment').value = moment;
        document.querySelectorAll('.time-btn').forEach(b => b.classList.remove('active-btn'));
        btn.classList.add('active-btn');
    }
</script>
<style>
    .active-btn {
        background-color: #92e6eb !important;
        border: 2px solid #00bcd4 !important;
    }
</style>
@endsection
