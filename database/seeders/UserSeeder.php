<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = [ // [name, email, role_id]
            ['Admin',   'admin@example.com', 1],
            ['Seller',  'seller@example.com', 2],
            ['Teller',  'teller@example.com', 3],
            ['Student', 'student@example.com', 4],
        ];

        foreach($users as $key => $user) {
            User::create([
                'id'        => 100000 + $key,
                'name'      => $user[0],
                'email'     => $user[1],
                'role_id'   => $user[2],
                'password'  => Hash::make('password'),
            ]);
        }
    }
}
