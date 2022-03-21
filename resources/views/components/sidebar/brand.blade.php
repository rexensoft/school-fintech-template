<a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ $route }}">
    <div class="sidebar-brand-icon">
        @if($attributes['img'])
        <img src="{{ $img }}" width="48" height="48" alt=" ">
        @elseif($attributes['icon'])
        <i class="fa-solid {{ $attributes['icon'] }}"></i>
        @endisset
    </div>
    <div class="sidebar-brand-text mx-3">{{ $name }}</div>
</a>