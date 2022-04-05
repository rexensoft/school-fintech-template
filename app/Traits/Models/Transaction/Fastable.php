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
            'sender_id'     => $sending ? auth()->id() : null,
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
            
            $item->groupBy('id')->each(function($item, $key) {
                $count = $item->count();
                $item  = Item::find($key);

                $item->update([
                    'stock' => $item->stock - $count,
                ]);
            });
            
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
    
    
    public function scopeFastApproveTopup($query, $transaction) {
        $transactionId = $transaction->id ?? null;
        $transaction   = Transaction::with(['receiver'])
            ->where('type', 1)
            ->find($transactionId ?? $transaction);

        if(!$transaction) throw new Exception('Transaction not found');
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
    
    
    public function scopeFastRejectTopup($query, $transaction) {
        $transactionId = $transaction->id ?? null;
        $transaction   = Transaction::with(['receiver'])
            ->where('type', 1)
            ->find($transactionId ?? $transaction);

        if(!$transaction) throw new Exception('Transaction not found');
        if($transaction->status !== 1) throw new Exception('Already responded');

        $transaction = $transaction->update([
            'status' => 4, // Failed
        ]);

        return $transaction;
    }
    
    
    public function scopeFastCancelTopup($query, $transaction) {
        $user          = auth()->user();
        $transactionId = $transaction->id ?? null;
        $transaction   = Transaction::with(['receiver'])
            ->where('type', 1)->where('receiver_id', $user->id)
            ->find($transactionId ?? $transaction);

        if(!$transaction) throw new Exception('Transaction not found');
        if($transaction->status !== 1) throw new Exception('Already responded');

        $transaction = $transaction->update([
            'status' => 5, // Canceled
        ]);

        return $transaction;
    }


    public function scopeFastApproveBuy($query, $transaction) {
        $transactionId = $transaction->id ?? null;
        $transaction   = Transaction::with(['receiver'])
            ->where('type', 2)
            ->find($transactionId ?? $transaction);

        if(!$transaction) throw new Exception('Transaction not found');
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
        $transaction   = Transaction::with(['items', 'receiver'])
            ->where('type', 2)
            ->find($transactionId ?? $transaction);

        if(!$transaction) throw new Exception('Transaction not found');
        if($transaction->status !== 1) throw new Exception('Already responded');

        $transaction->items->groupBy('id')->each(function($item, $key) {
            $count = $item->count();
            $item  = Item::find($key);

            $item->update([
                'stock' => $item->stock + $count,
            ]);
        });

        $user = $transaction->sender;
        $user->update([
            'balance' => $user->balance + $transaction->amount,
        ]);

        $transaction = $transaction->update([
            'status' => 4, // Failed
        ]);

        return $transaction;
    }


    public function scopeFastCancelBuy($query, $transaction) {
        $user          = auth()->user();
        $transactionId = $transaction->id ?? null;
        $transaction   = Transaction::with(['items', 'receiver'])
            ->where('type', 2)->where('sender_id', $user->id)
            ->find($transactionId ?? $transaction);

        if(!$transaction) throw new Exception('Transaction not found');
        if($transaction->status !== 1) throw new Exception('Already responded');

        $transaction->items->groupBy('id')->each(function($item, $key) {
            $count = $item->count();
            $item  = Item::find($key);

            $item->update([
                'stock' => $item->stock + $count,
            ]);
        });

        $user = $transaction->sender;
        $user->update([
            'balance' => $user->balance + $transaction->amount,
        ]);

        $transaction = $transaction->update([
            'status' => 5, // Canceled
        ]);

        return $transaction;
    }


    public function scopeFastWithdraw($query, $data, $user=null) {
        $data       = (object) $data;
        $sending    = $user ? true : false;
        $userId     = $user->id ?? null;
        $user       = isset($user) ? User::find($userId ?? $user) : auth()->user();

        if(!$user) throw new Exception('User not found');
        if(!($data->amount ?? null)) throw new Exception('Data not valid');
        if($user->balance < $data->amount) throw new Exception('Balance not enough');

        $transaction = Transaction::fastCreate([
            'sender_id'     => $sending ? auth()->id() : null,
            'receiver_id'   => $user->id ?? null,
            'amount'        => $data->amount,
            'type'          => 3,
            'status'        => $sending ? 3 : 1,
        ]);
        
        $user->update([
            'balance' => $user->balance - $transaction->amount,
        ]);
        
        return $transaction;
    }


    public function scopeFastApproveWithdraw($query, $transaction) {
        $transactionId = $transaction->id ?? null;
        $transaction   = Transaction::with(['receiver'])
            ->where('type', 3)
            ->find($transactionId ?? $transaction);

        if(!$transaction) throw new Exception('Transaction not found');
        if($transaction->status !== 1) throw new Exception('Already responded');

        $transaction = $transaction->update([
            'status' => 2, // Paid
        ]);

        return $transaction;
    }
    
    
    public function scopeFastRejectWithdraw($query, $transaction) {
        $transactionId = $transaction->id ?? null;
        $transaction   = Transaction::with(['receiver'])
            ->where('type', 3)
            ->find($transactionId ?? $transaction);

        if(!$transaction) throw new Exception('Transaction not found');
        if($transaction->status !== 1) throw new Exception('Already responded');

        $receiver = $transaction->receiver;
        $receiver->update([
            'balance' => $receiver->balance + $transaction->amount
        ]);

        $transaction = $transaction->update([
            'status' => 4, // Failed
        ]);

        return $transaction;
    }
   
   
    public function scopeFastCancelWithdraw($query, $transaction) {
        $user          = auth()->user();
        $transactionId = $transaction->id ?? null;
        $transaction   = Transaction::with(['receiver'])
            ->where('type', 3)->where('receiver_id', $user->id)
            ->find($transactionId ?? $transaction);

        if(!$transaction) throw new Exception('Transaction not found');
        if($transaction->status !== 1) throw new Exception('Already responded');

        $receiver = $transaction->receiver;
        $receiver->update([
            'balance' => $receiver->balance + $transaction->amount
        ]);

        $transaction = $transaction->update([
            'status' => 5, // Canceled
        ]);

        return $transaction;
    }
}