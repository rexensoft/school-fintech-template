<?php

namespace App\Traits\Models\Cart;

use App\Models\{Cart, Item, Transaction, User};
use Exception;
use Illuminate\Support\Facades\Validator;
use App\Traits\Models\Searchable;

trait Fastable{
    public function scopeFastPaginate($query, $data=[]) {
        $data   = (object) $data;
        $search = $data->search ?? null;
        $user   = auth()->user() ?? $query;
        $carts  = Cart::selectRaw('items.*, COUNT(*) as total')
            ->join('items', 'carts.item_id', '=', 'items.id')
            ->where('user_id', $user->id)
            ->groupBy('carts.item_id');

        if($search) $carts = $carts->search($search);

        $carts = $carts
            ->paginate(10)
            ->withQueryString();
        
        return $carts;
    }


    public function scopeFastAdd($query, $item, $user=null) {
        $user   = isset($user) ? User::find($user->id ?? $user) : auth()->user();
        $item   = Item::find($item->id ?? $item);

        if(!$user) throw new Exception('User not found');
        if(!$item) throw new Exception('Item not found');

        Cart::create([
            'user_id'   => $user->id,
            'item_id'   => $item->id,
        ]);

        return $item;
    }


    public function scopeFastDelete($query, $item, $user=null) {
        $user   = isset($user) ? User::find($user->id ?? $user) : auth()->user();
        $item   = Item::find($item->id ?? $item);

        if(!$user) throw new Exception('User not found');
        if(!$item) throw new Exception('Item not found');

        Cart::where('user_id', $user->id)
            ->where('item_id', $item->id)
            ->delete();

        return $item;
    }


    public function scopeFastCheckout($query, $user=null) {
        $userParam  = $user;
        $user       = $user ? User::find($user->id ?? $user) : auth()->user();
        $items      = $user->carts()->get() ?? null;
        $data       = [
            'amount' => $items->sum('price') ?? 0
        ];

        $transaction = Transaction::fastBuy($data, $items, $userParam);
        Cart::where('user_id', $user->id)->delete();
        
        return $transaction;
    }
}