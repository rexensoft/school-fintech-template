@php
    $tag            = 'p';
    $inline         = false;
    $value          = $attributes['value'];
    $class          = $attributes['class'];
    $italic         = $attributes['italic'];
    $bold           = $attributes['bold'];
    $color          = $attributes['color'];
    $underline      = $attributes['underline'];
    $strikethrough  = $attributes['strikethrough'];
    $decoration     = '';

    if($attributes['href']) $tag            = 'a';
    if($attributes['inline']) $inline       = true;
    if(!$inline) $attributes['class']       = "$class d-block mb-0";
    if($italic) $attributes['class']       .= ' fst-italic';
    if($bold) $attributes['class']         .= ' fw-bold';
    if($color) $attributes['class']        .= " text-$color";
    if($underline) $decoration             .= ' underline';
    if($strikethrough) $decoration         .= ' line-through';
    if($decoration) $attributes['style']    = "text-decoration:$decoration";

    unset(
        $attributes['inline'],
        $attributes['value'],
        $attributes['italic'],
        $attributes['bold'],
        $attributes['underline'],
        $attributes['strikethrough'],
        $attributes['color'],
    );
@endphp

<{{ $tag }} {{ $attributes }}>
    {{ $value ?? $slot }}
</{{ $tag }}>