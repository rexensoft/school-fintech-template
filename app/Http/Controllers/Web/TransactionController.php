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
        $type         = $request->type;
        $search       = $request->search;
        $transactions = Transaction::latest();

        if($type)
            $transactions = $transactions->where('type', $type);
        if($search)
            $transactions = $transactions->search($search);

        $transactions = $transactions->paginate(10);

        return view('pages.admin.transaction.index', compact('transactions'));
    }


    public function topup(Request $request) {
        try{
            $this->validate($request, [
                'amount'    => 'required|numeric|digits_between:1,18',
            ]);

            Transaction::create([
                'receiver_id'   => auth()->id(),
                'amount'        => $request->amount,
                'type'          => 1,
                'status'        => 1,
            ]);

            Alert::success('Success', 'Topup created successfully');
        }catch(Exception $err) {
            Alert::error('Failed', $err->getMessage());
        }finally {
            return back();
        }
    }
}
