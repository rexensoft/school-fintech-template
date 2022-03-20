<li class="nav-item {{ $active }}">
    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseUtilities{{ Str::replace(' ', '', $name) }}"
        aria-expanded="true" aria-controls="collapseUtilities">
        <i class="fa-solid fa-fw {{ $icon }}"></i>
        <span>{{ $name }}</span>
    </a>
    <div id="collapseUtilities{{ Str::replace(' ', '', $name) }}" class="collapse" aria-labelledby="headingUtilities"
        data-bs-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">

            @foreach ($routes as $key => $route)
            <a class="collapse-item" href="{{ $route }}">{{ $key }}</a>
            @endforeach

        </div>
    </div>
</li>