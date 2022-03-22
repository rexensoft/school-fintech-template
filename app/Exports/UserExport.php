<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ToArray;

class UserExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $header = ['ID', 'Role ID', 'Name', 'Balance', 'Email', 'Created At', 'Updated At'];
        $users  = User::select('id', 'role_id', 'name', 'name', 'balance', 'email', 'created_at', 'updated_at')->get();
        $users->splice(0, 0, [$header]);

        return $users;
    }
}
