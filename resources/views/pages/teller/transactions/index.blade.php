<x-layout app>
    <x-layout.section title="Transactions" />
    <x-card class="mb-4">
        <x-card.head>
            <x-text bold color="primary" value="Transactions" />
            <x-form method="GET" class="ms-auto d-none d-md-flex">
                <x-input name="search" placeholder="Search..." value="{{ request()->search ?? '' }}" class="me-2"/>
                <x-button outline type="submit" value="Search" />
            </x-form>
        </x-card.head>
        <x-card.body class="table-responsive" style="min-height: 400px">

            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Sender</th>
                        <th>Receiver</th>
                        <th>Amount</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Requested</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach($transactions as $transaction)
                    @php
                        $page       = $transactions->currentPage();
                        $perPage    = $transactions->perPage();
                        $number     = $loop->iteration + $perPage * ($page-1);
                        $isPending  = $transaction->status === 1;
                        $isDisabled = $isPending ? '' : 'disabled';
                    @endphp

                    <tr>
                        <td class="align-middle">{{ $number }}</td>
                        <td class="align-middle">
                            <h6 class="fw-bold m-0">{{ $transaction->sender->name ?? '-'}}</h6>
                            <small>{{ $transaction->sender->email ?? '' }}</small>
                        </td>
                        <td class="align-middle">
                            <h6 class="fw-bold m-0">{{ $transaction->receiver->name ?? '-' }}</h6>
                            <small>{{ $transaction->receiver->email ?? '' }}</small>
                        </td>
                        <td class="align-middle">{{ $transaction->amount }}</td>
                        <td class="align-middle">{{ $transaction->type_name }}</td>
                        <td class="align-middle">{{ $transaction->status_name }}</td>
                        <td class="align-middle">
                            <h6 class="fw-bold m-0">{{ $transaction->created_at->format('d/m/Y') }}</h6>
                            <small>{{ $transaction->created_at->format('H:i:s') }}
                        </small>
                        <td class="align-middle">
                            <x-view>
                                <x-button color="success" :action="route('transactions.approve', [$transaction->id])" method="GET" class="{{ $isDisabled }}">
                                    <i class="fas fa-check"></i>
                                </x-button>
                                <x-button color="danger" :action="route('transactions.reject', [$transaction->id])" method="GET" class="ms-1 {{ $isDisabled }}">
                                    <i class="fas fa-ban"></i>
                                </x-button>
                            </x-view>
                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>

            {{ $transactions->links() }}
        </x-card.body>
    </x-card>
</x-layout>