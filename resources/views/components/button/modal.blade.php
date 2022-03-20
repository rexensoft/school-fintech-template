@php
    use Illuminate\Database\Eloquent\Model;

    $action = $attributes['action'];
    $target = $attributes['target'];
    $data   = $attributes['data'] ?? [];
    $id     = $attributes['id'] ??  "button-".Str::random();

    $attributes['id']   = $id;
    $attributes['type'] = 'button';

    if($data instanceof Model) $data = $data->toArray();

    unset(
        $attributes['action'],
        $attributes['target'],
        $attributes['type'],
        $attributes['data'],
    );
@endphp

<x-button data-bs-toggle="modal" data-bs-target="#{{ $target }}" {{ $attributes }}>
    {{ $value ?? $slot }}
</x-button>

<x-layout.section scripts>
    <script>
        const onClickHandler = (data, target, action=null) => {
            const modal   = document.querySelector(`#${target}`);
            const form    = modal.querySelector('.modal-content form');
            const inputs  = Object.keys(data).map((item) => {
                const elmnt  = modal.querySelector(`*[name=${item}]`);
                return [item, elmnt || null];
            }).filter(item => item[1]);

            if(action) form.action = action;

            inputs.forEach(item => item[1].value = data[item[0]]);
        }
    </script>
</x-layout.section>

<x-layout.push scripts>
    <script>
        document.querySelector('#{{ $id }}').addEventListener('click', () => {
            onClickHandler({{ Js::from($data) }}, '{{ $target }}', '{{ $action }}');
        });
    </script>
</x-layout.push>