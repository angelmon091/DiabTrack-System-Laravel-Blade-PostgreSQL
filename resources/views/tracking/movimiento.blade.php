@extends('layouts.tracking')

@section('title', 'Registro de Datos - Movimiento')

@section('content')
<form action="{{ route('registro.movimiento.store') }}" method="POST" class="movement-layout">
    @csrf
    
    <div class="movement-group group-time-intensity">
        
        <h3 class="section-title">Duración de Actividad: <span id="duration_text">0</span> min</h3>
        
        <div class="time-row">
            <label>Inicio:</label>
            <input type="time" name="start_time" id="start_time" class="pill-input" value="{{ date('H:i') }}">
        </div>
        
        <div class="time-row">
            <label>Fin:</label>
            <input type="time" name="end_time" id="end_time" class="pill-input" value="{{ date('H:i') }}">
        </div>

        <br>
        <h3 class="section-title">Intensidad</h3>
        
        <div class="intensity-grid">
            <label class="checkbox-item"><input type="radio" name="intensity" value="Muy ligera"> Muy ligera</label>
            <label class="checkbox-item"><input type="radio" name="intensity" value="Intensa"> Intensa</label>
            <label class="checkbox-item"><input type="radio" name="intensity" value="Ligera"> Ligera</label>
            <label class="checkbox-item"><input type="radio" name="intensity" value="Máxima"> Máxima</label>
            <label class="checkbox-item"><input type="radio" name="intensity" value="Moderada"> Moderada</label>
        </div>

    </div>

    <div class="movement-group group-activity-type">
        <h3 class="section-title">¿Qué tipo de actividad realizó?</h3>

        <div class="activity-block">
            <label class="checkbox-item activity-label">
                <input type="checkbox" name="activities[]" value="Aeróbico/Cardio"> 
                <span>
                    <strong>Aeróbico/Cardio</strong> <small>(caminar, correr, nadar, ciclismo)</small>
                </span>
            </label>
            <div class="activity-icons">
                <img src="{{ asset('img/medios/iconos/directions_walk.png') }}" alt="caminar">
                <img src="{{ asset('img/medios/iconos/Vector.png') }}" alt="correr">
                <img src="{{ asset('img/medios/iconos/pool.png') }}" alt="nadar">
                <img src="{{ asset('img/medios/iconos/directions_bike.png') }}" alt="bici">
            </div> 
        </div>

        <div class="activity-block">
            <label class="checkbox-item activity-label">
                <input type="checkbox" name="activities[]" value="Fuerza/Pesas"> 
                <span>
                    <strong>Fuerza/Pesas</strong> <small>(máquinas, pesas libres)</small>
                </span>
            </label>
            <div class="activity-icons">
                <img src="{{ asset('img/medios/iconos/Group.png') }}" alt="pesas">
                <img src="{{ asset('img/medios/iconos/fitness_center.png') }}" alt="gimnasio">
            </div>
        </div>

        <div class="activity-block">
            <label class="checkbox-item activity-label">
                <input type="checkbox" name="activities[]" value="Mixto/HIIT"> 
                <span>
                    <strong>Mixto/HIIT</strong> <small>(crossfit)</small>
                </span>
            </label>
            <div class="activity-icons">
                <img src="{{ asset('img/medios/iconos/sports_handball.png') }}" alt="deporte">
                <img src="{{ asset('img/medios/iconos/sports_kabaddi.png') }}" alt="contacto">
            </div>
        </div>

        <div class="activity-block">
            <label class="checkbox-item activity-label">
                <input type="checkbox" name="activities[]" value="Flexibilidad"> 
                <span>
                    <strong>Flexibilidad</strong> <small>(yoga, estiramientos)</small>
                </span>
            </label>
            <div class="activity-icons">
                <img src="{{ asset('img/medios/iconos/yoga.png') }}" alt="yoga">
                <img src="{{ asset('img/medios/iconos/accessibility_new.png') }}" alt="estirar">
            </div>
        </div>

        <div class="activity-block">
            <label class="checkbox-item activity-label">
                <input type="checkbox" name="activities[]" value="Vida diaria"> 
                <span>
                    <strong>Vida diaria</strong> <small>(limpieza, jardinería)</small>
                </span>
            </label>
            <div class="activity-icons">
                <img src="{{ asset('img/medios/iconos/cleaning_services.png') }}" alt="limpieza">
                <img src="{{ asset('img/medios/iconos/nature_people.png') }}" alt="jardin">
            </div>
        </div>

    </div>

    <div class="form-actions">
        <button type="reset" class="btn-borrar">Borrar</button>
        <button type="submit" class="btn-guardar">Guardar</button>
    </div>

</form>
@endsection

@section('scripts')
<script>
    function updateDuration() {
        const start = document.getElementById('start_time').value;
        const end = document.getElementById('end_time').value;
        if (start && end) {
            const startDate = new Date(`2000-01-01T${start}:00`);
            const endDate = new Date(`2000-01-01T${end}:00`);
            let diff = (endDate - startDate) / 60000;
            if (diff < 0) diff += 1440; // overnight activity
            document.getElementById('duration_text').innerText = diff;
        }
    }
    document.getElementById('start_time').addEventListener('input', updateDuration);
    document.getElementById('end_time').addEventListener('input', updateDuration);
</script>
@endsection
