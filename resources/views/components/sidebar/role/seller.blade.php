@php
    $transactionNav = [
        'Topup'   => url('/transactions') . '?type=1', 
        'Buying'  => url('/transactions') . '?type=2',
        'Refund'  => url('/transactions') . '?type=3',
    ];
@endphp

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