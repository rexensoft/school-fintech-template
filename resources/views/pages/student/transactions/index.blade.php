<x-layout app>
    <x-layout.section title="Transactions" />
    <x-card class="mb-4">
        <x-card.head>
            <x-text bold color="primary" value="Transactions" />
            <x-button.modal class="ms-3" target="modalTopup" value="Topup"/>
            <x-form method="GET" class="ms-auto d-none d-md-flex">
                <x-input name="search" placeholder="Search..." value="{{ request()->search ?? '' }}" class="me-2"/>
                <x-button outline type="submit" value="Search" />
            </x-form>
        </x-card.head>
        <x-card.body class="table-responsive" style="min-height: 400px">

            <!-- MODAL TOPUP -->
            <x-modal id="modalTopup" title="Topup" :action="route('transactions.topup')">
                <x-modal.body>
                    <x-input type="number" name="amount" label="Amount:" />
                </x-modal.body>
            </x-modal>

            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Code</th>
                        <th>Amount</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Requested</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach($transactions as $transaction)
                    @php
                        $page    = $transactions->currentPage();
                        $perPage = $transactions->perPage();
                        $number  = $loop->iteration + $perPage * ($page-1);
                        $status  = $transaction->status;
                    @endphp

                    <tr>
                        <td class="align-middle">{{ $number }}</td>
                        <td class="align-middle">{{ $transaction->code }}</td>
                        <td class="align-middle">{{ number_format($transaction->amount) }}</td>
                        <td class="align-middle">{{ $transaction->type_name }}</td>
                        <td class="align-middle">
                            <h6 class="fw-bold m-0">{{ $transaction->status_name }}</h6>

                            @if($status !== 1)
                            <small>{{ $transaction->updated_at->format('d/m/Y') }}</small>
                            @endif

                        </td>
                        <td class="align-middle">
                            <h6 class="fw-bold m-0">{{ $transaction->created_at->format('d/m/Y') }}</h6>
                            <small>{{ $transaction->created_at->format('H:i:s') }}
                        </small>
                    </tr>
                    @endforeach

                </tbody>
            </table>

            {{ $transactions->links() }}
        </x-card.body>
    </x-card>
</x-layout>