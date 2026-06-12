<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Carbon\Carbon;
use App\Models\User;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if (!session()->has('success')) {
            session()->flash('success', 'Selamat datang kembali di Panel Admin Alia Cookies!');
        }

        $query = Transaction::with(['user', 'items']);

        if ($request->has('keyword') && $request->get('keyword') != '') {
            $keyword = $request->get('keyword');
            $query->where(function($q) use ($keyword) {
                $q->where('id', 'LIKE', "%{$keyword}%")
                  ->orWhereHas('user', function($userQuery) use ($keyword) {
                      $userQuery->where('name', 'LIKE', "%{$keyword}%");
                  })
                  ->orWhereHas('items', function($subQuery) use ($keyword) {
                      $subQuery->where('nama_produk', 'LIKE', "%{$keyword}%");
                  });
            });
        }

        $transactions = $query->latest('tanggal_transaksi')->paginate(10);

        if ($request->ajax()) {
            return view('partials.transaction_table', compact('transactions'))->render();
        }

        $allTransactions = Transaction::with('items')->get();
        $paidTransactions = $allTransactions->where('payment_status', 'Paid');

        // ==========================================================
        // 2. STATISTIK KOTAK ATAS
        // ==========================================================
        $totalPendapatan = $paidTransactions->sum('total_harga');
        $totalPesanan    = $paidTransactions->count();
        $totalTerjual    = $paidTransactions->sum(function ($trx) {
            return $trx->items->sum('jumlah');
        });
        $totalPelanggan  = \App\Models\User::where('role', 'customer')->count();

        $stats = [
            'pendapatan' => 'Rp ' . number_format($totalPendapatan, 0, ',', '.'),
            'pesanan'    => $totalPesanan,
            'terjual'    => $totalTerjual,
            'pelanggan'  => $totalPelanggan,
        ];

        // ==========================================================
        // 3. INFO CEPAT DINAMIS
        // ==========================================================
        // Mencari produk terlaris dari pesanan yang sudah dibayar
        $semuaItemTerjual = $paidTransactions->pluck('items')->flatten();
        $produkTerlaris = $semuaItemTerjual->groupBy('nama_produk')->map(function ($grup) {
            return $grup->sum('jumlah');
        })->sortDesc()->keys()->first();

        // Menghitung pendapatan khusus hari ini
        $pendapatanHariIni = $paidTransactions->filter(function($trx) {
            return \Carbon\Carbon::parse($trx->created_at)->isToday();
        })->sum('total_harga');

        $infoCepat = [
            'selesai'             => $allTransactions->where('status_pesanan', 'Selesai')->count(),
            'proses'              => $allTransactions->where('status_pesanan', 'Proses')->count(),
            'batal'               => $allTransactions->where('status_pesanan', 'Batal')->count(),
            'produk_terlaris'     => $produkTerlaris ?? 'Belum ada',
        ];

        // ==========================================================
        // 4. GRAFIK DINAMIS (MINGGUAN)
        // ==========================================================
        $mingguanData = [];
        $mingguanLabels = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
        $awalMinggu = \Carbon\Carbon::now()->startOfWeek();

        // Looping untuk mencari pendapatan hari Senin - Minggu khusus minggu ini
        for ($i = 0; $i < 7; $i++) {
            $tanggalCek = $awalMinggu->copy()->addDays($i)->format('Y-m-d');
            $mingguanData[] = $paidTransactions->filter(function($trx) use ($tanggalCek) {
                return \Carbon\Carbon::parse($trx->created_at)->format('Y-m-d') === $tanggalCek;
            })->sum('total_harga');
        }

        // Susun array grafik menjadi lebih sederhana tanpa opsi harian
        $grafik = [
            'labels' => $mingguanLabels,
            'data'   => $mingguanData,
        ];

        // ==========================================================
        // RETURN VIEW (Pastikan infoCepat ikut dikirim)
        // ==========================================================
        return view('dashboard', [
            'username'     => Auth::user()->name,
            'stats'        => $stats,
            'infoCepat'    => $infoCepat, // Variabel baru dikirim ke view
            'transactions' => $transactions,
            'grafik'       => $grafik,
        ]);
    }

    public function resetKunjungan(Request $request)
    {
        $request->session()->forget(['visit_count', 'first_visit', 'last_visit']);

        return redirect()->back()->with('success', 'Hitungan kunjungan berhasil direset dari awal!');
    }
}
