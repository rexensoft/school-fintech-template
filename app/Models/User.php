<?php

namespace App\Models;

use App\Traits\Models\Searchable;
use Exception;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\{Hash, Validator};
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Searchable, SoftDeletes;

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    protected $guarded = ['id'];

    protected $hidden = [
        'password',
        'remember_token',
    ];


    public function sendTransactions() {
        return $this->hasMany(Transaction::class, 'sender_id');
    }


    public function receiveTransactions() {
        return $this->hasMany(Transaction::class, 'receiver_id');
    }


    public function role() {
        return $this->belongsTo(Role::class);
    }


    public function carts() {
        return $this->belongsToMany(Item::class, 'carts');
    }


    /*
        FAST METHODS
    */
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
