<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }
    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        // 1. Validasi Inputan Alamat Baru
        $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'phone'         => ['nullable', 'string', 'max:20'],
            'email'         => ['required', 'string', 'email', 'max:255', \Illuminate\Validation\Rule::unique('users')->ignore($user->id)],
            'provinsi_id'   => ['nullable', 'string'],
            'provinsi_nama' => ['nullable', 'string'],
            'kota_id'       => ['nullable', 'string'],
            'kota_nama'     => ['nullable', 'string'],
            'kecamatan'     => ['nullable', 'string', 'max:100'],
            'kode_pos'      => ['nullable', 'string', 'max:10'],
            'detail_alamat' => ['nullable', 'string', 'max:500'],
        ]);

        // 2. Isi data ke model
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone ?? '0000';

        // Simpan data alamat detail
        $user->provinsi_id   = $request->provinsi_id;
        $user->provinsi_nama = $request->provinsi_nama;
        $user->kota_id       = $request->kota_id;
        $user->kota_nama     = $request->kota_nama;
        $user->kecamatan     = $request->kecamatan;
        $user->kode_pos      = $request->kode_pos;
        $user->detail_alamat = $request->detail_alamat;

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::back()->with('success', 'Profil Anda berhasil diperbarui!');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
