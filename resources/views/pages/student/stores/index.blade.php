<x-layout app>
    <x-layout.section title="Items" />
    <x-card class="mb-4">
        <x-card.head>
            <x-text bold color="primary" value="Items" />
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
                        <th>Name</th>
                        <th>Stock</th>
                        <th>Price</th>
                        <th>Desc</th>
                        {{-- <th>Action</th> --}}
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
                        <td class="align-middle">{{ $item->name }}</td>
                        <td class="align-middle">{{ number_format($item->stock) }}</td>
                        <td class="align-middle">{{ number_format($item->price) }}</td>
                        <td class="align-middle">
                            <textarea
                                class="form-control"
                                readonly
                                >{{ $item->desc }}</textarea>
                        </td>
                        {{-- <td class="align-middle">
                            <x-view>
                                <x-button color="success" :action="url('/cart')">
                                    <i class="fas fa-cart-arrow-down"></i>
                                </x-button>
                            </x-view>
                        </td> --}}
                    </tr>
                    @endforeach

                </tbody>
            </table>

            {{ $items->links() }}
        </x-card.body>
    </x-card>
</x-layout>