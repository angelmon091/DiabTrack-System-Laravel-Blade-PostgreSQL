@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'auth-status auth-status--success']) }}>
        {{ __($status) }}
    </div>
@endif
