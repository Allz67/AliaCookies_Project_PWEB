<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function profile()
    {
        $username = session('username', 'Admin');

        $user = [
            'nama'          => $username,
            'role'          => 'Administrator',
            'email'         => strtolower(str_replace(' ', '', $username)) . '@aliacookies.com',
            'tanggal_login' => date('d F Y, H:i') . ' WIB',
            'bergabung'     => '01 Januari 2025',
            'toko'          => 'Alia Cookies',
            'lokasi'        => 'Jember, Indonesia',
            'no_hp'         => '+62 856-4856-9562',
        ];

        return view('profile', compact('username', 'user'));
    }
}
