<?php

namespace App\Http\Controllers\Web;

use App\Exports\TransactionExport;
use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Transaction;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class TransactionController extends Controller
{
    public function index(Request $request) {
        $role = auth()->user()->role_id;
        $trxs = new Transaction();

        if($role === 2) $trxs = Transaction::whereIn('type', [2,3]) // Buying, Withdraw
            ->where('receiver_id', auth()->id());
        if($role === 3) $trxs = Transaction::whereIn('type', [1,3]); // Topup, Withdraw
        if($role === 4) $trxs = Transaction::where('sender_id', auth()->id())
            ->orWhere('receiver_id', auth()->id());

        $trxs = $trxs->fastPaginate($request);

        return view('pages.all.transactions.index', ['transactions' => $trxs]);
    }


    public function export() {
        try{
            $filename = 'transactions_' . now()->format('ymdHis') . '.xlsx';
            return Excel::download(new TransactionExport, $filename);
        }catch(Exception $err) {
            Alert::error('Failed', $err->getMessage());
            return back();
        }
    }


    public function approve($transactionId) {
        try{
            $trx = Transaction::find($transactionId);
            if($trx->type === 1) return $this->approveTopup($trx->id);
            if($trx->type === 3) return $this->approveWithdraw($trx->id);
        }catch(Exception $err) {
            Alert::error('Failed', $err->getMessage());
            return back();
        }
    }
    
    
    public function reject($transactionId) {
        try{
            $trx = Transaction::find($transactionId);
            if($trx->type === 1) return $this->rejectTopup($trx->id);
            if($trx->type === 3) return $this->rejectWithdraw($trx->id);
        }catch(Exception $err) {
            Alert::error('Failed', $err->getMessage());
            return back();
        }
    }


    public function cancel($transactionId) {
        try{
            $trx = Transaction::find($transactionId);
            if($trx->type === 1) return $this->cancelTopup($trx->id);
            if($trx->type === 2) return $this->cancelTopup($trx->id);
            if($trx->type === 3) return $this->cancelWithdraw($trx->id);
        }catch(Exception $err) {
            Alert::error('Failed', $err->getMessage());
            return back();
        }
    }


    public function topup(Request $request) {
        try{
            if(auth()->user()->role_id ?? 4 === 4) unset($request->user_id);
            Transaction::fastTopup($request, $request->user_id);
            Alert::success('Success', 'Topup created successfully');
            return back();
        }catch(Exception $err) {
            Alert::error('Failed', $err->getMessage());
            return back();
        }
    }
    
    
    public function approveTopup($transactionId) {
        try{
            Transaction::fastApproveTopup($transactionId);
            Alert::success('Success', 'Topup approved successfully');
            return back();
        }catch(Exception $err) {
            Alert::error('Failed', $err->getMessage());
            return back();
        }
    }
    
    
    public function rejectTopup($transactionId) {
        try{
            Transaction::fastRejectTopup($transactionId);
            Alert::success('Success', 'Topup rejected successfully');
            return back();
        }catch(Exception $err) {
            Alert::error('Failed', $err->getMessage());
            return back();
        }
    }

    public function cancelTopup($transactionId) {
        try{
            Transaction::fastCancelTopup($transactionId);
            Alert::success('Success', 'Transaction canceled successfully');
            return back();
        }catch(Exception $err) {
            Alert::error('Failed', $err->getMessage());
            return back();
        }
    }

    
    public function buy(Request $request, Item $item) {
        try{
            Transaction::fastBuy($item);
            Alert::success('Success', 'Buying successfully');
            return back();
        }catch(Exception $err) {
            Alert::error('Failed', $err->getMessage());
            return back();
        }
    }


    public function approveBuy($transactionId) {
        try{
            Transaction::fastApproveBuy($transactionId);
            Alert::success('Success', 'Transaction approved successfully');
            return back();
        }catch(Exception $err) {
            Alert::error('Failed', $err->getMessage());
            return back();
        }
    }
    
    
    public function rejectBuy($transactionId) {
        try{
            Transaction::fastRejectBuy($transactionId);
            Alert::success('Success', 'Transaction rejected successfully');
            return back();
        }catch(Exception $err) {
            Alert::error('Failed', $err->getMessage());
            return back();
        }
    }


    public function cancelBuy($transactionId) {
        try{
            Transaction::fastCancelBuy($transactionId);
            Alert::success('Success', 'Transaction canceled successfully');
            return back();
        }catch(Exception $err) {
            Alert::error('Failed', $err->getMessage());
            return back();
        }
    }


    public function withdraw(Request $request) {
        try{
            if(auth()->user()->role_id ?? 4 === 4) unset($request->user_id);
            Transaction::fastWithdraw($request, $request->user_id);
            Alert::success('Success', 'Withdraw created successfully');
            return back();
        }catch(Exception $err) {
            Alert::error('Failed', $err->getMessage());
            return back();
        }
    }


    public function approveWithdraw($transactionId) {
        try{
            Transaction::fastApproveWithdraw($transactionId);
            Alert::success('Success', 'Transaction approved successfully');
            return back();
        }catch(Exception $err) {
            Alert::error('Failed', $err->getMessage());
            return back();
        }
    }
    
    
    public function rejectWithdraw($transactionId) {
        try{
            Transaction::fastRejectWithdraw($transactionId);
            Alert::success('Success', 'Transaction rejected successfully');
            return back();
        }catch(Exception $err) {
            Alert::error('Failed', $err->getMessage());
            return back();
        }
    }
    
    
    public function cancelWithdraw($transactionId) {
        try{
            Transaction::fastCancelWithdraw($transactionId);
            Alert::success('Success', 'Transaction canceled successfully');
            return back();
        }catch(Exception $err) {
            Alert::error('Failed', $err->getMessage());
            return back();
        }
    }
}
