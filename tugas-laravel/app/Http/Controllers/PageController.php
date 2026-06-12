<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Setting;

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
            'bergabung'     => Auth::user()->created_at->format('d F Y'),
            'lokasi'        => Auth::user()->address ?? 'Belum diisi',
            'no_hp'         => Auth::user()->phone ?? 'Belum diisi',
        ];

        return view('profile', ['username' => $userNama,'user' => $user]);
    }

    public function tentang()
    {
        $ceritaDb = Setting::query()->where('key', 'tentang_cerita')->first();
        $cerita = $ceritaDb ? $ceritaDb->value : 'Alia Cookies bermula dari kecintaan terhadap seni membuat kue...';

        $milestonesDb = Setting::query()->where('key', 'tentang_milestones')->first();
        $milestones = $milestonesDb ? json_decode($milestonesDb->value, true) : [];

        $kontaksDb = Setting::query()->where('key', 'tentang_kontak')->first();
        $kontaks = $kontaksDb ? json_decode($kontaksDb->value, true) : [];

        $produkUnggulan = [
            ['id' => 1, 'nama' => 'Choco Chip Cookies', 'harga' => 'Rp 45.000', 'ket' => 'Kue kering renyah dengan taburan cokelat melimpah.', 'gambar' => 'cookies1.jpg'],
            ['id' => 2, 'nama' => 'Premium Nastar',     'harga' => 'Rp 60.000', 'ket' => 'Nastar lembut dengan isian selai nanas homemade yang manis segar.', 'gambar' => 'cookies2.jpg'],
            ['id' => 3, 'nama' => 'Almond Cheese Crispy', 'harga' => 'Rp 55.000', 'ket' => 'Tipis, renyah, perpaduan gurihnya keju dan renyahnya kacang almond.', 'gambar' => 'cookies3.jpg'],
        ];

        return view('tentang', compact('cerita', 'milestones', 'kontaks', 'produkUnggulan'));
    }
    public function editTentang()
    {
        // Ambil data saat ini dari database
        $ceritaDb = Setting::query()->where('key', 'tentang_cerita')->first();
        $cerita = $ceritaDb ? $ceritaDb->value : '';

        $milestonesDb = Setting::query()->where('key', 'tentang_milestones')->first();
        $milestones = $milestonesDb ? json_decode($milestonesDb->value, true) : [];

        $kontaksDb = Setting::query()->where('key', 'tentang_kontak')->first();
        $kontaks = $kontaksDb ? json_decode($kontaksDb->value, true) : [];

        // Lempar ke file view khusus admin
        return view('admin-tentang', compact('cerita', 'milestones', 'kontaks'));
    }

    /**
     * PROSES SIMPAN DATA DARI ADMIN
     */
    public function updateTentang(Request $request)
    {
        // 1. Simpan Teks Cerita
        Setting::query()->updateOrCreate(
            ['key' => 'tentang_cerita'],
            ['value' => $request->cerita]
        );

        // 2. Simpan Data Milestones (Ubah array dari form jadi JSON)
        if ($request->has('milestones')) {
            // array_values() berguna untuk mereset nomor urut array jika ada baris yang dihapus admin
            Setting::query()->updateOrCreate(
                ['key' => 'tentang_milestones'],
                ['value' => json_encode(array_values($request->milestones))]
            );
        }

        // 3. Simpan Data Kontak
        if ($request->has('kontaks')) {
            Setting::query()->updateOrCreate(
                ['key' => 'tentang_kontak'],
                ['value' => json_encode(array_values($request->kontaks))]
            );
        }

        return redirect()->back()->with('success', 'Halaman Tentang Kami berhasil diperbarui');
    }

    public function kontak()
    {
        return view('kontak');
    }
}
