@props(['active' => 'signos'])

<section class="tracking-tabs">
    <a href="{{ route('tracking.vital.create') }}" class="tracking-tab {{ $active == 'signos' ? 'active' : '' }}">
        {{ __('Signos Vitales') }}
    </a>
    <a href="{{ route('tracking.symptom.create') }}" class="tracking-tab {{ $active == 'sintomas' ? 'active' : '' }}">
        {{ __('Síntomas') }}
    </a>
    <a href="{{ route('tracking.nutrition.create') }}" class="tracking-tab {{ $active == 'nutricion' ? 'active' : '' }}">
        {{ __('Nutrición') }}
    </a>
    <a href="{{ route('tracking.activity.create') }}" class="tracking-tab {{ $active == 'movimiento' ? 'active' : '' }}">
        {{ __('Movimiento') }}
    </a>
</section>
