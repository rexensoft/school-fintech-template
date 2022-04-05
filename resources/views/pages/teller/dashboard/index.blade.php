@php
    use App\Models\Transaction;

    $today  = request()->today;
    $trx    = $today ? Transaction::today() : new Transaction();
    $topup  = $trx->where('type', 1);
@endphp

<x-layout app>
    <x-layout.section title="Dashboard" />
    
    <x-view.row>
        <x-card.info title="Total Topup Balance" value="{{ number_format($topup->whereIn('status', [2, 3])->sum('amount')) }}" icon="fa-money-bill-wave text-primary" />
        <x-card.info title="Topup Pending" value="{{ number_format($topup->where('status', 1)->count()) }}" icon="fa-clock text-gray-300" color="warning"/>
        <x-card.info title="Topup Paid" value="{{ number_format($topup->where('status', 2)->count()) }}" icon="fa-check-circle text-gray-300" color="success" />
        <x-card.info title="Topup Failed" value="{{ number_format($topup->where('status', 4)->count()) }}" icon="fa-ban text-gray-300" color="danger" />
    </x-view.row>
</x-layout.section>