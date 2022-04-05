@php
    $transactionNav = [
        'All'     => url('/transactions'),
        'Topup'   => url('/transactions') . '?type=1',
        'Buying'  => url('/transactions') . '?type=2',
        'Refund'  => url('/transactions') . '?type=3',
    ];
@endphp

<x-sidebar.item
    active="{{ Request::is('dashboard') }}"
    icon="fa-gauge-high"
    name="Dashboard" 
    :route="url('/dashboard')" />

<x-sidebar.collapse-item
    active="{{ Request::is('transactions') }}"
    icon="fa-clipboard-list"
    name="Transactions"
    :routes="$transactionNav" />

<x-sidebar.item
    active="{{ Request::is('stores') }}"
    icon="fa-shop"
    name="Store"
    :route="url('/stores')" />

<x-sidebar.item
    active="{{ Request::is('carts') }}"
    icon="fa-cart-shopping"
    name="Carts"
    :route="url('/carts')" />