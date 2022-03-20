<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(Request $request) {
        $items = Item::latest()->paginate(10);

        return view('pages.seller.items.index', compact('items'));
    }
}
