@php
    $class  = $attributes['class'];
    $title  = $attributes['title'];
    $action = $attributes['action'];
    $method = $attributes['method'];

    $attributes['class'] = "modal fade $class";

    unset(
        $attributes['title'],
        $attributes['action'],
        $attributes['method'],
    );
@endphp

<div tabindex="-1" aria-hidden="true" {{ $attributes }}>
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">

        @if($title)
            <x-modal.head :value="$title" />
        @endif

        @if($action)
            <x-form :action="$action" :method="$method">

                {{ $slot }}
                
                @if(!str_contains($slot, '<div class="modal-footer'))
                <x-modal.foot>
                    <x-button outline color="secondary" data-bs-dismiss="modal" value="Close" />
                    <x-button type="submit" color="primary" value="Save" />
                </x-modal.foot>
                @endif

            </x-form>
        @else
            {{ $slot }}
        @endif
        
      </div>
    </div>
</div>