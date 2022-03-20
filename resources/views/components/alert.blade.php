<div class="alert alert-{{ $color }} border-left-{{ $color }}" role="alert">
    <ul class="pl-4 my-2">
        @foreach ($value->all() as $item)
            <li>{{ $item }}</li>
        @endforeach
    </ul>
</div>