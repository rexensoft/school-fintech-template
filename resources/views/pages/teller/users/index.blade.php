<x-layout app>
    <x-layout.section title="Users" />
    <x-card class="mb-4">
        <x-card.head>
            <x-text bold color="primary" value="Users" />
            <x-button outline class="ms-2" :action="route('users.export')" method="GET" title="Export"><i class="fas fa-file-export"></i></x-button>
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
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
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
                    </tr>
                    @endforeach

                </tbody>
            </table>

            {{ $users->links() }}
        </x-card.body>
    </x-card>
</x-layout>