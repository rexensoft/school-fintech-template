@php
    use App\Models\{Transaction, User};

    $today = request()->today;
    $user  = new User();
    $trxs  = $today ? Transaction::today() : new Transaction();
@endphp

<x-layout app>
    <x-layout.section title="Dashboard" />
    
    <x-view.row>
        <x-card.info title="Total User" :value="number_format($user->count())" icon="fa-users text-primary" />
        <x-card.info title="Total Topup" :value="number_format($trxs->where('type', 1)->count())" icon="fa-coins text-gray-300" color="warning" />
        <x-card.info title="Total Buying" :value="number_format($trxs->where('type', 2)->count())" icon="fa-cart-shopping text-gray-300" color="success" />
        <x-card.info title="Total Withdraw" :value="number_format($trxs->where('type', 3)->count())" icon="fa-clock-rotate-left text-gray-300" color="danger" />
    </x-view.row>
</x-layout.section>