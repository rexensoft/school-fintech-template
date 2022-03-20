@php
    $method = strtoupper($method);
    $isGet  = $method === 'GET';
    $inline = $attributes['inline'];

    if($inline) $attributes['class'] .= ' form-inline';

    unset(
        $attributes['inline'],
    );
@endphp

<form action="{{ $action }}" method="{{ $isGet ? 'GET' : 'POST' }}" enctype="multipart/form-data" {{ $attributes }}>
    @if(!$isGet) 
        @method($method) 
        @csrf
    @endif

    {{ $slot }}
</form>