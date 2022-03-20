@php $role = auth()->user()->role_id @endphp

@if($role === 1) @include('pages.admin.transactions.index')
@elseif($role === 2) @include('pages.seller.transactions.index')
@elseif($role === 3) @include('pages.teller.transactions.index')
@elseif($role === 4) @include('pages.student.transactions.index')
@endif