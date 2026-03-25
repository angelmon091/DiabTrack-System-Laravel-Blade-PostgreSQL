@props([
    'type' => 'submit',
    'variant' => 'primary', // primary, secondary, danger
    'size' => '', // sm, lg, ''
    'icon' => null,
])

@php
$baseClasses = 'btn custom-btn d-inline-flex align-items-center justify-content-center fw-semibold rounded-3 transition-all';

$variantClasses = [
    'primary' => 'btn-primary custom-btn-primary',
    'secondary' => 'btn-outline-secondary custom-btn-secondary',
    'danger' => 'btn-danger custom-btn-danger',
][$variant] ?? 'btn-primary custom-btn-primary';

$sizeClass = $size ? "btn-{$size}" : '';
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => "{$baseClasses} {$variantClasses} {$sizeClass}"]) }}>
    @if($icon)
        <i class="fa-solid fa-{{ $icon }} me-2"></i>
    @endif
    {{ $slot }}
</button>
