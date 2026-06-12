<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    // =========================================================================
    // INDEX — tampil halaman checkout
    // =========================================================================
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user->provinsi_id || !$user->kota_id || !$user->detail_alamat) {
            return redirect()->route('profile')
                ->with('error', 'Halo! Tolong lengkapi alamat pengiriman Anda terlebih dahulu sebelum checkout ya.');
        }

        $items      = [];
        $totalHarga = 0;
        $totalBerat = 0;

        if ($request->has('product_id')) {
            $product = Product::findOrFail($request->product_id);
            $qty     = $request->input('qty', 1);

            $items[] = [
                'id'       => $product->id,
                'nama'     => $product->nama,
                'gambar'   => $product->foto,
                'harga'    => $product->harga,
                'qty'      => $qty,
                'subtotal' => $product->harga * $qty,
                'berat'    => ($product->berat ?? 500) * $qty,
            ];

            $totalHarga = $product->harga * $qty;
            $totalBerat = ($product->berat ?? 500) * $qty;
        } else {
            $carts = Cart::with('product')->where('user_id', $user->id)->get();

            if ($carts->isEmpty()) {
                return redirect()->route('cart.index')->with('error', 'Keranjang belanja Anda masih kosong.');
            }

            foreach ($carts as $cart) {
                /** @var \App\Models\Cart $cart */
                if (!$cart->product) {
                    $cart->query()->delete();
                    continue;
                }

                $hargaProduk = $cart->product->harga ?? 0;
                $jumlahBeli  = $cart->jumlah ?? $cart->qty ?? 1;
                $subtotal    = $hargaProduk * $jumlahBeli;

                $items[] = [
                    'id'       => $cart->product->id,
                    'nama'     => $cart->product->nama,
                    'gambar'   => $cart->product->foto,
                    'harga'    => $hargaProduk,
                    'qty'      => $jumlahBeli,
                    'subtotal' => $subtotal,
                    'berat'    => ($cart->product->berat ?? 500) * $jumlahBeli,
                ];

                $totalHarga += $subtotal;
                $totalBerat += ($cart->product->berat ?? 500) * $jumlahBeli;
            }
        }

        $sumberOrder = $request->has('product_id') ? 'langsung' : 'keranjang';

        return view('transaksi.Checkout', compact('items', 'totalHarga', 'totalBerat', 'user', 'sumberOrder'));
    }

    // =========================================================================
    // PROSES PAYMENT (Checkout Manual)
    // =========================================================================
    public function prosesPayment(Request $request)
    {
        $user         = Auth::user();
        $transaksiId  = 'TRX-' . strtoupper(Str::random(7));
        $metode       = $request->tipe_pengiriman;

        $alamatPengiriman = ($metode == 'pickup')
            ? 'Ambil di Toko Alia Cookies'
            : $request->alamat_lengkap;

        // Pickup langsung ke Unpaid (karena ongkir 0), Delivery menunggu admin (Pending)
        $statusPesanan = ($metode == 'pickup') ? 'Unpaid' : 'Pending';

        $transaksi = Transaction::create([
            'id'               => $transaksiId,
            'user_id'          => $user->id,
            'total_harga'      => $request->total_harga,
            'payment_status'   => 'Unpaid',
            'status_pesanan'   => $statusPesanan,
            'shipping_address' => $alamatPengiriman,
            'shipping_cost'    => 0,
            'courier'          => ($metode == 'pickup') ? 'Pickup' : 'Menunggu Konfirmasi',
            'tanggal_transaksi'=> now()->format('d M Y'),
        ]);

        $items = json_decode($request->items, true);
        foreach ($items as $item) {
            TransactionItem::create([
                'id_transaksi' => $transaksi->id,
                'id_produk'    => $item['id'],
                'nama_produk'  => $item['nama'],
                'jumlah'       => $item['qty'],
                'harga_satuan' => $item['harga'],
                'subtotal'     => $item['subtotal'],
            ]);
            Product::query()->where('id', $item['id'])->decrement('stok', $item['qty']);
        }

        if ($request->sumberOrder == 'keranjang') {
            Cart::query()->where('user_id', $user->id)->delete();
        }

        // Jika Pickup, ongkir sudah pasti 0, langsung buatkan Snap Token Midtrans sekarang!
        if ($metode == 'pickup') {
            Config::$serverKey    = env('MIDTRANS_SERVER_KEY');
            Config::$isProduction = false;
            Config::$isSanitized  = true;
            Config::$is3ds        = true;

            $params = [
                'transaction_details' => [
                    'order_id'     => $transaksi->id,
                    'gross_amount' => (int) $transaksi->total_harga,
                ],
                'customer_details' => [
                    'first_name' => $user->name,
                    'email'      => $user->email,
                    'phone'      => $user->phone ?? $user->no_hp ?? '08000000000',
                ],
            ];
            $transaksi->update(['snap_token' => Snap::getSnapToken($params)]);
        }

        session()->flash('success', 'Pesanan berhasil dibuat! ' . ($metode == 'pickup' ? 'Silakan lakukan pembayaran.' : 'Menunggu konfirmasi ongkir dari Admin.'));

        return response()->json(['success' => true, 'order_id' => $transaksiId]);
    }

    // =========================================================================
    // CALLBACK MIDTRANS
    // =========================================================================
    public function callback(Request $request)
    {
        $serverKey      = env('MIDTRANS_SERVER_KEY');
        $localSignature = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($request->signature_key !== $localSignature) {
            return response()->json(['message' => 'Signature tidak valid'], 403);
        }

        $transaksi = Transaction::query()->find($request->order_id);
        if (! $transaksi) return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);

        $status = $request->transaction_status;
        if (in_array($status, ['settlement', 'capture'])) {
            // Jika dibayar, payment status berubah dan pesanan otomatis masuk ke Proses
            $transaksi->update([
                'payment_status' => 'Dibayar',
                'status_pesanan' => 'Proses'
            ]);
        } elseif ($status === 'pending') {
            $transaksi->update(['payment_status' => 'Menunggu Pembayaran']);
        } elseif (in_array($status, ['deny', 'expire', 'cancel'])) {
            $transaksi->update([
                'payment_status' => 'Gagal',
                'status_pesanan' => 'Dibatalkan'
            ]);
        }
    }

    // =========================================================================
    // HALAMAN TRANSAKSI (Admin & Customer)
    // =========================================================================
    public function indexTransaksi(Request $request) // <-- Wajib tambah Request $request
    {
        $user  = Auth::user();

        // 1. Mulai Query dasar
        $query = Transaction::with(['items.product', 'user']);

        // 2. Filter berdasarkan Role (Customer hanya melihat miliknya sendiri)
        if ($user->role !== 'admin') {
            $query->where('user_id', $user->id);
        }

        // 3. LOGIKA BARU: Filter berdasarkan status dari Dropdown
        if ($request->has('status') && $request->status != '') {
            $status = $request->status;

            // Khusus untuk 'unpaid', kita cek payment_status-nya
            if ($status == 'unpaid') {
                $query->whereIn('payment_status', ['Unpaid', 'Menunggu Pembayaran', 'Pending']);
            } else {
                // Untuk status lain (proses, dikirim, selesai, dibatalkan), cek status_pesanan-nya
                $query->where('status_pesanan', $status);
            }
        }

        // 4. Urutkan dari yang terbaru dan paginate
        $transaksis = $query->latest()->paginate(10);

        // 5. Bawa query parameter saat pagination agar filter tidak hilang saat pindah halaman
        $transaksis->appends($request->query());

        return view('transaksi.index', compact('transaksis'));
    }

    public function detailTransaksi($id)
    {
        $user      = Auth::user();
        $transaksi = Transaction::with(['items.product', 'user'])->findOrFail($id);

        if ($user->role !== 'admin' && $transaksi->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke transaksi ini.');
        }

        // ========================================================
        // FITUR SELF-HEALING (SINKRONISASI OTOMATIS)
        // Mencegah bug nyangkut saat testing manual di Localhost
        // ========================================================
        $paymentStatus = strtolower($transaksi->payment_status);
        $orderStatus   = strtolower($transaksi->status_pesanan);

        // Jika payment sudah 'Paid' / 'Dibayar', tapi status pesanan masih nyangkut di 'Unpaid'
        if (in_array($paymentStatus, ['paid', 'dibayar', 'settlement']) && $orderStatus === 'unpaid') {
            $transaksi->status_pesanan = 'Proses';
            $transaksi->save();
        }

        return view('transaksi.DetailTransaksi', compact('transaksi'));
    }

    public function showDetail($id) { return $this->detailTransaksi($id); }

    // =========================================================================
    // UPDATE ONGKIR & RESI (Logika Admin) + SNAP TOKEN GENERATOR
    // =========================================================================
    public function updateResi(Request $request, $id)
    {
        $transaksi  = Transaction::findOrFail($id);
        $statusLama = strtolower($transaksi->status_pesanan);
        $statusBaru = strtolower($request->status_pesanan ?? $statusLama);

        if (in_array($statusLama, ['selesai', 'dibatalkan'])) {
            return redirect()->back()->with('error', 'Pesanan yang sudah Selesai/Batal tidak dapat diubah kembali!');
        }

        $urutanStatus = ['pending' => 1, 'unpaid' => 2, 'proses' => 3, 'dikirim' => 4, 'selesai' => 5, 'dibatalkan' => 99];
        if (isset($urutanStatus[$statusBaru]) && isset($urutanStatus[$statusLama])) {
            if ($urutanStatus[$statusBaru] < $urutanStatus[$statusLama] && $statusBaru !== 'dibatalkan') {
                return redirect()->back()->with('error', 'Status pesanan tidak boleh mundur.');
            }
        }

        // 1. ADMIN INPUT ONGKIR (Hanya saat Pending) -> Otomatis buat Token Midtrans
        if ($request->has('shipping_cost') && $statusLama === 'pending') {
            $transaksi->shipping_cost = $request->shipping_cost;
            $transaksi->courier       = $request->courier ?? $transaksi->courier;

            $totalProduk              = $transaksi->items->sum('subtotal');
            $transaksi->total_harga   = $totalProduk + $request->shipping_cost;
            $transaksi->status_pesanan= 'Unpaid';

            // Generate Token Midtrans karena tagihan akhirnya sudah fix!
            Config::$serverKey    = env('MIDTRANS_SERVER_KEY');
            Config::$isProduction = false;
            Config::$isSanitized  = true;
            Config::$is3ds        = true;

            $params = [
                'transaction_details' => [
                    'order_id'     => $transaksi->id,
                    'gross_amount' => (int) $transaksi->total_harga,
                ],
                'customer_details' => [
                    'first_name' => $transaksi->user->name,
                    'email'      => $transaksi->user->email,
                    'phone'      => $transaksi->user->phone ?? $transaksi->user->no_hp ?? '08000000000',
                ],
            ];
            $transaksi->snap_token = Snap::getSnapToken($params);
        }

        // 2. ADMIN INPUT RESI (Hanya saat Proses -> Dikirim)
        if ($request->has('resi_number') && in_array($statusLama, ['proses', 'dikirim'])) {
            $transaksi->resi_number = $request->resi_number;
            if ($statusLama === 'proses' && $request->resi_number) {
                $transaksi->status_pesanan = 'Dikirim';
            }
        }

        if ($request->has('status_pesanan')) {
            $transaksi->status_pesanan = $request->status_pesanan;

            // JIKA DIBATALKAN -> KEMBALIKAN STOK PRODUK OTOMATIS
            if (strtolower($request->status_pesanan) === 'dibatalkan' && $statusLama !== 'dibatalkan') {
                $transaksi->payment_status = 'Failed';

                // Looping semua produk yang dibeli di transaksi ini, lalu tambah stoknya kembali
                foreach ($transaksi->items as $item) {
                    \App\Models\Product::query()->where('id', $item->id_produk)->increment('stok', $item->jumlah);
                }
            }
        }

        $transaksi->save();

        return redirect()->back()->with('success', 'Data pesanan berhasil diperbarui!');
    }
}
