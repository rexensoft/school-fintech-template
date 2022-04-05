@php
    $dashboardNav   = [
        'All'   => url('/dashboard'),
        'Today' => url('/dashboard') . '?today=true',
    ];

    $transactionNav = [
        'All'       => url('/transactions'), 
        'Pending'   => url('/transactions') . '?status=1', 
        'Paid'      => url('/transactions') . '?status=2',
        'Failed'    => url('/transactions') . '?status=3',
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
    active="{{ Request::is('items') }}"
    icon="fa-box"
    name="Items"
    :route="url('/items')" />