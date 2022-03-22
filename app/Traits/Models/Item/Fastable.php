<?php

namespace App\Traits\Models\Item;

use App\Models\Item;
use Exception;
use Illuminate\Support\Facades\Validator;
use App\Traits\Models\Searchable;

trait Fastable{
    public function scopeFastPaginate($query, $data=[]) {
        $data   = (object) $data;
        $search = $data->search ?? null; 
        $items  = $query->latest();

        if($search) $items = $items->search($search);

        $items = $items
            ->paginate(10)
            ->withQueryString();
        
        return $items;
    }
    

    public function scopeFastCreate($query, $data) {
        $data       = (object) $data;
        $validator  = Validator::make($data->all() ?? $data, [
            'name'  => 'required|min:2|max:50',
            'stock' => 'nullable|numeric|digits_between:1,18',
            'price' => 'required|numeric|digits_between:1,18',
            'desc'  => 'nullable|max:255',
        ]);

        if($validator->fails()) {
            $error = $validator->errors()->first();
            throw new Exception($error);
        }

        $item = Item::create([
            'name'  => $data->name,
            'stock' => $data->stock,
            'price' => $data->price,
            'desc'  => $data->desc,
        ]);

        return $item;
    }
    
    
    public function scopeFastUpdate($query, $data, $item) {        
        $itemId     = $item->id ?? null;
        $item       = Item::find($itemId ?? $item);
        $data       = (object) $data;
        $validator  = Validator::make($data->all() ?? $data, [
            'name'  => 'nullable|min:2|max:50',
            'stock' => 'nullable|numeric|digits_between:1,18',
            'price' => 'nullable|numeric|digits_between:1,18',
            'desc'  => 'nullable|max:255',
        ]);
        
        if(!$item) throw new Exception('Item not found');
        if($validator->fails()) {
            $error = $validator->errors()->first();
            throw new Exception($error);
        }

        $item->update([
            'name'  => $data->name ?? $item->name,
            'stock' => $data->stock ?? $item->stock,
            'price' => $data->price ?? $item->price,
            'desc'  => $data->desc,
        ]);

        return $item;
    }


    public function scopeFastDelete($query, $item) {
        $itemId = $item->id ?? null;
        $item   = Item::find($itemId ?? $item);

        if(!$item) throw new Exception('Item not found');

        $item->delete();

        return $item;
    }
}