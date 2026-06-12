<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request) // <-- KITA TAMBAHKAN PARAMETER Request $request DI SINI
    {
        // 1. Ambil query dasar dari model Product
        $query = Product::query();

        // 2. KUNCI UTAMA: Logika Live Search AJAX untuk mencari Kode atau Nama Produk
        if ($request->has('keyword') && $request->get('keyword') != '') {
            $keyword = $request->get('keyword');
            $query->where(function($q) use ($keyword) {
                $q->where('nama', 'LIKE', "%{$keyword}%")
                ->orWhere('kode', 'LIKE', "%{$keyword}%");
            });
        }

        // 3. Ubah batas paginasinya menjadi per 10 baris data biar serasi dengan dashboard
        $products = $query->latest()->paginate(10);

        // 4. KUNCI AJAX: Jika request datang dari JavaScript AJAX, langsung kembalikan potongan tabel produk saja
        if ($request->ajax()) {
            return view('partials.product_table', [
                'products' => $products
            ])->render();
        }

        // 5. Statistik untuk dashboard pengelolaan (Semuanya tetap dipertahankan utuh bawaan kodemu)
        $stokStats = [
            'total'   => Product::sum('stok'),
            'menipis' => Product::where('stok', '>', 0)->where('stok', '<=', 10)->count(),
            'habis'   => Product::where('stok', 0)->count(),
        ];

        $chartStok = [
            'cookies' => Product::where('kategori', 'Cookies')->sum('stok'),
            'hampers' => Product::where('kategori', 'Hampers')->sum('stok'),
        ];

        // 6. Kembalikan ke view utama 'pengelolaan' lengkap dengan semua variabel pendukungnya
        return view('pengelolaan', [
            'username'  => Auth::user()->name,
            'products'  => $products,
            'stokStats' => $stokStats,
            'chartStok' => $chartStok
        ]);
    }

    public function create()
    {
        return view('product.create');
    }

    public function store(Request $request)
    {
        $prefix = ($request->kategori == 'Cookies') ? 'CK' : 'HM';

        $produkTerakhir = Product::withTrashed()
                                 ->where('kategori', $request->kategori)
                                 ->latest('id')
                                 ->first();

        // Ambil angka dari kode terakhir, lalu tambah 1
        if ($produkTerakhir) {
            // Memotong huruf depan (misal "HM-003" diambil angka "3"-nya saja)
            $angkaTerakhir = (int) substr($produkTerakhir->kode, 3);
            $count = $angkaTerakhir + 1;
        } else {
            $count = 1;
        }

        $generatedKode = $prefix . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);

        $request->validate([
            'nama'      => 'required|min:3',
            'stok'      => 'required|integer|min:0',
            'satuan'    => 'required',
            'kategori'  => 'required|in:Cookies,Hampers',
            'harga'     => 'required|numeric|min:5000|max:100000000',
            'deskripsi' => 'nullable|string',
            'foto'      => 'nullable|image|mimes:jpg,png,jpeg|max:2048'
        ]);

        $data = $request->all();
        $data['kode'] = $generatedKode;
        $data['is_active'] = true;

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('products', 'public');
            $data['foto'] = $path;
        }

        Product::create($data);

        return redirect()->route('product.index')
            ->with('success', "Produk $generatedKode berhasil ditambahkan!");
    }

    public function show(Product $product)
    {
        return view('product.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('product.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'nama'      => 'required|min:3',
            'stok'      => 'required|integer|min:0',
            'satuan'    => 'required',
            'kategori'  => 'required|in:Cookies,Hampers',
            'harga'     => 'required|numeric|min:5000|max:100000000',
            'foto'      => 'nullable|image|mimes:jpg,png,jpeg|max:2048'
        ]);

        $data = $request->except('foto');

        if ($request->hasFile('foto')) {
            if ($product->foto && Storage::disk('public')->exists($product->foto)) {
                Storage::disk('public')->delete($product->foto);
            }

            $path = $request->file('foto')->store('products', 'public');
            $data['foto'] = $path;
        }

        $product->update($data);

        return redirect()->route('product.index')
            ->with('success', "Data produk {$product->kode} berhasil diperbarui.");
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        $product->delete();

        return redirect()->back()->with('success', 'Produk berhasil dihapus!');
    }

    public function trashed()
    {
        $username = session('username', 'Admin');

        $products = Product::onlyTrashed()->latest()->paginate(10);

        return view('product.trashed', compact('username', 'products'));
    }

    public function restore($id)
    {
        try {
            $product = Product::withTrashed()->findOrFail($id);
            $product->restore();
            return redirect()->route('product.trashed')->with('success', 'Produk berhasil dipulihkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memulihkan produk.');
        }
    }
}
