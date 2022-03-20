<?php

namespace App\Models;

use App\Helpers\RandomHelper;
use App\Traits\Models\Searchable;
use Exception;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Transaction extends Model
{
    use HasFactory, Searchable;

    protected $appends   = ['status_name', 'type_name'];

    protected $guarded   = ['id'];

    public $status_names = [
        1 => 'Pending',
        2 => 'Paid',
        3 => 'Success',
        4 => 'Failed',
        5 => 'Expired'
    ];

    public $type_names   = [
        1 => 'Topup',
        2 => 'Buying',
        3 => 'Refund',
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


    /*
        FAST METHODS
    */
    public function scopeFastPaginate($query, $data=[]) {
        $data           = (object) $data;
        $transactions   = $query->with(['sender', 'receiver'])->latest();

        $transactions = $transactions->where(function($query) use($data) {
            $type   = $data->type ?? null;
            $status = $data->status ?? null;
            $search = $data->search ?? null; 
            
            if($type) $query->where('type', $type);
            if($status) $query->where('status', $status);
            if($search) $query->search($search);
        });

        $transactions = $transactions
            ->paginate(10)
            ->withQueryString();
        
        return $transactions;
    }

    
    public function scopeFastTopup($query, $data, $user=null) {
        $userId = $user->id ?? null;
        $user   = isset($user) ? User::find($userId ?? $user) : auth()->user();

        if(!$user) throw new Exception('User not found');
        if(gettype($data) === 'array') $data = (object) $data;

        $validator  = Validator::make($data->all() ?? $data, [
            'amount' => 'required|numeric|digits_between:1,18',
        ]);

        if($validator->fails()) {
            $error = $validator->errors()->first();
            throw new Exception($error);
        }

        $transaction = Transaction::create([
            'receiver_id'   => auth()->id(),
            'amount'        => $data->amount,
            'type'          => 1,
            'status'        => 1,
        ]);

        return $transaction;
    }
    
    
    public function scopeFastApprove($query, $transaction) {
        $transactionId = $transaction->id ?? null;
        $transaction   = Transaction::with(['receiver'])
            ->find($transactionId ?? $transaction);

        if(!$transaction) throw new Exception('User not found');
        if($transaction->type !== 1) throw new Exception('Invalid transaction');
        if($transaction->status !== 1) throw new Exception('Already responded');

        $receiver = $transaction->receiver;
        $receiver->update([
            'balance' => $receiver->balance + $transaction->amount
        ]);

        $transaction = $transaction->update([
            'status' => 2, // Paid
        ]);

        return $transaction;
    }
    
    
    public function scopeFastReject($query, $transaction) {
        $transactionId = $transaction->id ?? null;
        $transaction   = Transaction::with(['receiver'])
            ->find($transactionId ?? $transaction);

        if(!$transaction) throw new Exception('User not found');
        if($transaction->type !== 1) throw new Exception('Invalid transaction');
        if($transaction->status !== 1) throw new Exception('Already responded');

        $transaction = $transaction->update([
            'status' => 4, // Failed
        ]);

        return $transaction;
    }
}
