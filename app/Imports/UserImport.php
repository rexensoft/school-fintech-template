<?php

namespace App\Imports;

use App\Models\{User, Transaction};
use Exception;
use Illuminate\Support\Facades\{Hash, Validator};
use Maatwebsite\Excel\Concerns\{ToModel, WithEvents, WithHeadingRow};
use Maatwebsite\Excel\Events\AfterImport;

class UserImport implements ToModel, WithEvents, WithHeadingRow{    
    public $data      = [];

    public $rowNumber = 0;


    public function model(array $row) {
        $this->rowNumber++;

        $validator = Validator::make($row, [
            'name'      => 'required|min:2|max:50',
            'email'     => 'required|email|unique:users',
            'role_id'   => 'required|exists:roles,id',
            'balance'   => 'nullable|numeric|digits_between:1,18',
        ]);
        
        if(!$row['name'] && !$row['email'] && !$row['role_id'])
            return;
        if($validator->fails()) {
            $error   = $validator->errors()->first();
            $message = "$error Error on row {$this->rowNumber}";
            throw new Exception($message);
        }

        $user = new User([
            'name'      => $row['name'],
            'email'     => $row['email'],
            'role_id'   => $row['role_id'],
            'balance'   => $row['balance'] ?? 0,
            'password'  => Hash::make('password'),
        ]);

        $this->data[] = $user;

        return $user;
    }

 
    public function registerEvents(): array {
        return [
            AfterImport::class => function(AfterImport $event) {
                collect($this->data)->each(function($user) {
                    if($user->balance < 1) return;
                    Transaction::fastTopup([
                        'receiver_id'   => $user->id,
                        'amount'        => $user->balance,
                    ], $user);
                });
            },
        ];
    }
}