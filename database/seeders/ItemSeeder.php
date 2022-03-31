<?php

namespace Database\Seeders;

use App\Models\{Item, User};
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    public function run()
    {
        $seller = User::skip(1)->first();
        $items  = [ // [name, stock, price, desc]
            ['Pencil',  40, 3000, 'This is a pencil'],
            ['Pen',     25, 2500, 'This is a pen'],
            ['Book',    55, 6000, 'This is a book'],
            ['Ruler',   10, 5000, 'This is a ruler'],
        ];

        foreach($items as $item) {
            Item::create([
                'seller_id' => $seller->id,
                'name'      => $item[0],
                'stock'     => $item[1],
                'price'     => $item[2],
                'desc'      => $item[3],
            ]);
        }
    }
}
