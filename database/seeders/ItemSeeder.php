<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    public function run()
    {
        $items = [ // [name, stock, price, desc]
            ['Pencil',  40, 3000, 'This is a pencil'],
            ['Pen',     25, 2500, 'This is a pen'],
            ['Book',    55, 6000, 'This is a book'],
            ['Ruler',   10, 5000, 'This is a ruler'],
        ];

        foreach($items as $item) {
            Item::create([
                'name'  => $item[0],
                'stock' => $item[1],
                'price' => $item[2],
                'desc'  => $item[3],
            ]);
        }
    }
}
