<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            ItemSeeder::class,
        ]);

        User::factory(20)
            ->create(['role_id' => 4]);
    }
}
