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

    
    public function scopeFastTopup($query, $data, $user=null) {
        $data       = (object) $data;
        $sending    = $user ? true : false;
        $userId     = $user->id ?? null;
        $user       = isset($user) ? User::find($userId ?? $user) : auth()->user();
        $validator  = Validator::make($data->all() ?? $data, [
            'amount'  => 'required|numeric|digits_between:1,18',
        ]);

        if(!$user) throw new Exception('User not found');
        if($validator->fails()) {
            $error = $validator->errors()->first();
            throw new Exception($error);
        }

        $transaction = Transaction::create([
            'receiver_id'   => $user->id,
            'amount'        => $data->amount,
            'type'          => 1,
            'status'        => $sending ? 3 : 1,
        ]);

        if($transaction->status === 3) $user->update([
            'balance' => $user->balance + $transaction->amount, 
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


    public function scopeFastBuy($query, $item, $user=null) {
        $userId     = $user->id ?? null;
        $user       = isset($user) ? User::find($userId ?? $user) : auth()->user();
        $itemId     = $item->id ?? null;
        $item       = Item::find($itemId ?? $item);
        $seller     = User::where('role_id', 2)->first();

        if(!$item) throw new Exception('Item not found');
        if(!$user) throw new Exception('User not found');
        if($user->balance < $item->price) throw new Exception('Balance not enough');

        $transaction = Transaction::create([
            'sender_id'     => $user->id,
            'receiver_id'   => $seller->id,
            'amount'        => $item->price * 1,
            'type'          => 2,
            'status'        => 1,
        ]);

        ItemTransaction::create([
            'transaction_id'    => $transaction->id,
            'item_id'           => $item->id,
        ]);

        $user->update([
            'balance' => $user->balance - $transaction->amount,
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

        $receiver = $transaction->receiver;
        $receiver->update([
            'balance' => $receiver->balance + $transaction->amount
        ]);

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