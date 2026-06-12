<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Tampilkan halaman cart milik user yang sedang login.
     */
    public function index()
    {
        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        // Hitung total harga
        $subtotal = $cartItems->sum(function ($item) {
            return $item->jumlah * ($item->product->harga ?? 0);
        });

        return view('cart', compact('cartItems', 'subtotal'));
    }

    /**
     * Tambah produk ke cart.
     * Kalau produk sudah ada di cart, tambah jumlahnya.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_produk' => ['required', 'exists:products,id'],
            'jumlah'    => ['sometimes', 'integer', 'min:1'],
        ]);

        $produk = Product::where('is_active', 1)
                         ->whereNull('deleted_at')
                         ->findOrFail($request->id_produk);

        $jumlah = $request->input('jumlah', 1);

        // Cek apakah sudah ada di cart
        $cartItem = Cart::where('user_id', Auth::id())
                        ->where('id_produk', $produk->id)
                        ->first();

        if ($cartItem) {
            $newJumlah = $cartItem->jumlah + $jumlah;

            // Jangan melebihi stok
            if ($newJumlah > $produk->stok) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Jumlah melebihi stok yang tersedia (' . $produk->stok . ' ' . $produk->satuan . ').'
                ]);
            }

            $cartItem->update(['jumlah' => $newJumlah]);
        } else {
            if ($jumlah > $produk->stok) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Jumlah melebihi stok yang tersedia.'
                ]);
            }

            Cart::create([
                'user_id'   => Auth::id(),
                'id_produk' => $produk->id,
                'jumlah'    => $jumlah,
            ]);
        }

        // Balas dengan JSON agar dibaca oleh JavaScript
        return response()->json([
            'status'  => 'success',
            'message' => $produk->nama . ' berhasil ditambahkan ke keranjang!'
        ]);
    }

    /**
     * Update jumlah item di cart (tombol + / -).
     */
    public function update(Request $request, Cart $cart)
    {
        // Pastikan item ini milik user yang login
        abort_if($cart->user_id !== Auth::id(), 403);

        $request->validate([
            'jumlah' => ['required', 'integer', 'min:1'],
        ]);

        $produk = $cart->product;

        if ($request->jumlah > $produk->stok) {
            return response()->json([
                'success' => false,
                'message' => 'Stok tidak mencukupi. Tersedia: ' . $produk->stok . ' ' . $produk->satuan,
            ], 422);
        }

        $cart->update(['jumlah' => $request->jumlah]);

        $subtotal    = $cart->jumlah * $produk->harga;
        $cartTotal   = Cart::with('product')
                           ->where('user_id', Auth::id())
                           ->get()
                           ->sum(fn($i) => $i->jumlah * ($i->product->harga ?? 0));

        return response()->json([
            'success'    => true,
            'subtotal'   => $subtotal,
            'cart_total' => $cartTotal,
            'jumlah'     => $cart->jumlah,
        ]);
    }

    /**
     * Hapus satu item dari cart.
     */
    public function destroy(Cart $cart)
    {
        abort_if($cart->user_id !== Auth::id(), 403);

        $nama = $cart->product->nama ?? 'Item';
        $cart->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', $nama . ' dihapus dari keranjang.');
    }

    /**
     * Hapus semua item di cart milik user (clear cart).
     */
    public function clear()
    {
        Cart::where('user_id', Auth::id())->delete();

        return back()->with('success', 'Keranjang berhasil dikosongkan.');
    }
}
