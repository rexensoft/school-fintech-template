@php
    $balance   = auth()->user()->balance;
    $totalPaid = $items->sum(fn($item)=>  $item->price * $item->total);
@endphp

<x-layout app>
    <x-layout.section title="Carts" />
    <x-card class="mb-4">
        <x-card.head>
            <x-text bold color="primary" value="Carts" />
            <x-button.modal class="ms-3" target="modalCheckout" value="Checkout"/>
            <x-form method="GET" class="ms-auto d-none d-md-flex">
                <x-input name="search" placeholder="Search..." value="{{ request()->search ?? '' }}" class="me-2"/>
                <x-button outline type="submit" value="Search" />
            </x-form>
        </x-card.head>
        <x-card.body class="table-responsive" style="min-height: 400px">
            
            <!-- MODAL CONFIRM CHECKOUT -->
            <x-modal id="modalCheckout" title="Checkout" :action="route('carts.checkout')">
                <x-modal.body>
                    <x-text>Saldo: {{ number_format($balance) }}</x-text>
                    <x-text>Dibayar: {{ number_format($totalPaid) }}</x-text>

                    @if($balance > $totalPaid)
                    <x-text bold class="mt-3">Dibayar: {{ number_format($totalPaid) }}</x-text>
                    <x-text class="mb-3">
                        Change: {{ number_format($balance - $totalPaid) }} ({{ number_format($balance) }} - {{ number_format($totalPaid) }})
                    </x-text>
                    <x-text value="Are you sure to checkout?" />
                    @else
                    <x-text bold class="mb-3 mt-3">Balance not enough</x-text>
                    @endif

                </x-modal.body>
                <x-modal.foot>
                    <x-button type="submit" value="Checkout" class="{{ $items->count() && $balance > $totalPaid ? '' : 'disabled' }}"/>
                </x-modal.foot>
            </x-modal>

            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Poduct</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    
                    @foreach($items as $item)
                    @php
                        $page    = $items->currentPage();
                        $perPage = $items->perPage();
                        $number  = $loop->iteration + $perPage * ($page-1);
                    @endphp

                    <tr>
                        <td class="align-middle">{{ $number }}</td>
                        <td class="align-middle">
                            <h6 class="fw-bold m-0">{{ $item->name }}</h6>
                            <small>
                                Stock: {{ number_format($item->stock) }} 
                            </small>
                        </td>
                        <td class="align-middle">
                            <h6 class="fw-bold m-0">{{ number_format($item->total * $item->price) }}</h6>
                            <small>{{ $item->total }} x {{ number_format($item->price) }}</small>
                        </td>
                        <td class="align-middle">
                            <x-view>
                                <x-button color="danger" :action="route('carts.destroy', [$item->id])" method="DELETE">
                                    <i class="fas fa-trash"></i>
                                </x-button>
                            </x-view>
                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>

            {{ $items->links() }}
        </x-card.body>
    </x-card>
</x-layout>