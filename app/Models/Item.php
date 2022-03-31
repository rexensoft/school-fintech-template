<?php

namespace App\Models;

use App\Traits\Models\Item\Fastable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Item extends Model
{
    use Fastable, HasFactory;

    protected $guarded  = ['id'];


    public function seller() {
        return $this->belongsTo(User::class);
    }


    public function transactions() {
        return $this->belongsToMany(Transaction::class);
    }
}
