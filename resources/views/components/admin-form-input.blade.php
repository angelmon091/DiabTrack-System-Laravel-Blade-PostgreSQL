@props([
    'name',
    'label',
    'type' => 'text',
    'value' => '',
    'placeholder' => '',
    'required' => false,
])

<div class="mb-4">
    <label for="{{ $name }}" class="form-label fw-semibold mb-2">
        {{ $label }} @if($required)<span class="text-danger">*</span>@endif
    </label>
    <input 
        type="{{ $type }}" 
        class="form-control custom-input @error($name) is-invalid @enderror" 
        id="{{ $name }}" 
        name="{{ $name }}" 
        value="{{ old($name, $value) }}" 
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
    >
    @error($name)
        <div class="invalid-feedback mt-2">{{ $message }}</div>
    @enderror
</div>
