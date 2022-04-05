<?php

namespace App\Models;

use App\Helpers\RandomHelper;
use App\Traits\Models\Transaction\Fastable;
use App\Traits\Models\Searchable;
use App\Traits\Todayable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use Fastable, HasFactory, Searchable, Todayable;

    protected $appends   = ['status_name', 'type_name'];

    protected $guarded   = ['id'];

    public $status_names = [
        1 => 'Pending',
        2 => 'Paid',
        3 => 'Success',
        4 => 'Failed',
        5 => 'Canceled',
        6 => 'Expired'
    ];

    public $type_names   = [
        1 => 'Topup',
        2 => 'Buying',
        3 => 'Withdraw',
        4 => 'Refund',
    ];


    static protected function boot() {
        parent::creating(function($data) {
            if(!$data->code)
                $data->code = 'INV'.strtoupper(RandomHelper::code());
        });

        parent::boot();
    }


    public function sender() {
        return $this->belongsTo(User::class);
    }


    public function receiver() {
        return $this->belongsTo(User::class);
    }


    public function items() {
        return $this->belongsToMany(Item::class);
    }


    protected function statusName(): Attribute{
        $get = function() {
            $status      = $this->status;
            $statusNames = collect($this->status_names);
            $statusName  = $statusNames->first(fn($_, $key) => $key === $status);

            return $statusName ?? 'Unknown';
        };

        return Attribute::make($get);
    }


    protected function typeName(): Attribute{
        $get = function() {
            $type      = $this->type;
            $typeNames = collect($this->type_names);
            $typeName  = $typeNames->first(fn($_, $key) => $key === $type);

            return $typeName ?? 'Unknown';
        };

        return Attribute::make($get);
    }
}
