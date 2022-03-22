<?php

namespace App\Http\Controllers\Web;

use App\Models\Cart;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class CartController extends Controller
{
    public function index(Request $request) {
        $role  = auth()->user()->role_id;
        $items = Cart::fastPaginate($request);

        return view('pages.student.carts.index', compact('items'));
    }


    public function destroy($itemId) {
        try{
            Cart::fastDelete($itemId);
            Alert::success('Success', 'Cart deleted successfully');
            return back();
        }catch(Exception $err) {
            Alert::error('Failed', $err->getMessage());
            return back();
        }
    }


    public function checkout(Request $request) {
        try{
            Cart::fastCheckout($request);
            Alert::success('Success', 'Checkout successfully');
            return back();
        }catch(Exception $err) {
            Alert::error('Failed', $err->getMessage());
            return back();
        }
    }
}
