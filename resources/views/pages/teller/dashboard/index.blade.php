@php
    $trx    = new App\Models\Transaction();
    $topup  = $trx->where('type', 1);
@endphp

<x-layout app>
    <x-layout.section title="Dashboard" />
    
    <x-view.row>
        <x-card.info title="Total Topup"    value="{{ $topup->count() }}" icon="fa-money-bill-wave text-primary" />
        <x-card.info title="Topup Pending"  value="{{ $topup->where('status', 1)->count() }}" icon="fa-clock text-gray-300" color="warning"/>
        <x-card.info title="Topup Paid"     value="{{ $topup->where('status', 2)->count() }}" icon="fa-check-circle text-gray-300" color="success" />
        <x-card.info title="Topup Failed"   value="{{ $topup->where('status', 4)->count() }}" icon="fa-ban text-gray-300" color="danger" />
    </x-view.row>
</x-layout.section>