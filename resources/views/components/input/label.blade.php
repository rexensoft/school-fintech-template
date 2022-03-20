@php
    $value = $attributes['value'];
    $class = $attributes['class'];

    $attributes['class'] = "form-label $class";

    unset(
        $attributes['value']
    );
@endphp

<label {{ $attributes }}>
    {{ $slot->isNotEmpty() ? $slot : $value }}
</label>