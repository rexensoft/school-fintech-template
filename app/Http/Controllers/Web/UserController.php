<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller
{
    public function index(Request $request) {
        $search = $request->search; 
        $users  = User::latest();

        if($search) $users = $users->search($search);

        $users  = $users->paginate(10);
        
        return view('pages.admin.users.index', compact('users'));
    }


    public function store(Request $request) {
        try{
            $this->validate($request, [
                'name'  => 'required|min:2|max:50',
                'email' => 'required|email|unique:users',
            ]);

            User::create([
                'name'      => $request->name,
                'email'     => $request->email,
                'password'  => Hash::make('password'),
            ]);

            Alert::success('Success', 'User added successfully');
        }catch(Exception $err) {
            Alert::error('Failed', $err->getMessage());
        }finally {
            return back();
        }
    }


    public function update(Request $request, $userId) {
        try{
            $this->validate($request, [
                'name'  => 'nullable|min:2|max:50',
                'email' => "nullable|email|unique:users,email,$userId",
            ]);

            $user = User::find($userId);

            if(!$user) throw new Exception('User not found');

            $user->update([
                'name'  => $request->name ?? $user->name,
                'email' => $request->email ?? $user->email,
            ]);

            Alert::success('Success', 'User updated successfully');
        }catch(Exception $err) {
            Alert::error('Failed', $err->getMessage());
        }finally {
            return back();
        }
    }


    public function destroy($userId) {
        try{
            $user = User::find($userId);

            if(!$user) throw new Exception('User not found');

            $user->delete();

            Alert::success('Success', 'User deleted successfully');
        }catch(Exception $err) {
            Alert::error('Failed', $err->getMessage());
        }finally {
            return back();
        }
    }
}
