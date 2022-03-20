@php
    $class = $attributes['class'];
    $attributes['class'] = "row $class";
@endphp

<div {{ $attributes }}>
    {{ $slot }}
</div>