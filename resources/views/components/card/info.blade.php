@php
    $class = $attributes['class'];
    $color = $attributes['color'] ?? 'primary';
    $icon  = $attributes['icon'];
    $title = $attributes['title'];
    $value = $attributes['value'];

    $attributes['class'] = "col-xl-3 col-md-6 mb-4 $class";

    if(!str_contains($icon, 'text-')) $icon .= ' text-gray-300';
@endphp

<div {{ $attributes }}>
    <x-card class="border-left-{{ $color }} shadow h-100 py-2 rounded-3">
        <x-card.body>
            <div class="row g-0 align-items-center">
                <div class="col me-2">
                    <div class="text-xs fw-bold text-{{ $color }} text-uppercase mb-1">
                        {{ $title }}    
                    </div>
                    <div class="h5 mb-0 fw-bold text-gray-800">{{ $value }}</div>
                </div>
                <div class="col-auto">
                    <i class="fa-solid {{ $icon }} fa-2x"></i>
                </div>
            </div>
        </x-card.body>
    </x-card>
</div>