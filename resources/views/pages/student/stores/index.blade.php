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

             <!-- MODAL SHOW PRODUCT -->
             <x-modal id="modalShowProduct" title=" " :action="route('users.import')">
                <x-modal.body>
                    <div class="mb-1">Price: <span id="productPrice"></span></div>
                    <div class="mb-1">Stock: <span id="productStock"></span></div>
                    <div class="mb-3">Seller: <span id="productSeller"></span></div>
                    <x-input.label>Description:</x-input.label>
                    <textarea id="productDesc"  class="form-control" style="height: 100px" readonly></textarea>
                </x-modal.body>
                <x-modal.foot>
                    <x-button outline color="secondary" data-bs-dismiss="modal" value="Close" />
                </x-modal.foot>
            </x-modal>

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
                                <x-button outline :data="$item" data-bs-toggle="modal" data-bs-target="#modalShowProduct">
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

    <x-layout.section scripts>
        <script>
            const onClickHandler = (data, action=null) => {
                const modal   = document.querySelector(`#modalShowProduct`);
                const form    = modal.querySelector('.modal-content form');
                const elmnts  = {
                    title : modal.querySelector('.modal-title'),
                    price : form.querySelector('#productPrice'),
                    stock : form.querySelector('#productStock'),
                    seller: form.querySelector('#productSeller'),
                    desc  : form.querySelector('#productDesc'),
                };

                elmnts.title.innerText  = data.name;
                elmnts.price.innerText  = Intl.NumberFormat('en-EN').format(data.price);
                elmnts.stock.innerText  = Intl.NumberFormat('en-EN').format(data.stock);
                elmnts.seller.innerText = data.seller.name;
                elmnts.desc.value = data.desc;
            }

            document.querySelectorAll('*[data-bs-target="#modalShowProduct"]')
            .forEach(button => button.addEventListener('click', () => {
                const data = JSON.parse(button.getAttribute('data'));
                console.log(data);
                onClickHandler(data);
            }))
        </script>
    </x-layout.section>
</x-layout>