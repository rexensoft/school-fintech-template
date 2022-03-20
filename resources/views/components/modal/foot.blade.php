@php
    $class = $attributes['class'];
    $cross = $attributes['cross'];
    $value = $attributes['value'];
    $main  = $attributes['main'];

    $attributes['class']             = "modal-footer $class";
    if($main) $attributes['class']  .= " justify-content-$main";
    if($cross) $attributes['class'] .= " align-items-$cross";
    
    unset(
        $attributes['main'],
        $attributes['cross'],
        $attributes['value'],
    );
@endphp

<div class="{{ $attributes['class'] }}" {{ $attributes }}>

   @if ($slot->isNotEmpty()){{ $slot }}
   @else {{ $value }}
   @endif

</div>