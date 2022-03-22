@php
    $transactionNav = [
        'Pending'   => url('/transactions') . '?type=1', 
        'Paid'      => url('/transactions') . '?type=2',
        'Failed'    => url('/transactions') . '?type=3',
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