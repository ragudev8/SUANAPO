@props([
    'name',
    'items' => [],
    'value' => null,
    'placeholder' => 'Escriba para buscar...',
    'required' => false,
    'emptyLabel' => 'Sin asignar',
])

@php
    $currentValue = old($name, $value);
    $selected = collect($items)->firstWhere('value', (string) $currentValue) ?? collect($items)->firstWhere('value', $currentValue);
    $inputId = 'search-select-'.str_replace(['[', ']'], '-', $name).'-'.uniqid();
@endphp

<div class="search-select" data-search-select data-required="{{ $required ? '1' : '0' }}" data-items='@json(array_values($items))'>
    <input type="hidden" name="{{ $name }}" value="{{ $currentValue }}" @required($required)>
    <div class="position-relative">
        <input
            id="{{ $inputId }}"
            type="text"
            class="form-control search-select-input"
            autocomplete="off"
            value="{{ $selected['label'] ?? '' }}"
            placeholder="{{ $placeholder }}"
            @required($required)
        >
        <button class="search-select-clear" type="button" aria-label="Limpiar">&times;</button>
        <div class="search-select-menu shadow-sm" hidden>
            @unless ($required)
                <button type="button" class="search-select-option" data-value="" data-label="{{ $emptyLabel }}">{{ $emptyLabel }}</button>
            @endunless
        </div>
    </div>
</div>
