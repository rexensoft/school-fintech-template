@php
    $class = $attributes['class'];
    $value = $attributes['value'];

    $attributes['class'] = "modal-header $class";
    
    unset(
        $attributes['value'],
    );
@endphp

<div {{ $attributes }}>

    @if($slot->isNotEmpty()) {{ $slot }}
    @else <h5 class="modal-title">{{ $value }}</h5>
    @endif
    
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>