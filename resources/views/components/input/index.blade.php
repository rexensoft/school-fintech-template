@php
    $id     = $attributes['id'] ?? Str::random();
    $type   = $attributes['type'] ?? 'text';
    $label  = $attributes['label'];

    $attributes['id'] = $id;
@endphp

@if($type === 'checkbox')
    <x-input.checkbox {{ $attributes }} />
@else

    @if($label) 
    <x-input.label :value="$label" :for="$id"/>
    @endif

    <input type="{{ $type }}" 
        class="form-control {{ $attributes['class'] }}"
        {{ $attributes }} />
@endif