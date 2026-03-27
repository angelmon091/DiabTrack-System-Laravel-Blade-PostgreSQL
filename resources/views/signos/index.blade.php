@extends('layouts.app')

@section('title', 'DiabTrack - Dashboard')

@section('content')
    <main class="container-fluid py-4 px-md-5">

        <h1>Registro de Datos</h1>

        <section class="categories">
            <a href="signos.html" class="card">Signos Vitales</a>
            <a href="sintomas.html" class="card">Síntomas</a>
            <a href="nutricion.html" class="card">Nutrición</a>
            <a href="movimiento.html" class="card active-card">Movimiento</a>
        </section>

        <form class="main-content">
            <section class="form-section">
                <div class="input-group">
                    <label>Nivel de Glucosa: <strong>120mg/dL</strong></label>
                    <input type="range" min="40" max="300" value="120">
                </div>

                <div class="input-group">
                    <label>Presión Arterial:</label>
                    <select>
                        <option>Rango (mmHg)</option>
                    </select>
                </div>

                <div class="input-group">
                    <label>Frecuencia Cardiaca:</label>
                    <input type="range" min="40" max="200" value="75">
                </div>

                <div class="input-group">
                    <label>Hemoglobina Glicosilada:</label>
                    <div class="double-select">
                        <select>
                            <option>Lapso de Tiempo</option>
                        </select>
                        <select>
                            <option>% de HbA1c</option>
                        </select>
                    </div>
                </div>
            </section>

            <aside class="timing-panel">
                <h3>Momento de la Medición</h3>
                <div class="timing-grid">
                    <button type="button" class="time-btn active-btn">
                        <img src="img/medios/iconos/wb_sunny.png" alt="sol">
                        <span>Ayunas</span>
                    </button>

                    <button type="button" class="time-btn">
                        <img src="img/medios/iconos/no_meals_ouline.png" alt="sin comida">
                        <span>Antes de Comer</span>
                    </button>

                    <button type="button" class="time-btn">
                        <img src="img/medios/iconos/restaurant.png" alt="cubiertos">
                        <span>Después de Comer</span>
                    </button>

                    <button type="button" class="time-btn">
                        <img src="img/medios/iconos/bedtime.png" alt="luna">
                        <span>Al Dormir</span>
                    </button>
                </div>
            </aside>

            <div class="form-actions">
                <button type="button" class="btn-borrar">Borrar</button>
                <button type="submit" class="btn-guardar">Guardar</button>
            </div>
        </form>

    </main>
@endsection