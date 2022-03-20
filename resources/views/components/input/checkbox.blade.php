@php
    $id =  $attributes['id'] ?? Str::uuid();
@endphp

<div class="form-check {{ $attributes['class'] }}">
    <input type="checkbox"
        id="{{ $id }}"
        class="form-check-input"
        name="{{ $attributes['name'] }}"
        {{ $attributes['checked'] }}>
    <label class="form-check-label" for="{{ $id }}">{{ __($attributes['label']) }}</label>
</div>