@php
    $transactionNav = [
        'Topup'   => url('/transactions') . '?type=1', 
        'Buying'  => url('/transactions') . '?type=2',
        'Refund'  => url('/transactions') . '?type=3',
    ];
@endphp

<x-sidebar.sidebar theme="dark">
    <x-sidebar.brand 
      img="{{ asset('assets/img/brand.png') }}"
      name="REXENSOFT"
      route="/dashboard" />
  
    <x-sidebar.divider />
  
    <x-sidebar.item
      active="{{ Request::is('dashboard') }}"
      icon="fa-gauge-high"
      name="Dashboard" 
      :route="url('/dashboard')" />
  
    <x-sidebar.collapse-item
      active="{{ Request::is('coins') }}"
      icon="fa-clipboard-list"
      name="Transactions"
      :routes="$transactionNav" />  
    
    <x-sidebar.item
      active="{{ Request::is('users') }}"
      icon="fa-users"
      name="Users"
      :route="url('/users')" />  
  
    <x-sidebar.divider mb="4"/>
    
    <x-sidebar.toggle/> 
  </x-sidebar.sidebar>