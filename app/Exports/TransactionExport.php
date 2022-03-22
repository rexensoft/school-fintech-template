<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ToArray;

class TransactionExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $header         = ['ID', 'Sender ID', 'Receiver ID', 'Code', 'Amount', 'Created At', 'Updated At', 'Status', 'Type'];
        $transactions   = Transaction::all()->makeHidden(['detail', 'status', 'type']);
        $transactions->splice(0, 0, [$header]);

        return $transactions;
    }
}
