@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'auth-error-list']) }}>
        @foreach ((array) $messages as $message)
            <li>{{ __($message) }}</li>
        @endforeach
    </ul>
@endif
