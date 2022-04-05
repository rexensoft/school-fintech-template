<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\{FromView, RegistersEventListeners, ShouldAutoSize, WithEvents};
use Maatwebsite\Excel\Events\AfterSheet;


class UserExport implements FromView, ShouldAutoSize, WithEvents
{
    use RegistersEventListeners;

    public function view() :View {
        $users = User::with(['role'])->get();

        return view('exports.users', compact('users'));
    }


    static public function afterSheet(AfterSheet $event) {
        $event->sheet->getDelegate()->freezePane('A2');
    }
}
