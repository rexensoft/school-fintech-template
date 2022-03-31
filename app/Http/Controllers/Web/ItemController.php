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
        $user  = auth()->user();
        $role  = $user->role_id;
        $items = new Item();

        if($role === 2) $items = $items->where('seller_id', $user->id);

        $items = $items->fastPaginate($request);

        return view('pages.all.items.index', compact('items'));
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
