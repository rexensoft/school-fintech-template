@php
    $class = $attributes['class'];
    $value = $attributes['value'];

    $attributes['class'] = "modal-body $class";

    unset(
        $attributes['value'],
    );
@endphp

<div {{ $attributes }}>
    {{ $slot ?? $value }}
</div>