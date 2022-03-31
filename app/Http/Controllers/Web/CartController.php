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


    public function store($itemId) {
        try{
            Cart::fastAdd($itemId);
            Alert::success('Success', 'Added to cart successfully');
            return back();
        }catch(Exception $err) {
            Alert::error('Failed', $err->getMessage());
            return back();
        }
    }


    public function destroy($itemId) {
        try{
            Cart::fastDelete($itemId);
            Alert::success('Success', 'Deleted from cart successfully');
            return back();
        }catch(Exception $err) {
            Alert::error('Failed', $err->getMessage());
            return back();
        }
    }


    public function checkout() {
        try{
            Cart::fastCheckout();
            Alert::success('Success', 'Checkout successfully');
            return back();
        }catch(Exception $err) {
            Alert::error('Failed', $err->getMessage());
            return back();
        }
    }
}
