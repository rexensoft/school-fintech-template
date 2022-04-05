<?php

namespace App\Exports;

use App\Models\Transaction;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\{FromCollection, FromView, ShouldAutoSize, WithEvents};
use Maatwebsite\Excel\Events\AfterSheet;

class TransactionExport implements FromView, ShouldAutoSize, WithEvents
{
    public function view() :View {
        $transactions = Transaction::with(['sender', 'receiver'])
            ->get();

        return view('exports.transactions', compact('transactions'));
    }


    public function registerEvents() :array {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->freezePane('A3');
            },
        ];
    }
}
