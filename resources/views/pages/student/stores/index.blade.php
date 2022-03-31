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
                        <th>Product</th>
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
                        <td class="align-middle">
                            <h6 class="fw-bold m-0">{{ $item->name }}</h6>
                            <small>
                                Stock: {{ number_format($item->stock) }} 
                                {{'@'. number_format($item->price) }}
                            </small>
                        </td>
                        <td class="align-middle">
                            <x-view>
                                <x-button outline>
                                    <i class="fas fa-eye"></i>
                                </x-button>
                                <x-button :action="route('stores.store', [$item->id])" class="ms-2">
                                    <i class="fas fa-cart-plus"></i>
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