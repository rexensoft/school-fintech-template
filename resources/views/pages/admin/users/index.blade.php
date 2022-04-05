<x-layout app>
    <x-layout.section title="Users" />
    <x-card class="mb-4">
        <x-card.head>
            <x-text bold color="primary" value="Users" />
            <x-button.modal class="ms-3" target="modalAddUser"><i class="fas fa-user-plus"></i></x-button.modal>
            <x-button.modal outline class="ms-2" target="modalImportUser" title="Import"><i class="fas fa-file-import"></i></x-button.modal>
            <x-button outline class="ms-2" :action="route('users.export')" method="GET" title="Export"><i class="fa-solid fa-file-export"></i></x-button>
            <x-form method="GET" class="ms-auto d-none d-md-flex">
                <x-input name="search" placeholder="Search..." value="{{ request()->search ?? '' }}" class="me-2"/>
                <x-button outline type="submit" value="Search" />
            </x-form>
        </x-card.head>
        <x-card.body class="table-responsive" style="min-height: 400px">
            
            <!-- MODAL IMPORT USER -->
            <x-modal id="modalImportUser" title="Import User" :action="route('users.import')">
                <x-modal.body>
                    <x-input type="file" name="file" class="mb-3" accept=".xlsx"/>
                </x-modal.body>
            </x-modal>

            <!-- MODAL ADD USER -->
            <x-modal id="modalAddUser" title="Add User" :action="route('users.store')">
                <x-modal.body>
                    <x-input type="text" name="name" label="Name:" class="mb-3" />
                    <x-input type="email" name="email" label="Email:" class="mb-3" />
                    <x-input.label value="Role:" />
                    <select name="role_id" class="form-control form-select mb-3">

                        @foreach($roles as $role)
                        <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                        @endforeach

                      </select>
                    <x-input value="password" label="Default Password:" class="mb-3" readonly />
                </x-modal.body>
            </x-modal>
            
            <!-- MODAL EDIT USER -->
            <x-modal id="modalEditUser" title="Edit User" action=" " method="PUT">
                <x-modal.body>
                    <x-input type="text" name="name" label="Name:" class="mb-3" />
                    <x-input type="text" name="email" label="Email:" class="mb-3" />
                    <x-input.label value="Role:" />
                    <select name="role_id" class="form-control form-select mb-3">

                        @foreach($roles as $role)
                        <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                        @endforeach

                      </select>
                </x-modal.body>
            </x-modal>

            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach($users as $user)
                    @php
                        $page    = $users->currentPage();
                        $perPage = $users->perPage();
                        $number  = $loop->iteration + $perPage * ($page-1);
                    @endphp

                    <tr>
                        <td class="align-middle">{{ $number }}</td>
                        <td class="align-middle">{{ $user->id }}</td>
                        <td class="align-middle">{{ $user->name }}</td>
                        <td class="align-middle">{{ $user->email }}</td>
                        <td class="align-middle">{{ ucfirst($user->role->name) }}</td>
                        <td class="align-middle">
                            <x-view>
                                <x-button color="danger" :action="route('users.destroy', [$user->id])" method="DELETE">
                                    <i class="fas fa-trash"></i>
                                </x-button>
                                <x-button.modal color="warning" :data="$user" :action="route('users.update', [$user->id])" class="ms-1 text-white" target="modalEditUser">
                                    <i class="fas fa-pencil"></i>
                                </x-button.modal>
                            </x-view>
                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>

            {{ $users->links() }}
        </x-card.body>
    </x-card>
</x-layout>