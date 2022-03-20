@php
    $keys   = array_keys($attributes->getAttributes());
    $name   = $keys[0] ?? null;
    $value  = $attributes[$name] ?? $slot;
@endphp

@if($name)
    @if($slot->isNotEmpty())
        @section($name)
            {{ $slot }}
        @endsection
    @else
        @section($name, $value)
    @endif
@endif