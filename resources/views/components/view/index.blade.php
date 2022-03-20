@php
    $main       = $attributes['main'];
    $cross      = $attributes['cross'];
    $class      = $attributes['class'];
    $style      = $attributes['style'];
    $direction  = $attributes['direction'];
    
    $attributes['class']                     = "d-flex $class";
    if($main) $attributes['class']          .= " justify-content-$main";
    if($cross) $attributes['class']         .= " align-items-$cross";
    if($direction) $attributes['class']     .= " flex-$direction";
    if(
        !str_contains($style, 'width') &&
        !str_contains($class, 'w-')
    ) $attributes['class']                  .= " flex-grow-1";

    unset(
        $attributes['main'],
        $attributes['cross'],
        $attributes['direction']
    );
@endphp

<div {{ $attributes }}>
    {{ $slot }}
</div>