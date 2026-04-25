@extends('layouts.app')

@section('title', 'DiabTrack - Registro de Nutrición')

@section('styles')
    @vite('resources/css/tracking.css')
@endsection

@section('content')
<div class="tracking-container animate-fade-in">
    <div class="tracking-header">
        <h1>{{ __('Registro de Nutrición') }}</h1>
        <p class="tracking-subtitle">{{ __('Registra tu alimentación y medicación') }}</p>
    </div>

    <x-tracking-nav active="nutricion" />

    <form class="tracking-form-layout" action="{{ route('tracking.nutrition.store') }}" method="POST">
        @csrf

        <section class="tracking-form-main">
            <div class="diab-card p-4 mb-4">
                <div class="tracking-field">
                    <label class="d-flex justify-content-between align-items-center w-100 mb-3">
                        <span>{{ __('Carbohidratos') }}: <strong id="carbs_val">{{ old('carbs_grams', 50) }}</strong>g</span>
                        <i class="fa-solid fa-circle-info info-icon opacity-50 text-muted" data-bs-toggle="tooltip" title="La cantidad de comida que sube tu azúcar (ej. tortillas, pan, arroz, fruta, dulces). Puedes ver esto en las etiquetas de lo que comes."></i>
                    </label>
                    <input type="range" name="carbs_grams" class="tracking-range" min="0" max="300" value="{{ old('carbs_grams', 50) }}" oninput="document.getElementById('carbs_val').innerText = this.value">
                    <x-input-error :messages="$errors->get('carbs_grams')" />
                </div>

                <div class="tracking-field">
                    <label>{{ __('Hora de Consumo') }}:</label>
                    <input type="time" name="consumed_at" class="tracking-input" value="{{ old('consumed_at') }}">
                    <x-input-error :messages="$errors->get('consumed_at')" />
                </div>

                <div class="tracking-field" style="margin-bottom: 0;">
                    <label class="d-flex justify-content-between align-items-center w-100 mb-3">
                        <span>{{ __('Categorías de Alimentos') }}:</span>
                        <i class="fa-solid fa-circle-info info-icon opacity-50 text-muted" data-bs-toggle="tooltip" title="Marca los tipos de comida que acabas de ingerir. Es útil para ver cómo reacciona tu cuerpo."></i>
                    </label>
                    @php
                        $foodCategories = [
                            'frutas' => 'Frutas',
                            'verduras' => 'Verduras',
                            'cereales' => 'Cereales / Granos',
                            'proteinas' => 'Proteínas',
                            'lacteos' => 'Lácteos',
                            'grasas' => 'Grasas Saludables',
                            'azucares' => 'Azúcares / Dulces',
                            'bebidas' => 'Bebidas',
                        ];
                    @endphp
                    @foreach($foodCategories as $value => $label)
                        <label class="tracking-checkbox">
                            <input type="checkbox" name="food_categories[]" value="{{ $value }}"
                                {{ is_array(old('food_categories')) && in_array($value, old('food_categories')) ? 'checked' : '' }}>
                            {{ __($label) }}
                        </label>
                    @endforeach
                    <x-input-error :messages="$errors->get('food_categories')" />
                </div>
            </div>
        </section>

        <aside class="tracking-form-aside">
            <div class="tracking-panel">
                <h3>{{ __('Tipo de Comida') }}</h3>
                <input type="hidden" name="meal_type" id="meal_type" value="{{ old('meal_type', 'desayuno') }}">

                <div class="selector-grid" id="meal-grid">
                    @php
                        $mealTypes = [
                            ['id' => 'desayuno', 'label' => 'Desayuno', 'icon' => 'fa-solid fa-sun'],
                            ['id' => 'almuerzo', 'label' => 'Almuerzo', 'icon' => 'fa-solid fa-cloud-sun'],
                            ['id' => 'cena', 'label' => 'Cena', 'icon' => 'fa-solid fa-moon'],
                            ['id' => 'snack', 'label' => 'Snack', 'icon' => 'fa-solid fa-apple-whole'],
                            ['id' => 'correccion', 'label' => 'Corrección', 'icon' => 'fa-solid fa-pills'],
                        ];
                    @endphp
                    @foreach($mealTypes as $meal)
                        <button type="button" 
                                class="selector-btn {{ old('meal_type', 'desayuno') == $meal['id'] ? 'active' : '' }}" 
                                onclick="setMealType('{{ $meal['id'] }}', this)">
                            <span class="selector-emoji"><i class="{{ $meal['icon'] }}"></i></span>
                            <span>{{ __($meal['label']) }}</span>
                        </button>
                    @endforeach
                </div>
                <x-input-error :messages="$errors->get('meal_type')" />

                <h3 class="mt-4 d-flex justify-content-between align-items-center w-100">
                    <span>{{ __('Medicación') }} <span class="text-muted fw-normal" style="font-size: 0.8rem;">(Opcional)</span></span>
                    <i class="fa-solid fa-circle-info info-icon opacity-50 text-muted fs-6" data-bs-toggle="tooltip" title="Anota si tomaste alguna pastilla (ej. Metformina) o si te inyectaste Insulina."></i>
                </h3>
                <div class="mt-3">
                    <div class="tracking-field">
                        <label class="tracking-field-label">{{ __('Medicamento') }}</label>
                        <input type="text" name="medication_taken" class="tracking-input" placeholder="{{ __('Ej: Insulina, Metformina...') }}" value="{{ old('medication_taken') }}">
                    </div>
                    <div class="tracking-field" style="margin-bottom: 0;">
                        <label class="tracking-field-label">{{ __('Dosis') }}</label>
                        <input type="text" name="medication_dose" class="tracking-input" placeholder="{{ __('Ej: 10 unidades, 500mg...') }}" value="{{ old('medication_dose') }}">
                    </div>
                </div>
            </div>
        </aside>

        <div class="tracking-actions">
            <button type="reset" class="btn-track-reset">{{ __('Borrar') }}</button>
            <button type="submit" class="btn-track-save">{{ __('Guardar') }}</button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    function setMealType(val, btn) {
        document.getElementById('meal_type').value = val;
        document.getElementById('meal-grid').querySelectorAll('.selector-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
    }
</script>
@endsection
