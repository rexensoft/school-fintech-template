<?php

namespace App\Http\Controllers\Web;

use App\Exports\UserExport;
use App\Http\Controllers\Controller;
use App\Models\{Role, User};
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller
{
    public function index(Request $request) {
        $role   = auth()->user()->role_id;
        $roles  = Role::orderByDesc('id')->get();
        $users  = User::fastPaginate($request);
        
        if($role === 1)
            return view('pages.admin.users.index', compact('users', 'roles'));
        if($role === 3)
            return view('pages.teller.users.index', compact('users'));
    }


    public function store(Request $request) {
        try{
            User::fastCreate($request);
            Alert::success('Success', 'User added successfully');
            return back();
        }catch(Exception $err) {
            Alert::error('Failed', $err->getMessage());
            return back();
        }
    }


    public function update(Request $request, $userId) {
        try{
            User::fastUpdate($request, $userId);
            Alert::success('Success', 'User updated successfully');
            return back();
        }catch(Exception $err) {
            Alert::error('Failed', $err->getMessage());
            return back();
        }
    }


    public function destroy($userId) {
        try{
            User::fastDelete($userId);
            Alert::success('Success', 'User deleted successfully');
            return back();
        }catch(Exception $err) {
            Alert::error('Failed', $err->getMessage());
            return back();
        }
    }


    public function export() {
        try{
            return Excel::download(new UserExport, 'users.xlsx');
        }catch(Exception $err) {
            Alert::error('Failed', $err->getMessage());
            return back();
        }
    }
}
