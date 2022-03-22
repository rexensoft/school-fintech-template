<?php

namespace App\Traits\Models\Cart;

use App\Models\Cart;
use Exception;
use Illuminate\Support\Facades\Validator;
use App\Traits\Models\Searchable;

trait Fastable{
    public function scopeFastPaginate($query, $data=[]) {
        $data   = (object) $data;
        $search = $data->search ?? null;
        $user   = auth()->user() ?? $query;
        $items  = $user->items();

        if($search) $items = $items->search($search);

        $items = $items
            ->paginate(10)
            ->withQueryString();
        
        return $items;
    }
}