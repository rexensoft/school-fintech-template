@php $role = auth()->user()->role_id @endphp

@if($role === 2) @include('pages.seller.items.index')
@elseif($role === 4) @include('pages.student.stores.index')
@endif