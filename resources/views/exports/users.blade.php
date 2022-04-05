<table>
    @php
        $bold       = 'font-weight: bold;';
        $textCenter = 'text-align: center;';
    @endphp

    <thead>
        <tr>
            <th style="{{ $bold }}">#</th>
            <th style="{{ $bold }}">ID</th>
            <th style="{{ $bold }}">Role ID</th>
            <th style="{{ $bold }}">Role Name</th>
            <th style="{{ $bold }}">Balance</th>
            <th style="{{ $bold }}">Name</th>
            <th style="{{ $bold }}">Email</th>
            <th style="{{ $bold }}">Created At</th>
            <th style="{{ $bold }}">Updated At</th>
        </tr>
    </thead>
    <tbody>

        @foreach($users as $key => $user)
        
        @php
            $bgColor = $key % 2 === 0 ? '#fff' : '#efefef';
            $bgColor = "background-color: $bgColor";
        @endphp

        <tr>
            <td style="{{ $bgColor }}">{{ $key+1 }}</td>
            <td style="{{ $bgColor }}">{{ $user->id }}</td>
            <td style="{{ $bgColor }}">{{ $user->role_id }}</td>
            <td style="{{ $bgColor }}">{{ $user->role->name }}</td>
            <td style="{{ $bgColor }}">{{ $user->balance ?? 0 }}</td>
            <td style="{{ $bgColor }}">{{ $user->name }}</td>
            <td style="{{ $bgColor }}">{{ $user->email }}</td>
            <td style="{{ $bgColor }}">{{ $user->created_at->format('d/m/Y H:i:s') }}</td>
            <td style="{{ $bgColor }}">{{ $user->updated_at->format('d/m/Y H:i:s') }}</td>
        </tr>
        @endforeach

    </tbody>
</table>