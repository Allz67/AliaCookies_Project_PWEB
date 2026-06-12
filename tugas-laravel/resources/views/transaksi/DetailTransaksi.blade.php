@extends(Auth::user()->role == 'admin' ? 'layouts.app' : 'layouts.store')

@section('title', 'Detail Pesanan — Alia Cookies')

@section('content')

@if(Auth::user()->role != 'admin')
    {{-- BREADCRUMB CUSTOMER --}}
    <div class="sc-breadcrumb">
        <div class="sc-breadcrumb__inner">
            <a href="{{ route('home') }}">Beranda</a>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
            <a href="{{ route('transaksi.index') }}">Daftar Transaksi</a>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
            <span>Detail {{ $transaksi->id }}</span>
        </div>
    </div>
@endif

<div class="co-page">
    <div class="co-header">
        <div class="co-header__eyebrow">PESANAN {{ $transaksi->id }}</div>
        <h1 class="co-header__title">Detail <span>Pesanan</span></h1>
    </div>

    <div class="co-grid">
        {{-- ===================================================================
             KOLOM KIRI: PRODUK & RINGKASAN HARGA
             =================================================================== --}}
        <div class="co-main-col">
            <div class="co-card">
                <div class="co-card__head">
                    <div class="co-card__head-icon"></div>
                    <div class="co-card__head-title">Produk yang Dibeli</div>
                </div>
                <div class="co-card__body">
                    <table class="order-items">
                        @foreach ($transaksi->items as $item)
                        <tr>
                            <td>
                                <div class="item-cell">
                                    <div class="item-thumb">
                                        @if ($item->product && $item->product->foto)
                                            <img src="{{ asset('storage/' . $item->product->foto) }}" alt="{{ $item->nama_produk }}">
                                        @else
                                            🍪
                                        @endif
                                    </div>
                                    <div class="item-details">
                                        <div class="item-name">{{ $item->nama_produk }}</div>
                                        <div class="item-qty">{{ $item->jumlah }}x @ Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</div>
                                    </div>
                                    <div class="item-price" style="font-weight: 600;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </table>

                    <div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid #f0e6d2;">
                        <table class="cost-rows" style="margin: 0;">
                            <tr>
                                <td>Total Produk</td>
                                <td>Rp {{ number_format($transaksi->total_harga - $transaksi->shipping_cost, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td>Ongkos Kirim</td>
                                <td>
                                    @if(strtolower($transaksi->status_pesanan) === 'pending')
                                        <span style="color: #d97706; font-style: italic; font-size: 12px;">Menunggu Konfirmasi</span>
                                    @else
                                        Rp {{ number_format($transaksi->shipping_cost, 0, ',', '.') }}
                                    @endif
                                </td>
                            </tr>
                            <tr class="divider"><td colspan="2"></td></tr>
                            <tr class="total-row">
                                <td>Total Tagihan</td>
                                <td class="total-amount">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
                            </tr>
                        </table>
                    </div>

                </div>
            </div>
        </div>

        {{-- ===================================================================
             KOLOM KANAN: INFORMASI STATUS & DETAIL
             =================================================================== --}}
        <div class="co-summary-col">
            <div class="co-card">

                {{-- HEADER CARD + TOMBOL KELOLA ADMIN --}}
                <div class="co-card__head" style="display: flex; justify-content: space-between; align-items: center;">
                    <div style="display: flex; align-items: center;">
                        <div class="co-card__head-icon"></div>
                        <div class="co-card__head-title">Informasi Pesanan</div>
                    </div>

                    {{-- Tombol kelola hanya muncul jika Admin DAN status BELUM Selesai & BUKAN Unpaid --}}
                    @if(Auth::user()->role == 'admin' && !in_array(strtolower($transaksi->status_pesanan), ['selesai', 'unpaid', 'dibatalkan']))
                        <button type="button" id="btn-toggle-edit" style="background: #f5ede0; border: 1px solid #d4c5b0; color: #5a4a33; padding: 4px 12px; border-radius: 6px; font-size: 12px; cursor: pointer; font-weight: bold; transition: 0.2s;">
                            Kelola Pesanan
                        </button>
                    @endif
                </div>

                <div class="co-card__body">

                    <form action="{{ route('transaksi.updateResi', $transaksi->id) }}" method="POST">
                        @csrf

                        {{-- 1. BAGIAN STATUS --}}
                        <div style="padding-bottom: 16px; margin-bottom: 16px; border-bottom: 1px dashed #d4c5b0;">
                            {{-- STATUS PEMBAYARAN --}}
                            <div style="display: flex; justify-content: space-between; margin-bottom: 12px; align-items: center;">
                                <span style="color: #8a7a63; font-size: 13px;">Status Pembayaran</span>
                                @if(in_array($transaksi->payment_status, ['Paid', 'Dibayar']))
                                    <span style="font-weight: 600; font-size: 14px; color: #2e7d32; background: #e8f5e9; padding: 2px 8px; border-radius: 4px;">LUNAS</span>
                                @elseif(in_array($transaksi->payment_status, ['Unpaid', 'Menunggu Pembayaran']))
                                    <span style="font-weight: 600; font-size: 14px; color: #d32f2f; background: #ffebee; padding: 2px 8px; border-radius: 4px;">BELUM DIBAYAR</span>
                                @else
                                    <span style="font-weight: 600; font-size: 14px; color: #555; background: #eee; padding: 2px 8px; border-radius: 4px;">GAGAL/BATAL</span>
                                @endif
                            </div>

                            {{-- STATUS PESANAN --}}
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span style="color: #8a7a63; font-size: 13px;">Status Pesanan</span>
                                <span style="font-weight: 600; font-size: 14px; color: #d97706; padding: 2px 8px; background: #fffbeb; border-radius: 4px;">
                                    {{ strtoupper($transaksi->status_pesanan ?? 'PROSES') }}
                                </span>
                            </div>
                        </div>

                        {{-- 2. BAGIAN PENERIMA --}}
                        <div style="padding-bottom: 16px; margin-bottom: 16px; border-bottom: 1px dashed #d4c5b0;">
                            <div style="color: #8a7a63; font-size: 12px; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px;">Penerima</div>
                            <div style="font-weight: 600; color: #444;">{{ $transaksi->user->name ?? 'Customer' }}</div>
                            <div style="font-size: 13px; color: #666; margin-bottom: 12px;">{{ $transaksi->user->phone ?? '-' }}</div>

                            <div style="color: #8a7a63; font-size: 12px; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px;">Alamat Pengiriman</div>
                            <div style="font-size: 13px; line-height: 1.5; color: #444;">
                                {{ $transaksi->shipping_address }}
                            </div>
                        </div>

                        {{-- 3. BAGIAN TEKNIS PENGIRIMAN & RESI --}}
                        <div style="padding-bottom: 16px; border-bottom: 1px dashed #d4c5b0;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                <span style="color: #8a7a63; font-size: 13px;">Layanan Pengiriman</span>
                                <span style="font-weight: 600; font-size: 13px; color: #5a4a33; background: #e6dfd1; padding: 2px 6px; border-radius: 4px;">
                                    {{ strtoupper($transaksi->courier ?? 'MENUNGGU KONFIRMASI') }}
                                </span>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span style="color: #8a7a63; font-size: 13px;">Nomor Resi</span>
                                <span style="font-weight: 600; font-size: 14px; letter-spacing: 0.5px; color: #333;">
                                    {{ $transaksi->resi_number ?? '-' }}
                                </span>
                            </div>
                        </div>

                        {{-- ============================================================
                             4. FORM KENDALI ADMIN (DINAMIS SESUAI STATUS)
                             ============================================================ --}}
                        @if(Auth::user()->role == 'admin')
                            <div id="form-edit-admin" style="display: none; margin-top: 16px;">

                                {{-- STATE 1: PENDING (Input Ongkir) --}}
                                @if(strtolower($transaksi->status_pesanan) === 'pending')
                                    <div style="background: #fffcf5; padding: 16px; border-radius: 8px; border: 1px solid #f0e6d2;">
                                        <div style="margin-bottom: 12px;">
                                            <label style="font-size: 12px; color: #8a7a63; display: block; margin-bottom: 4px; font-weight: bold;">Nama Ekspedisi / Kurir</label>
                                            <input type="text" name="courier" style="width: 100%; padding: 10px; border: 1px solid #ccbc9f; border-radius: 6px; font-size: 13px;" placeholder="Cth: JNE Reguler / Kurir Toko" required>
                                        </div>
                                        <div>
                                            <label style="font-size: 12px; color: #8a7a63; display: block; margin-bottom: 4px; font-weight: bold;">Nominal Ongkos Kirim (Rp)</label>
                                            <input type="number" name="shipping_cost" style="width: 100%; padding: 10px; border: 1px solid #ccbc9f; border-radius: 6px; font-size: 13px;" placeholder="Cth: 15000" min="0" required>
                                        </div>
                                    </div>

                                {{-- STATE 2: PROSES (Input Resi & Kirim) --}}
                                @elseif(strtolower($transaksi->status_pesanan) === 'proses')
                                    <div style="background: #f0fdf4; padding: 16px; border-radius: 8px; border: 1px solid #bbf7d0;">
                                        <div style="margin-bottom: 12px;">
                                            <label style="font-size: 12px; color: #166534; display: block; margin-bottom: 4px; font-weight: bold;">Ubah Status</label>
                                            <select name="status_pesanan" style="width: 100%; padding: 10px; border: 1px solid #86efac; border-radius: 6px; font-size: 13px; font-weight: bold; color: #15803d; outline: none;">
                                                <option value="Dikirim" selected>DIKIRIM</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label style="font-size: 12px; color: #166534; display: block; margin-bottom: 4px; font-weight: bold;">Nomor Resi</label>
                                            <input type="text" name="resi_number" style="width: 100%; padding: 10px; border: 1px solid #86efac; border-radius: 6px; font-size: 13px;" placeholder="Ketik nomor resi valid..." required>
                                        </div>
                                    </div>

                                {{-- STATE 3: DIKIRIM (Selesaikan Pesanan) --}}
                                @elseif(strtolower($transaksi->status_pesanan) === 'dikirim')
                                    <div style="background: #eff6ff; padding: 16px; border-radius: 8px; border: 1px solid #bfdbfe;">
                                        <label style="font-size: 12px; color: #1e3a8a; display: block; margin-bottom: 4px; font-weight: bold;">Tandai Pesanan</label>
                                        <select name="status_pesanan" style="width: 100%; padding: 10px; border: 1px solid #93c5fd; border-radius: 6px; font-size: 13px; font-weight: bold; color: #1d4ed8; outline: none;">
                                            <option value="Selesai" selected>SELESAI</option>
                                        </select>
                                    </div>
                                @endif

                                {{-- TOMBOL SIMPAN ADMIN --}}
                                <div style="margin-top: 16px;">
                                    <button type="submit" class="btn-bayar" style="width: 100%; background: #d97706; border-bottom: 4px solid #b45309; margin: 0;">
                                        @if(strtolower($transaksi->status_pesanan) === 'pending')
                                            Konfirmasi Ongkir & Tagih Customer
                                        @elseif(strtolower($transaksi->status_pesanan) === 'proses')
                                            Simpan Resi & Kirim Pesanan
                                        @else
                                            Tandai Sebagai Selesai
                                        @endif
                                    </button>
                                    <button type="button" id="btn-batal-edit" style="width: 100%; margin-top: 8px; padding: 10px; background: transparent; color: #8a7a63; border: 1px solid #d4c5b0; border-radius: 6px; font-size: 13px; cursor: pointer; font-weight: 600;">
                                        Batal
                                    </button>
                                </div>
                            </div>

                            {{-- STATE 1.5: UNPAID (Info khusus Admin saat nunggu dibayar) --}}
                            @if(strtolower($transaksi->status_pesanan) === 'unpaid')
                                <div style="margin-top: 24px; text-align: center; padding: 16px; background: #f9fafb; border-radius: 8px; border: 1px dashed #d1d5db;">
                                    <span style="font-size: 13px; color: #6b7280; font-weight: 500;">Ongkir sudah dikonfirmasi.<br>Sistem sedang menunggu Kustomer menyelesaikan pembayaran.</span>
                                </div>
                            @endif
                        @endif
                    </form>

                    {{-- ============================================================
                         5. TOMBOL AKSI (KHUSUS CUSTOMER)
                         ============================================================ --}}
                    @if(Auth::user()->role != 'admin')
                        <div style="margin-top: 24px;">

                            {{-- STATE 1: PENDING / UNPAID (Bisa Bayar & Batal) --}}
                            @if(in_array(strtolower($transaksi->status_pesanan), ['pending', 'unpaid']))

                                {{-- Tombol Bayar --}}
                                @if(strtolower($transaksi->status_pesanan) === 'pending')
                                    <button type="button" class="btn-bayar" style="width: 100%; background-color: #cccccc; color: #777777; border-bottom: 4px solid #aaaaaa; cursor: not-allowed; margin-bottom: 8px;" disabled>
                                        Lanjut Bayar
                                    </button>
                                    <div style="text-align: center; font-size: 12px; color: #8a7a63; margin-bottom: 16px;">
                                        Tombol aktif setelah Admin mengkonfirmasi ongkos kirim.
                                    </div>
                                @else
                                    <button type="button" id="pay-button" class="btn-bayar" style="width: 100%; margin-bottom: 16px;">
                                        Lanjut Bayar Sekarang
                                    </button>
                                @endif

                                {{-- Tombol Batalkan Pesanan (UI disamakan dengan btn-bayar) --}}
                                <form id="form-batal-pesanan" action="{{ route('transaksi.updateResi', $transaksi->id) }}" method="POST" onsubmit="return confirmCancel(event);">
                                    @csrf
                                    <input type="hidden" name="status_pesanan" value="Dibatalkan">
                                    <button type="submit" class="btn-bayar" style="width: 100%; background-color: #fef2f2; color: #dc2626; border: 1px solid #fca5a5; border-bottom: 4px solid #f87171; margin-bottom: 0;">
                                        Batalkan Pesanan
                                    </button>
                                </form>
                            @endif

                            {{-- STATE 2: PROSES / DIKIRIM (Menunggu Paket Datang) --}}
                            @if(in_array(strtolower($transaksi->status_pesanan), ['proses', 'dikirim']))

                                @if(strtolower($transaksi->status_pesanan) === 'dikirim')
                                    {{-- Tombol Menyala Hijau (Siap Diterima) --}}
                                    <form action="{{ route('transaksi.updateResi', $transaksi->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status_pesanan" value="Selesai">
                                        <button type="submit" class="btn-bayar" style="width: 100%; background-color: #15803d; border-bottom: 4px solid #166534; margin-bottom: 0;">
                                            Pesanan Diterima (Selesai)
                                        </button>
                                    </form>
                                @else
                                    {{-- Tombol Mati Abu-abu (Paket masih diproses admin) --}}
                                    <button type="button" class="btn-bayar" style="width: 100%; background-color: #cccccc; color: #777777; border-bottom: 4px solid #aaaaaa; cursor: not-allowed; margin-bottom: 0;" disabled>
                                        Pesanan Diterima
                                    </button>
                                    <div style="text-align: center; font-size: 12px; color: #8a7a63; margin-top: 8px;">
                                        Tombol aktif setelah pesanan berstatus dikirim.
                                    </div>
                                @endif

                            @endif

                        </div>
                    @endif

                </div>
            </div>
        </div>

    </div>
</div>

<div class="ct-modal-overlay" id="cancelModal" style="display:none">
    <div class="ct-modal">
        <h3>Batalkan pesanan ini?</h3>
        <p>Pesanan yang dibatalkan tidak dapat dikembalikan lagi, dan stok akan langsung dikembalikan ke toko.</p>
        <div class="ct-modal__actions">
            <button type="button" class="ct-modal__cancel" onclick="closeCancelModal()">Tutup</button>
            <button type="button" class="ct-modal__confirm" style="background-color: #dc2626;" onclick="submitCancelForm()">Ya, Batalkan</button>
        </div>
    </div>
</div>

{{-- SCRIPT TOGGLE FORM ADMIN --}}
@if(Auth::user()->role == 'admin')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const btnToggle = document.getElementById('btn-toggle-edit');
        const btnBatal  = document.getElementById('btn-batal-edit');
        const formEdit  = document.getElementById('form-edit-admin');

        if(btnToggle) {
            btnToggle.addEventListener('click', function() {
                btnToggle.style.display = 'none';
                if(formEdit) formEdit.style.display = 'block';
            });
        }

        if(btnBatal) {
            btnBatal.addEventListener('click', function() {
                btnToggle.style.display = 'inline-block';
                if(formEdit) formEdit.style.display = 'none';
            });
        }
    });
</script>
@endif

{{-- SCRIPT PEMBAYARAN MIDTRANS CUSTOMER --}}
@if(Auth::user()->role != 'admin' && in_array(strtolower($transaksi->status_pesanan), ['unpaid', 'pending']))
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('SB-Mid-client-T3HNRf3mJEV_6ZD4') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const payButton = document.getElementById('pay-button');
            if (payButton) {
                payButton.addEventListener('click', function () {
                    snap.pay('{{ $transaksi->snap_token }}', {
                        onSuccess: function(result){
                            alert("Pembayaran sukses!");
                            window.location.reload();
                        },
                        onPending: function(result){
                            alert("Menunggu pembayaran Anda diselesaikan.");
                            window.location.reload();
                        },
                        onError: function(result){
                            alert("Pembayaran gagal atau dibatalkan!");
                        },
                        onClose: function(){
                            // Jangan lakukan reload di sini agar kustomer masih bisa melihat halaman
                        }
                    });
                });
            }
        });
    </script>
@endif

<script>
    // Fungsi untuk memunculkan Modal Batal
    function confirmCancel(e) {
        e.preventDefault(); // Tahan dulu formnya agar tidak langsung terkirim
        document.getElementById('cancelModal').style.display = 'flex';
        return false;
    }

    // Fungsi untuk menutup Modal
    function closeCancelModal() {
        document.getElementById('cancelModal').style.display = 'none';
    }

    // Fungsi jika tombol "Ya, Batalkan" ditekan
    function submitCancelForm() {
        // Ganti teks tombol biar kelihatan loading
        const btnConfirm = document.querySelector('#cancelModal .ct-modal__confirm');
        btnConfirm.innerText = 'Memproses...';
        btnConfirm.style.opacity = '0.7';

        // Kirim form pembatalan
        document.getElementById('form-batal-pesanan').submit();
    }

    // Tutup modal kalau kustomer klik area gelap di luar kotak putih
    document.addEventListener('DOMContentLoaded', function() {
        const cancelOverlay = document.getElementById('cancelModal');
        if(cancelOverlay) {
            cancelOverlay.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeCancelModal();
                }
            });
        }
    });
</script>

@endsection
