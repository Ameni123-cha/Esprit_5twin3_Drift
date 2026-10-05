@props([
    'label',
    'name',
    'type' => 'text',
    'value' => '',
    'required' => false,
    'step' => null,
    'min' => null,
    'max' => null,
])

<div>
    <x-input-label :for="$name" :value="$label" />
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{{ $value }}"
        @if($required) required @endif
        @if($step !== null) step="{{ $step }}" @endif
        @if($min !== null) min="{{ $min }}" @endif
        @if($max !== null) max="{{ $max }}" @endif
        {{ $attributes->merge(['class' => 'tv-input mt-1']) }}
    >
    <x-input-error :messages="$errors->get($name)" class="mt-1" />
</div>
