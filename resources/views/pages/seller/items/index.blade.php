<x-layout app>
    <x-layout.section title="Items" />
    <x-card class="mb-4">
        <x-card.head>
            <x-text bold color="primary" value="Items" />
            <x-button.modal class="ms-3" target="modalAddItem" value="Add Item"/>
            <x-form method="GET" class="ms-auto d-none d-md-flex">
                <x-input name="search" placeholder="Search..." value="{{ request()->search ?? '' }}" class="me-2"/>
                <x-button outline type="submit" value="Search" />
            </x-form>
        </x-card.head>
        <x-card.body class="table-responsive" style="min-height: 400px">
            
            <!-- MODAL ADD ITEM -->
            <x-modal id="modalAddItem" title="Add Item" :action="route('items.store')">
                <x-modal.body>
                    <x-input type="text" name="name" label="Name:" class="mb-3" />
                    <x-input type="number" name="stock" label="Stock:" class="mb-3" />
                    <x-input type="number" name="price" label="Price:" class="mb-3" />
                    <x-input.label value="Description:" />
                    <textarea name="desc" class="form-control mb-3" style="height: 80px"></textarea>
                </x-modal.body>
            </x-modal>
            
            <!-- MODAL EDIT ITEM -->
            <x-modal id="modalEditItem" title="Edit Item" action=" " method="PUT">
                <x-modal.body>
                    <x-input type="text" name="name" label="Name:" class="mb-3" />
                    <x-input type="number" name="stock" label="Stock:" class="mb-3" />
                    <x-input type="number" name="price" label="Price:" class="mb-3" />
                    <x-input.label value="Description:"/>
                    <textarea name="desc" class="form-control mb-3" style="height: 80px"></textarea>
                </x-modal.body>
            </x-modal>

            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Stock</th>
                        <th>Price</th>
                        <th>Desc</th>
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
                        <td class="align-middle">{{ $item->name }}</td>
                        <td class="align-middle">{{ $item->stock }}</td>
                        <td class="align-middle">{{ $item->price }}</td>
                        <td class="align-middle">
                            <textarea
                                class="form-control"
                                readonly
                                >{{ $item->desc }}</textarea>
                        </td>
                        <td class="align-middle">
                            <x-view>
                                <x-button color="danger" :action="route('items.destroy', [$item->id])" method="DELETE">
                                    <i class="fas fa-trash"></i>
                                </x-button>
                                <x-button.modal color="warning" :data="$item" :action="route('items.update', [$item->id])" class="ms-1 text-white" target="modalEditItem">
                                    <i class="fas fa-pencil"></i>
                                </x-button.modal>
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