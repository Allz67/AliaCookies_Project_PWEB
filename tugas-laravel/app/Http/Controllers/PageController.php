<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function profile()
    {
        $userNama = Auth::user()->name;

        $user = [
            'nama'          => $userNama,
            'role'          => Auth::user()->role,
            'email'         => Auth::user()->email,
            'tanggal_login' => date('d F Y, H:i') . ' WIB',
            'bergabung'     => '01 Januari 2025',
            'toko'          => 'Alia Cookies',
            'lokasi'        => 'Jember, Indonesia',
            'no_hp'         => '+62 856-4856-9562',
        ];

        return view('profile', ['username' => $userNama,'user' => $user]);
    }
}
