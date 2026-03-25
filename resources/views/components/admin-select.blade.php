@props([
    'name',
    'label',
    'options' => [],
    'selected' => [],
    'multiple' => false,
])

<div class="mb-4">
    <label for="{{ $name }}" class="form-label fw-semibold mb-2">{{ $label }}</label>
    <select 
        class="form-select custom-select @error($name) is-invalid @enderror" 
        id="{{ $name }}" 
        name="{{ $name }}{{ $multiple ? '[]' : '' }}" 
        {{ $multiple ? 'multiple' : '' }}
    >
        @if(!$multiple)
            <option value="">Selecciona una opción</option>
        @endif
        
        @foreach($options as $value => $text)
            <option value="{{ $value }}" {{ in_array($value, (array)$selected) ? 'selected' : '' }}>
                {{ $text }}
            </option>
        @endforeach
    </select>
    @error($name)
        <div class="invalid-feedback mt-2">{{ $message }}</div>
    @enderror
</div>
