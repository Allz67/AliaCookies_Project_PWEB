<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Carbon\Carbon; 

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if (!session()->has('success')) {
            session()->flash('success', 'Selamat datang kembali di Panel Admin Alia Cookies!');
        }

        $visitCount = $request->session()->get('visit_count', 0) + 1;
        $request->session()->put('visit_count', $visitCount);

        if (!$request->session()->has('first_visit')) {
            $request->session()->put('first_visit', now());
        }

        $request->session()->put('last_visit', now());

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

        $stats = [
            'pendapatan' => 'Rp 12.480.000',
            'pesanan'    => 148,
            'terjual'    => 342,
            'pelanggan'  => 87,
        ];

        $grafik = [
            'labels' => ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
            'data'   => [1200000, 980000, 1450000, 870000, 1600000, 2100000, 1850000],
        ];

        return view('dashboard', [
            'username'     => Auth::user()->name,
            'stats'        => $stats,
            'transactions' => $transactions,
            'grafik'       => $grafik,

            // JANGAN LUPA: Lempar juga variabel session-nya ke view
            'visitCount'   => $visitCount,
            'firstVisit'   => $request->session()->get('first_visit'),
            'lastVisit'    => $request->session()->get('last_visit'),
        ]);
    }

    public function resetKunjungan(Request $request)
    {
        $request->session()->forget(['visit_count', 'first_visit', 'last_visit']);

        return redirect()->back()->with('success', 'Hitungan kunjungan berhasil direset dari awal!');
    }
}
