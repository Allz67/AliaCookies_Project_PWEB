<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    /**
     * ID Kota Jember sebagai origin tetap toko Alia Cookies.
     * Dapatkan ID ini dari endpoint /destination/province -> /destination/city
     * menggunakan Komerce API V2 dan sesuaikan dengan data di dashboard kamu.
     */
    private const ORIGIN_CITY_ID = '256'; // ID Kota Jember di Komerce API V2

    /**
     * Base URL Komerce API V2 (RajaOngkir baru).
     */
    private string $apiBase;
    private string $apiKey;

    public function __construct()
    {
        $this->apiBase = rtrim(env('RAJAONGKIR_BASE_URL', 'https://rajaongkir.komerce.id/api/v1'), '/');
        $this->apiKey  = env('RAJAONGKIR_API_KEY', 'lly30A0X39799562540ab1cdFputdZKg');
    }

    // =========================================================================
    // HELPER: Buat instance HTTP client Komerce API agar tidak perlu repeat
    // =========================================================================
    private function apiClient()
    {
        return Http::withoutVerifying()
            ->withHeaders(['key' => $this->apiKey])
            ->withOptions([
                'curl' => [
                    CURLOPT_IPRESOLVE      => CURL_IPRESOLVE_V4,
                    CURLOPT_CONNECTTIMEOUT => 30,
                    CURLOPT_TIMEOUT        => 30,
                ],
            ]);
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        // 1. VALIDASI: Cek apakah profil alamat user sudah lengkap
        if (!$user->provinsi_id || !$user->kota_id || !$user->detail_alamat) {
            return redirect()->route('profile.edit') // Sesuaikan dengan nama rute edit profilmu
                ->with('error', 'Halo! Tolong lengkapi alamat pengiriman Anda terlebih dahulu sebelum checkout ya.');
        }

        $items = [];
        $totalHarga = 0;
        $totalBerat = 0; // Asumsi berat per toples, misal 500 gram

        if ($request->has('product_id')) {
            $product = Product::findOrFail($request->product_id);
            $qty = $request->input('qty', 1);
            $beratProduk = $product->berat ?? 100;

            $items[] = [
                'id'       => $product->id,
                'nama'     => $product->nama,     // Dibenarkan kembali ke 'nama'
                'gambar'   => $product->foto,   // Dibenarkan kembali ke 'gambar'
                'harga'    => $product->harga,    // Dibenarkan kembali ke 'harga'
                'qty'      => $qty,
                'subtotal' => $product->harga * $qty,
                'berat'    => $product->berat * $qty
            ];

            $totalHarga = $product->harga * $qty;
            $totalBerat = $product->berat * $qty;
        }
        // 3. LOGIKA JIKA DARI "KERANJANG BELANJA"
        else {
            $carts = Cart::with('product')->where('user_id', $user->id)->get();

            if ($carts->isEmpty()) {
                return redirect()->route('cart.index')->with('error', 'Keranjang belanja Anda masih kosong.');
            }

            foreach ($carts as $cart) {
                // Pengecekan produk (biar tidak error attempt to read null)
                if (!$cart->product) {
                    $cart->delete();
                    continue;
                }

                $hargaProduk = $cart->product->harga ?? 0;

                $jumlahBeli = $cart->jumlah ?? $cart->qty ?? 1;

                $subtotal = $hargaProduk * $jumlahBeli;

                $items[] = [
                    'id'       => $cart->product->id,
                    'nama'     => $cart->product->nama,
                    'gambar'   => $cart->product->foto,
                    'harga'    => $hargaProduk,
                    'qty'      => $jumlahBeli,
                    'subtotal' => $subtotal,
                    'berat'    => ($cart->product->berat ?? 500) * $jumlahBeli
                ];

                $totalHarga += $subtotal;
                $totalBerat += ($cart->product->berat ?? 500) * $jumlahBeli;
            }
        }
        $sumberOrder = $request->has('product_id') ? 'langsung' : 'keranjang';

        // 4. Kirim data ke tampilan halaman Checkout
        return view('transaksi.Checkout', compact('items', 'totalHarga', 'totalBerat', 'user', 'sumberOrder'));
    }

    // =========================================================================
    // HELPER: Validasi kelengkapan alamat profil user
    // Mengembalikan true jika lengkap, false jika ada field yang kosong.
    // =========================================================================
    private function isProfileAddressComplete(): bool
    {
        $user = Auth::user();

        // Sesuaikan nama kolom dengan struktur tabel `users` kamu.
        // Minimal: provinsi_id, kota_id, dan alamat_detail harus terisi.
        return ! empty($user->provinsi_id)
            && ! empty($user->kota_id)
            && ! empty($user->alamat_detail)
            && ! empty($user->no_hp);
    }

    public function hitungOngkir(Request $request)
    {
        $request->validate([
            'destination' => 'required',
            'weight'      => 'required|numeric|min:1',
        ]);

        try {
            $kurir = $request->courier;
            if (empty($kurir)) {
                $kurir = 'jnt';
            }

            $response = $this->apiClient()
                ->asForm()
                ->post($this->apiBase . '/calculate/domestic-cost', [
                    'origin'      => self::ORIGIN_CITY_ID,
                    'destination' => $request->destination,
                    'weight'      => $request->weight,
                    'courier'     => $kurir,
                ]);

            $data = $response->json();

            if (! $response->successful()) {
                $errorMsg = $data['meta']['message']
                            ?? ($data['status']['description'] ?? null)
                            ?? ($data['message'] ?? null)
                            ?? 'Gagal menghubungi API ongkir.';

                return response()->json([
                    'success' => false,
                    'message' => 'Tolak dari API: ' . $errorMsg,
                    'debug'   => $data
                ], $response->status());
            }

            $results = $data['data'] ?? [];

            return response()->json([
                'success' => true,
                'data'    => $results,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage(),
            ], 500);
        }
    }


    public function prosesPayment(Request $request)
    {
        $user = Auth::user();

        // Bikin ID Transaksi Unik sesuai formatmu, misal: #TRX-ABCD123
        $transaksiId = 'TRX-' . strtoupper(Str::random(7));

        // Tentukan alamat pengiriman berdasarkan pilihan
        $alamatPengiriman = ($request->tipe_pengiriman == 'pickup')
                            ? 'Ambil di Toko Alia Cookies'
                            : $request->alamat_lengkap; // Pastikan dari frontend ngirim ini

        // 1. Simpan Data ke Tabel `transaksis`
        $transaksi = Transaction::create([
            'id' => $transaksiId, // Pastikan di Model Transaksi: public $incrementing = false; protected $keyType = 'string';
            'user_id' => $user->id,
            'total_harga' => $request->total_harga,
            'payment_status' => 'Unpaid',
            'status_pesanan' => 'Proses',
            'shipping_address' => $alamatPengiriman,
            'shipping_cost' => $request->ongkir,
            'courier' => $request->kurir,
            'tanggal_transaksi' => now()->format('d M Y'),
        ]);

        // 2. Simpan Data ke Tabel `detail_transaksis`
        $items = json_decode($request->items, true);
        foreach ($items as $item) {
            TransactionItem::create([
                'id_transaksi' => $transaksi->id,
                'id_produk' => $item['id'], // Sesuaikan dengan key array $items di fungsi index()
                'nama_produk' => $item['nama'],
                'jumlah' => $item['qty'],
                'harga_satuan' => $item['harga'],
                'subtotal' => $item['subtotal']
            ]);
            Product::where('id', $item['id'])->decrement('stok', $item['qty']);
        }

        // 3. Kosongkan keranjang jika sumbernya dari keranjang
        if ($request->sumberOrder == 'keranjang') {
            Cart::where('user_id', $user->id)->delete();
        }

        // 4. Hubungkan dengan Midtrans
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = false;
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => $transaksi->id,
                'gross_amount' => $transaksi->total_harga,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone ?? '08000000000',
            ],
        ];

        try {
            // Dapatkan Token Midtrans
            $snapToken = Snap::getSnapToken($params);

            // Update snap_token ke database transaksis
            $transaksi->update(['snap_token' => $snapToken]);

            return response()->json([
                'success' => true,
                'snap_token' => $snapToken
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function callback(Request $request)
    {
        // 1. Ambil data penting dari laporan Midtrans
        $status_code      = $request->status_code;
        $order_id         = $request->order_id;
        $gross_amount     = $request->gross_amount;
        $transaction_status = $request->transaction_status;
        $signature_key    = $request->signature_key;

        // 2. Validasi Keamanan (Signature Key) agar data tidak bisa dimanipulasi orang lain
        $serverKey = env('MIDTRANS_SERVER_KEY');
        $localSignature = hash("sha512", $order_id . $status_code . $gross_amount . $serverKey);

        if ($signature_key !== $localSignature) {
            return response()->json(['message' => 'Signature tidak valid'], 403);
        }

        // 3. Cari data transaksi di database berdasarkan order_id
        $transaksi = Transaction::find($order_id);

        if (!$transaksi) {
            return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
        }

        // 4. Ubah status di database sesuai laporan Midtrans
        if ($transaction_status == 'settlement' || $transaction_status == 'capture') {
            // Jika sukses dibayar
            $transaksi->update(['payment_status' => 'Dibayar']);
        } elseif ($transaction_status == 'pending') {
            // Jika kustomer baru memunculkan kode bayar tapi belum transfer
            $transaksi->update(['payment_status' => 'Menunggu Pembayaran']);
        } elseif (in_array($transaction_status, ['deny', 'expire', 'cancel'])) {
            // Jika gagal, kadaluwarsa, atau dibatalkan
            $transaksi->update(['payment_status' => 'Gagal']);
        }
    }

    public function showDetail($id)
    {
        // Cari transaksi beserta item-itemnya.
        // Asumsi relasi di model Transaction adalah public function items()
        // yang merujuk ke tabel detail_transaksis
        $transaksi = Transaction::with('items.product')->findOrFail($id);

        // Pastikan hanya pemilik yang bisa melihat transaksinya sendiri
        if ($transaksi->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        return view('transaksi.DetailTransaksi', compact('transaksi'));
    }

    public function indexTransaksi()
    {
        $user = Auth::user();

        // Menggunakan nama model Transaksi (sesuaikan huruf besar kecilnya dengan proyekmu, misal: Transaction atau Transaksi)
        $query = \App\Models\Transaction::with(['items.product', 'user'])->latest();

        if ($user->role === 'admin') {
            // Admin: Lihat semua transaksi masuk, batasi 10 per halaman
            $transaksis = $query->paginate(10);
        } else {
            // Customer: Hanya lihat transaksi milik sendiri
            $transaksis = $query->where('user_id', $user->id)->paginate(10);
        }

        // Mengarah ke folder transaksi file index.blade.php
        return view('transaksi.index', compact('transaksis'));
    }

    public function detailTransaksi($id)
    {
        $user = Auth::user();
        $transaksi = \App\Models\Transaction::with(['items.product', 'user'])->findOrFail($id);

        // Guard: Customer biasa tidak boleh intip transaksi orang lain
        if ($user->role !== 'admin' && $transaksi->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke transaksi ini.');
        }

        return view('transaksi.DetailTransaksi', compact('transaksi'));
    }

    // 2. PROSES UPDATE RESI & STATUS (KHUSUS ADMIN)
    public function updateResi(Request $request, $id)
    {
        $transaksi = \App\Models\Transaction::findOrFail($id);

        // 1. GEMBOK TOTAL: Jika status database sudah 'Selesai', tidak boleh diotak-atik lagi
        if (strtolower($transaksi->status_pesanan) === 'selesai') {
            return redirect()->back()->with('error', 'Pesanan ini sudah Selesai. Status tidak dapat diubah kembali!');
        }

        // 2. VALIDASI INPUT: Status wajib diisi, No Resi wajib diisi jika belum ada di database
        $request->validate([
            'status_pesanan' => 'required|in:Proses,Dikirim,Selesai',
            'resi_number' => $transaksi->resi_number ? 'nullable|string' : 'required|string',
        ], [
            'status_pesanan.required' => 'Status pesanan wajib dipilih!',
            'status_pesanan.in' => 'Pilihan status tidak valid.',
            'resi_number.required' => 'Nomor resi pengiriman wajib diisi!',
        ]);

        $statusLama = strtolower($transaksi->status_pesanan ?? 'proses');
        $statusBaru = strtolower($request->status_pesanan);

        // 3. VALIDASI ALUR: Tidak boleh mundur dari 'Dikirim' kembali ke 'Proses'
        if ($statusLama === 'dikirim' && $statusBaru === 'proses') {
            return redirect()->back()->with('error', 'Gagal! Pesanan yang sudah dikirim tidak bisa dikembalikan ke status Proses.');
        }

        // 4. GEMBOK NOMOR RESI: Jika sudah pernah terisi, paksa pakai resi lama (tidak boleh diubah)
        $resiFinal = $transaksi->resi_number;
        if (!$transaksi->resi_number) {
            // Jika resi masih kosong di DB, baru pasang resi dari input form
            $resiFinal = $request->resi_number;
        }

        // Eksekusi pembaruan ke database
        $transaksi->update([
            'status_pesanan' => $request->status_pesanan,
            'resi_number' => $resiFinal,
        ]);

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui!');
    }
}
