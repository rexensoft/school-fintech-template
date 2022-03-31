<?php

namespace App\Traits\Models\Transaction;

use App\Models\{Item, ItemTransaction, Transaction, User};
use Exception;
use Illuminate\Support\Facades\Validator;

trait Fastable{
    public function scopeFastPaginate($query, $data=[]) {
        $data           = (object) $data;
        $type           = $data->type ?? null;
        $transactions   = $query->with(['sender', 'receiver'])->latest();
        
        $transactions = $transactions->where(function($query) use($data) {
            $type   = $data->type ?? null;
            $status = $data->status ?? null;
            $search = $data->search ?? null; 
            
            if($type) $query->where('type', $type);
            if($status) $query->where('status', $status);
            if($search) $query->search($search);
        });

        if($type)
            $transactions = $transactions->where('type', $type);

        $transactions = $transactions
            ->paginate(10)
            ->withQueryString();
        
        return $transactions;
    }


    public function scopeFastCreate($query, $data) {
        $data       = gettype($data) === 'object' ? $data->all() ?? $data : $data;
        $validator  = Validator::make($data, [
            'sender_id'     => 'nullable|exists:users,id',
            'receiver_id'   => 'required|exists:users,id',
            'amount'        => 'required|numeric|digits_between:1,18',
            'type'          => 'required|numeric|min:1',
            'status'        => 'nullable|numeric|min:1'
        ]);

        if($validator->fails()) {
            $error = $validator->errors()->first();
            throw new Exception($error);
        }

        $transaction = Transaction::create($data);

        return $transaction;
    }

    
    public function scopeFastTopup($query, $data, $user=null) {
        $data       = (object) $data;
        $sending    = $user ? true : false;
        $userId     = $user->id ?? null;
        $user       = isset($user) ? User::find($userId ?? $user) : auth()->user();

        if(!$user) throw new Exception('User not found');

        $transaction = Transaction::fastCreate([
            'receiver_id'   => $user->id ?? null,
            'amount'        => $data->amount,
            'type'          => 1,
            'status'        => $sending ? 3 : 1,
        ]);

        if($transaction->status === 3) $user->update([
            'balance' => $user->balance + $transaction->amount, 
        ]);
        
        return $transaction;
    }

    
    public function scopeFastBuy($query, $data, $items, $user=null) {
        $data       = (object) $data;
        $sending    = $user ? true : false;
        $userId     = $user->id ?? null;
        $user       = isset($user) ? User::find($userId ?? $user) : auth()->user();
        $items      = collect($items);

        if(!$user) throw new Exception('User not found');
        if(!($data->amount ?? null)) throw new Exception('Data not valid');
        if($user->balance < $data->amount) throw new Exception('Balance not enough');

        $items->groupBy('seller_id')->each(function($item, $key) use($sending, $user) {
            $transaction = Transaction::fastCreate([
                'sender_id'     => $user->id ?? null,
                'receiver_id'   => $key,
                'amount'        => $item->sum('price'),
                'type'          => 2,
                'status'        => $sending ? 3 : 1,
            ]);
            
            ItemTransaction::insert($item->map(fn($item) => ([
                'transaction_id'    => $transaction->id,
                'item_id'           => $item->id,
            ]))->toArray());
        });

        $user->update([
            'balance' => $user->balance - $data->amount, 
        ]);
        
        return true;
    }
    
    
    public function scopeFastApprove($query, $transaction) {
        $transactionId = $transaction->id ?? null;
        $transaction   = Transaction::with(['receiver'])
            ->find($transactionId ?? $transaction);

        if(!$transaction) throw new Exception('Transaction not found');
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

        if(!$transaction) throw new Exception('Transaction not found');
        if($transaction->type !== 1) throw new Exception('Invalid transaction');
        if($transaction->status !== 1) throw new Exception('Already responded');

        $transaction = $transaction->update([
            'status' => 4, // Failed
        ]);

        return $transaction;
    }


    public function scopeFastApproveBuy($query, $transaction) {
        $transactionId = $transaction->id ?? null;
        $transaction   = Transaction::with(['receiver'])
            ->find($transactionId ?? $transaction);

        if(!$transaction) throw new Exception('User not found');
        if($transaction->type !== 2) throw new Exception('Invalid transaction');
        if($transaction->status !== 1) throw new Exception('Already responded');

        $seller = $transaction->receiver;
        $seller->update([
            'balance'   => $seller->balance + $transaction->amount,
        ]);

        $transaction = $transaction->update([
            'status' => 2, // Paid
        ]);

        return $transaction;
    }
    
    
    public function scopeFastRejectBuy($query, $transaction) {
        $transactionId = $transaction->id ?? null;
        $transaction   = Transaction::with(['receiver'])
            ->find($transactionId ?? $transaction);

        if(!$transaction) throw new Exception('User not found');
        if($transaction->type !== 2) throw new Exception('Invalid transaction');
        if($transaction->status !== 1) throw new Exception('Already responded');

        $user = $transaction->sender;
        $user->update([
            'balance' => $user->balance + $transaction->amount,
        ]);

        $transaction = $transaction->update([
            'status' => 4, // Failed
        ]);

        return $transaction;
    }
}