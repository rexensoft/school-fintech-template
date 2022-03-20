@php
    $class = $attributes['class'];
    $theme = $attributes['theme'] ?? 'dark';

    $attributes['id']    = 'accordionSidebar';
    $attributes['class'] = "navbar-nav bg-gradient-primary sidebar sidebar-$theme accordion toggled";

    unset(
        $attributes['theme'],
    );
@endphp

<ul {{ $attributes }}>
    {{ $slot }}
</ul>