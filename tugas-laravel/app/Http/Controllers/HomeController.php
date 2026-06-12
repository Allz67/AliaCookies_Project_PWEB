<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class HomeController extends Controller
{
    /**
     * Halaman Beranda — menampilkan produk aktif dipisah per kategori.
     */
    public function index(Request $request)
    {
        $keyword = $request->keyword;

        // 1. JIKA ADA REQUEST PENCARIAN DARI LIVE SEARCH
        if ($request->ajax() || $request->has('keyword')) {
            $products = Product::query()->where('is_active', 1)
                ->whereNull('deleted_at')
                ->where(function($q) use ($keyword) {
                    $q->where('nama', 'LIKE', "%{$keyword}%")
                      ->orWhere('kategori', 'LIKE', "%{$keyword}%");
                })
                ->orderBy('nama')
                ->get();

            $html = '';
            if($products->isEmpty()) {
                $html = '<div style="text-align:center; padding: 2rem; color: var(--mocha);">Yah, produk "'.$keyword.'" tidak ditemukan 🍪</div>';
            } else {
                $html .= '<div class="sc-related__grid" style="padding: 0 5%;">';
                foreach($products as $item) {
                    $foto = $item->foto ? asset('storage/' . $item->foto) : '';
                    $fallback = $item->kategori === 'Hampers' ? '🎁' : '🍪';
                    $harga = number_format($item->harga, 0, ',', '.');
                    $route = route('product.detail', $item->id);

                    $html .= '
                    <a href="'.$route.'" class="sc-pcard sc-pcard--grid">
                        <div class="sc-pcard__img-wrap">
                            '.($foto ? '<img src="'.$foto.'" alt="'.$item->nama.'" loading="lazy">' : '<div class="sc-pcard__img-fallback">'.$fallback.'</div>').'
                            <div class="sc-pcard__overlay"><span>Lihat Detail</span></div>
                        </div>
                        <div class="sc-pcard__body">
                            <span class="sc-pcard__kategori sc-pcard__kategori--'.strtolower($item->kategori).'">'.$item->kategori.'</span>
                            <h3 class="sc-pcard__nama">'.$item->nama.'</h3>
                            <p class="sc-pcard__harga">Rp '.$harga.'</p>
                        </div>
                    </a>';
                }
                $html .= '</div>';
            }
            return response()->json(['html' => $html]);
        }

        // 2. TAMPILAN DEFAULT BERANDA (Jika tidak sedang mencari)
        $cookies = Product::query()->where('is_active', 1)->where('kategori', 'Cookies')->where('stok', '>', 0)->whereNull('deleted_at')->orderBy('nama')->get();
        $hampers = Product::query()->where('is_active', 1)->where('kategori', 'Hampers')->where('stok', '>', 0)->whereNull('deleted_at')->orderBy('nama')->get();

        return view('home', compact('cookies', 'hampers'));
    }

    /**
     * Halaman Detail Produk — menampilkan satu produk berdasarkan id.
     */
    public function show($id)
    {
        $produk = Product::query()->where('is_active', 1)
                         ->whereNull('deleted_at')
                         ->findOrFail($id);

        return view('detail', compact('produk'));
    }
}
