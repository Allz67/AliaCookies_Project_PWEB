<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function splash()
    {
        return view('splash');
    }

    public function login()
    {
        return view('login');
    }

    public function doLogin(Request $request)
    {
        $username = $request->input('username');
        session(['username' => $username]);
        return redirect()->route('dashboard');
    }

    public function logout()
    {
        session()->forget('username');
        return redirect()->route('login');
    }
}
