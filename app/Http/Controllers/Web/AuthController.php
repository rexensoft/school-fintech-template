<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function loginView() {
        return view('pages.guest.login');
    }


    public function login(Request $request) {
        $email      = $request->email;
        $password   = $request->password;

        $account = Auth::attempt([
            'email'     => $email,
            'password'  => $password,
        ]);

        if(!$account) 
            return back()
            ->withErrors(["Account doesn't match"])
            ->withInput();

        return redirect()->route('dashboard');
    }


    public function logout() {
        Auth::logout();
        return back();
    }
}
