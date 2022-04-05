@php
    $user = auth()->user();
    $trxs = \App\Models\Transaction::where('sender_id', $user->id)
        ->orWhere('receiver_id', $user->id);
@endphp

<x-layout app>
    <x-layout.section title="Dashboard" />
    
    <x-view.row>
        <x-card.info title="Balance"      value="Rp {{ number_format($user->balance) }}" icon="fa-money-bills-wave text-primary" />
        <x-card.info title="Total Topup"  value="{{ number_format($trxs->where('type', 1)->count()) }}" icon="fa-clock text-gray-300" color="warning"/>
        <x-card.info title="Total Buying" value="{{ number_format($trxs->where('type', 2)->count()) }}" icon="fa-check-circle text-gray-300" color="success" />
        <x-card.info title="Total Refund" value="{{ number_format($trxs->where('type', 3)->count()) }}" icon="fa-ban text-gray-300" color="danger" />
    </x-view.row>
</x-layout.section>