@php
    use Illuminate\Database\Eloquent\Model;

    $data    = collect($attributes['data']);
    $hidden  = $attributes['hidden'];
    $visible = $attributes['visible'];

    // HIDDEN ATTRIBUTE
    if(gettype($hidden) === 'array') $hidden = collect($hidden);
    else $hidden  = collect(explode('|', $hidden));

    // VISIBLE ATTRIBUTE 
    if(gettype($visible) === 'array') {
        $visible = collect($visible);
        $visible = $visible->mapWithKeys(fn($item, $key) => (
            (gettype($key) === 'integer') ? [$item => null] : [$key => $item]
        ));
    }else {
        $visible = collect(explode('|', $visible));
        $visible = $visible->map(fn($item) => explode(':', $item, 2));
        $visible = $visible->pluck(1, 0);
    }
    
    // DEFAULT HIDDEN
    $hidden->push('id');
    
    // DATA ATTRIBUTE
    $data = $data->map(function($item) use($hidden, $visible){
        $visible = $visible->keys();
        $hidden  = $hidden->reject(fn($item) => $visible->contains($item));

        if($item instanceof Model) $item = $item->makeHidden($hidden->all());
        else $item = (object) collect($item)->except($hidden)->toArray();

        return $item;
    });
    
    $first  = $data->first();
    $keys   = collect($first)->keys();

    unset(
        $attributes['data'],
        $attributes['hidden'],
        $attributes['visible'],
    );
@endphp

<div class="table-responsive">
    <table class="table table-hover {{ $attributes['class'] }}" {{ $attributes }}>
        <thead>
            <tr>
    
                @foreach($keys as $key)
                <th>{{ Str::headline($visible[$key] ?? $key) }}</th>
                @endforeach
    
            </tr>
        </thead>
        <tbody>
            
            @foreach ($data as $item)
            <tr>
    
                @foreach(collect($item)->keys() as $key)
                <td class="align-middle h6">{{ $item->$key }}</td>
                @endforeach
            
            </tr>
            @endforeach
    
        </tbody>
    </table>
</div>