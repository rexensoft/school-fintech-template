<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Exception;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class ItemController extends Controller
{
    public function index(Request $request) {
        $items = Item::fastPaginate($request);

        return view('pages.seller.items.index', compact('items'));
    }


    public function store(Request $request) {
        try{
            Item::fastCreate($request);
            Alert::success('Success', 'Item added successfully');
            return back();
        }catch(Exception $err) {
            Alert::error('Failed', $err->getMessage());
            return back();
        }
    }


    public function update(Request $request, $itemId) {
        try{
            Item::fastUpdate($request, $itemId);
            Alert::success('Success', 'Item updated successfully');
            return back();
        }catch(Exception $err) {
            Alert::error('Failed', $err->getMessage());
            return back();
        }
    }


    public function destroy($itemId) {
        try{
            Item::fastDelete($itemId);
            Alert::success('Success', 'Item deleted successfully');
            return back();
        }catch(Exception $err) {
            Alert::error('Failed', $err->getMessage());
            return back();
        }
    }
}
