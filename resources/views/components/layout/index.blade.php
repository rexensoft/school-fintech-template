@php
    $keys   = array_keys($attributes->getAttributes());
    $name   = $keys[0] ?? 'app';
    $layout = "layouts.$name"
@endphp
@extends($layout)
@section('content')
    {{ $slot }}
@endsection