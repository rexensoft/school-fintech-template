<?php

namespace App\Traits\Models\Transaction;

use App\Models\{Transaction, User};
use Exception;
use Illuminate\Support\Facades\Validator;

trait Fastable{
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
}