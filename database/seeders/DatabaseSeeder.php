<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UserSeeder::class,
        ]);

        User::factory(20)
            ->hasSendTransactions(1)
            ->hasReceiveTransactions(1)
            ->create();
    }
}
