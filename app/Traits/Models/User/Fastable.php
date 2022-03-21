<?php

namespace App\Traits\Models\User;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\{Hash, Validator};

trait Fastable{
    public function scopeFastPaginate($query, $data=[]) {
        $data   = (object) $data;
        $search = $data->search ?? null; 
        $users  = $query->with(['role'])->latest();

        if($search) $users = $users->search($search);

        $users = $users
            ->paginate(10)
            ->withQueryString();
        
        return $users;
    }


    public function scopeFastCreate($query, $data, string $password='password') {
        $data      = (object) $data;
        $validator = Validator::make($data->all() ?? $data, [
            'name'      => 'required|min:2|max:50',
            'email'     => 'required|email|unique:users',
            'role_id'   => 'required|exists:roles,id',
        ]);

        if($validator->fails()) {
            $error = $validator->errors()->first();
            throw new Exception($error);
        }

        $user = User::create([
            'name'      => $data->name,
            'email'     => $data->email,
            'role_id'   => $data->role_id,
            'password'  => Hash::make('password'),
        ]);

        return $user;
    }


    public function scopeFastUpdate($query, $data, $user=null, string $password='password') {
        $userId = $user->id ?? null;
        $user   = isset($user) ? User::find($userId ?? $user) : auth()->user();

        if(!$user) throw new Exception('User not found');

        $validator  = Validator::make($data->all() ?? $data, [
            'name'      => 'nullable|min:2|max:50',
            'email'     => 'nullable|email|unique:users,email,' . $user->id,
            'role_id'   => 'nullable|exists:roles,id',
        ]);

        if($validator->fails()) {
            $error = $validator->errors()->first();
            throw new Exception($error);
        }

        $user->update([
            'name'      => $data->name ?? $user->name,
            'email'     => $data->email ?? $user->email,
            'role_id'   => $data->role_id ?? $user->role_id,
        ]);

        return $user;
    }


    public function scopeFastDelete($query, $user) {
        $userId = $user->id ?? null;
        $user   = User::find($userId ?? $user);

        if(!$user) throw new Exception('User not found');

        $user->delete();

        return $user;
    }
}