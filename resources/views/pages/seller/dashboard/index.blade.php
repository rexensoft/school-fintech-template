@php
    use App\Models\{Item, Transaction};

    $today = request()->today;
    $user  = auth()->user();
    $items = Item::where('seller_id', $user->id);
    $trxs  = $today ? Transaction::today() : new Transaction();
    $trxs  = $trxs->where('receiver_id', $user->id)
        ->where('type', 2)->get();
@endphp

<x-layout app>
    <x-layout.section title="Dashboard" />
    
    <x-view.row>
        <x-card.info title="Balance" value="Rp {{ number_format($user->balance) }}" icon="fa-money-bill-wave text-primary" />
        <x-card.info title="Total Item" value="{{ number_format($items->count()) }}" icon="fa-box text-gray-300" color="info" />
        <x-card.info title="Transaction Pending" value="{{ number_format($trxs->where('status', 1)->count()) }}" icon="fa-clock text-gray-300" color="warning" />
        <x-card.info title="Transaction Paid" value="{{ number_format($trxs->where('status', 2)->count()) }}" icon="fa-cart-shopping text-gray-300" color="success" />
    </x-view.row>
</x-layout.section>