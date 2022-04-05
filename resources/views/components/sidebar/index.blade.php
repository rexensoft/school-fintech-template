@php $role = auth()->user()->role_id; @endphp

<x-sidebar.sidebar theme="dark">
    <x-sidebar.brand 
        {{-- img="{{ asset('assets/img/brand.png') }}" --}}
        icon="fa-store"
        name="COOLPAY"
        route="/dashboard" />
  
    <x-sidebar.divider />

    @if($role === 1) <x-sidebar.role.admin />
    @elseif($role === 2) <x-sidebar.role.seller />
    @elseif($role === 3) <x-sidebar.role.teller />
    @elseif($role === 4) <x-sidebar.role.student />
    @endif
  
    <x-sidebar.divider mb="4"/>
    
    <x-sidebar.toggle/> 
  </x-sidebar.sidebar>