<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Item extends Model
{
    use HasFactory;

    protected $guarded  = ['id'];


    public function transactions() {
        return $this->belongsToMany(Transaction::class);
    }
    

    /*
        FAST METHODS
    */
    public function scopeFastPaginate($query, $data=[]) {
        $data   = (object) $data;
        $search = $data->search ?? null; 
        $users  = $query->latest();

        if($search) $users = $users->search($search);

        $users = $users
            ->paginate(10)
            ->withQueryString();
        
        return $users;
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
            'desc'  => $data->desc ?? $item->desc,
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
