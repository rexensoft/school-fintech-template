@php
    $dashboardNav   = [
        'All'   => url('/dashboard'),
        'Today' => url('/dashboard') . '?today=true',
    ];

    $transactionNav = [
        'All'       => url('/transactions'), 
        'Topup'     => url('/transactions') . '?type=1', 
        'Buying'    => url('/transactions') . '?type=2',
        'Refund'    => url('/transactions') . '?type=3',
    ];
@endphp

<x-sidebar.collapse-item
    active="{{ Request::is('dashboard') }}"
    icon="fa-gauge-high"
    name="Dashboard" 
    :routes="$dashboardNav" />

<x-sidebar.collapse-item
    active="{{ Request::is('transactions') }}"
    icon="fa-clipboard-list"
    name="Transactions"
    :routes="$transactionNav" />  
    
<x-sidebar.item
    active="{{ Request::is('users') }}"
    icon="fa-users"
    name="Users"
    :route="url('/users')" />  