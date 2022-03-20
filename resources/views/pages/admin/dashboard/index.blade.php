@php
    $user = new App\Models\User();
    $trx  = new App\Models\Transaction();
@endphp

<x-layout app>
    <x-layout.section title="Dashboard" />
    
    <x-view.row>
        <x-card.info title="Total User" :value="$user->count()" icon="fa-users text-primary" />
        <x-card.info title="Total Topup" :value="$trx->where('type', 1)->count()" icon="fa-coins text-gray-300" color="warning" />
        <x-card.info title="Total Buying" :value="$trx->where('type', 2)->count()" icon="fa-cart-shopping text-gray-300" color="success" />
        <x-card.info title="Total Refund" :value="$trx->where('type', 3)->count()" icon="fa-clock-rotate-left text-gray-300" color="danger" />
    </x-view.row>
</x-layout.section>