@php $role = auth()->user()->role_id @endphp

@if($role === 1) @include('pages.admin.dashboard.index')
@elseif($role === 2) @include('pages.seller.dashboard.index')
@elseif($role === 3) @include('pages.teller.dashboard.index')
@elseif($role === 4) @include('pages.student.dashboard.index')
@endif