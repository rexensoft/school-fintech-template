<?php

namespace Database\Factories;

use App\Helpers\RandomHelper;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    public function definition()
    {
        $amount     = rand(1, 40) * 500;
        $code       = strtoupper(RandomHelper::code());
        $sender     = User::inRandomOrder()->first();
        $receiver   = User::inRandomOrder()->first();

        return [
            'sender_id'     => $sender->id,
            'receiver_id'   => $receiver->id,
            'code'          => "INV$code",
            'amount'        => $amount,
            'type'          => rand(1, 3),
        ];
    }
}
