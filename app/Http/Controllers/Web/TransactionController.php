<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Exception;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class TransactionController extends Controller
{
    public function index(Request $request) {
        $role = auth()->user()->role_id;
        $trxs = new Transaction();

        if($role === 2) $trxs = Transaction::where('type', 2); // Buying
        if($role === 3) $trxs = Transaction::where('type', 1); // Topup
        if($role === 4) $trxs = Transaction::where('sender_id', auth()->id())
            ->orWhere('receiver_id', auth()->id());

        $trxs = $trxs->fastPaginate($request);

        return view('pages.all.transactions.index', ['transactions' => $trxs]);
    }


    public function topup(Request $request) {
        try{
            Transaction::fastTopup($request);
            Alert::success('Success', 'Topup created successfully');
            return back();
        }catch(Exception $err) {
            Alert::error('Failed', $err->getMessage());
            return back();
        }
    }
    
    
    public function approve($transactionId) {
        try{
            Transaction::fastApprove($transactionId);
            Alert::success('Success', 'Topup approved successfully');
            return back();
        }catch(Exception $err) {
            Alert::error('Failed', $err->getMessage());
            return back();
        }
    }
    
    
    public function reject($transactionId) {
        try{
            Transaction::fastReject($transactionId);
            Alert::success('Success', 'Topup rejected successfully');
            return back();
        }catch(Exception $err) {
            Alert::error('Failed', $err->getMessage());
            return back();
        }
    }
}
