@php
    $keys   = array_keys($attributes->getAttributes());
    $name   = $keys[0] ?? null;
    $value  = $attributes[$name] ?? $slot;
@endphp

@if($name)
    @if($slot->isNotEmpty())
        @push($name)
            {{ $slot }}
        @endpush
    @else
        @push($name, $value)
    @endif
@endif