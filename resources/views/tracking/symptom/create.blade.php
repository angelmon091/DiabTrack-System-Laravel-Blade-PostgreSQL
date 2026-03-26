@extends('layouts.app')

@section('title', 'DiabTrack - Registro de Síntomas')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/tracking.css') }}">
@endsection

@section('content')
<div class="tracking-container animate-fade-in">
    <div class="tracking-header">
        <h1>{{ __('Registro de Datos') }}</h1>
        <p class="tracking-subtitle">{{ __('Selecciona los síntomas que presentas hoy') }}</p>
    </div>

    <x-tracking-nav active="sintomas" />

    <form action="{{ route('tracking.symptom.store') }}" method="POST">
        @csrf

        <div class="diab-card p-4 mb-4">
            <div class="tracking-symptoms-grid">
                @php
                    $categoryLabels = [
                        'physical' => ['label' => 'Síntomas Físicos', 'icon' => '🏥'],
                        'nocturnal' => ['label' => 'Síntomas Nocturnos', 'icon' => '🌙'],
                        'neurological' => ['label' => 'Síntomas Neurológicos', 'icon' => '🧠'],
                        'atypical' => ['label' => 'Síntomas Atípicos', 'icon' => '⚠️'],
                    ];
                @endphp

                @forelse($symptoms as $category => $categorySymptoms)
                    <div class="symptom-category">
                        <h3>
                            <span class="cat-icon">{{ $categoryLabels[$category]['icon'] ?? '📋' }}</span>
                            {{ __($categoryLabels[$category]['label'] ?? ucfirst($category)) }}
                        </h3>
                        @foreach($categorySymptoms as $symptom)
                            <label class="tracking-checkbox">
                                <input type="checkbox" name="symptoms[]" value="{{ $symptom->id }}"
                                    {{ is_array(old('symptoms')) && in_array($symptom->id, old('symptoms')) ? 'checked' : '' }}>
                                {{ $symptom->name }}
                            </label>
                        @endforeach
                    </div>
                @empty
                    <div class="text-center py-5 w-100">
                        <i class="fa-solid fa-clipboard-list text-diab-text-muted" style="font-size: 3rem;"></i>
                        <p class="text-diab-text-secondary fs-6 mt-3">{{ __('No hay síntomas configurados. Contacte al administrador.') }}</p>
                    </div>
                @endforelse
            </div>
        </div>

        <x-input-error :messages="$errors->get('symptoms')" />

        <div class="tracking-actions">
            <button type="reset" class="btn-track-reset">{{ __('Borrar') }}</button>
            <button type="submit" class="btn-track-save">{{ __('Guardar') }}</button>
        </div>
    </form>
</div>
@endsection
