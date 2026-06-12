@extends(Auth::user()->role == 'admin' ? 'layouts.app' : 'layouts.store')

@section('title', 'Detail Pesanan — Alia Cookies')

@section('content')

@if(Auth::user()->role != 'admin')
    {{-- BREADCRUMB --}}
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

                    {{-- Pindah ke kiri agar layout seimbang --}}
                    <div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid #f0e6d2;">
                        <table class="cost-rows" style="margin: 0;">
                            <tr>
                                <td>Total Produk</td>
                                <td>Rp {{ number_format($transaksi->total_harga - $transaksi->shipping_cost, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td>Ongkos Kirim</td>
                                <td>Rp {{ number_format($transaksi->shipping_cost, 0, ',', '.') }}</td>
                            </tr>
                            <tr class="divider"><td colspan="2"></td></tr>
                            <tr class="total-row">
                                <td>Total Pembayaran</td>
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

                    {{-- Tombol kelola hanya muncul jika Admin DAN status BELUM Selesai --}}
                    @if(Auth::user()->role == 'admin' && strtolower($transaksi->status_pesanan) !== 'selesai')
                        <button type="button" id="btn-toggle-edit" style="background: #f5ede0; border: 1px solid #d4c5b0; color: #5a4a33; padding: 4px 12px; border-radius: 6px; font-size: 12px; cursor: pointer; font-weight: bold; transition: 0.2s;">
                            Kelola Pesanan
                        </button>
                    @endif
                </div>

                <div class="co-card__body">

                    {{-- Alert Notifikasi Sukses --}}
                    @if(session('success'))
                        <div style="padding: 10px; background: #d1fae5; color: #065f46; border-radius: 6px; font-size: 13px; margin-bottom: 16px; font-weight: 500; text-align: center;">
                            ✅ {{ session('success') }}
                        </div>
                    @endif

                    {{-- Alert Notifikasi Gagal / Pelanggaran Validasi --}}
                    @if(session('error'))
                        <div style="padding: 10px; background: #ffebee; color: #c62828; border-radius: 6px; font-size: 13px; margin-bottom: 16px; font-weight: 500; text-align: center;">
                            ❌ {{ session('error') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div style="padding: 10px; background: #ffebee; color: #c62828; border-radius: 6px; font-size: 13px; margin-bottom: 16px; font-weight: 500;">
                            @foreach ($errors->all() as $error)
                                <p style="margin: 0; text-align: center;">⚠️ {{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <form action="{{ route('transaksi.updateResi', $transaksi->id) }}" method="POST">
                        @csrf

                        {{-- 1. BAGIAN STATUS --}}
                        <div style="padding-bottom: 16px; margin-bottom: 16px; border-bottom: 1px dashed #d4c5b0;">

                            {{-- STATUS PEMBAYARAN --}}
                            <div style="display: flex; justify-content: space-between; margin-bottom: 12px; align-items: center;">
                                <span style="color: #8a7a63; font-size: 13px;">Status Pembayaran</span>
                                @if(in_array($transaksi->payment_status, ['Paid']))
                                    <span style="font-weight: 600; font-size: 14px; color: #2e7d32; background: #e8f5e9; padding: 2px 8px; border-radius: 4px;">LUNAS</span>
                                @elseif(in_array($transaksi->payment_status, ['Unpaid']))
                                    <span style="font-weight: 600; font-size: 14px; color: #d32f2f; background: #ffebee; padding: 2px 8px; border-radius: 4px;">BELUM DIBAYAR</span>
                                @else
                                    <span style="font-weight: 600; font-size: 14px; color: #555; background: #eee; padding: 2px 8px; border-radius: 4px;">GAGAL/BATAL</span>
                                @endif
                            </div>

                            {{-- STATUS PESANAN (Tadi pembungkus div di bawah ini yang hilang!) --}}
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span style="color: #8a7a63; font-size: 13px;">Status Pesanan</span>

                                {{-- Mode Teks Baca biasa --}}
                                <span id="view-status" style="font-weight: 600; font-size: 14px; color: #d97706; padding: 2px 8px; background: #fffbeb; border-radius: 4px;">
                                    {{ strtoupper($transaksi->status_pesanan ?? 'PROSES') }}
                                </span>

                                {{-- Mode Dropdown Pilihan Admin --}}
                                @if(Auth::user()->role == 'admin')
                                    <select id="edit-status" name="status_pesanan" style="display: none; padding: 4px 8px; border: 1px solid #ccbc9f; border-radius: 4px; font-size: 13px; background: white; outline: none; font-weight: bold; color: #d97706;" required>
                                        @if(strtolower($transaksi->status_pesanan ?? 'proses') === 'proses')
                                            <option value="Proses" selected>PROSES</option>
                                            <option value="Dikirim">DIKIRIM</option>
                                            <option value="Selesai">SELESAI</option>
                                        @else
                                            {{-- Skenario jika sudah 'Dikirim', sembunyikan opsi 'Proses' agar tidak bisa mundur --}}
                                            <option value="Dikirim" selected>DIKIRIM</option>
                                            <option value="Selesai">SELESAI</option>
                                        @endif
                                    </select>
                                @endif
                            </div>

                        </div>

                        {{-- 2. BAGIAN WAKTU --}}
                        <div style="padding-bottom: 16px; margin-bottom: 16px; border-bottom: 1px dashed #d4c5b0;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 13px;">
                                <span style="color: #8a7a63;">Tanggal Pemesanan</span>
                                <span style="color: #444; font-weight: 500;">{{ $transaksi->created_at->format('d M Y, H:i') }} WIB</span>
                            </div>
                        </div>

                        {{-- 3. BAGIAN PENERIMA --}}
                        <div style="padding-bottom: 16px; margin-bottom: 16px; border-bottom: 1px dashed #d4c5b0;">
                            <div style="color: #8a7a63; font-size: 12px; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px;">Penerima</div>
                            <div style="font-weight: 600; color: #444;">{{ $transaksi->user->name ?? 'Customer' }}</div>
                            <div style="font-size: 13px; color: #666; margin-bottom: 12px;">{{ $transaksi->user->phone ?? '-' }}</div>

                            <div style="color: #8a7a63; font-size: 12px; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px;">Alamat Pengiriman</div>
                            <div style="font-size: 13px; line-height: 1.5; color: #444;">
                                {{ $transaksi->shipping_address }}
                            </div>
                        </div>

                        {{-- 4. BAGIAN TEKNIS PENGIRIMAN & RESI --}}
                        <div style="padding-bottom: 16px; margin-bottom: 16px; border-bottom: 1px dashed #d4c5b0;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                <span style="color: #8a7a63; font-size: 13px;">Layanan Pengiriman</span>
                                <span style="font-weight: 600; font-size: 13px; color: #5a4a33; background: #e6dfd1; padding: 2px 6px; border-radius: 4px;">
                                    {{ strtoupper($transaksi->courier ?? 'REG') }}
                                </span>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span style="color: #8a7a63; font-size: 13px;">Nomor Resi</span>

                                {{-- Mode Teks Baca biasa --}}
                                <span id="view-resi" style="font-weight: 600; font-size: 14px; letter-spacing: 0.5px; color: #333;">
                                    {{ $transaksi->resi_number ?? '-' }}
                                </span>

                                {{-- Mode Komponen Edit Admin --}}
                                @if(Auth::user()->role == 'admin')
                                    @if($transaksi->resi_number)
                                        {{-- Jika resi sudah ada, tampilkan status ter-kunci (Gak bisa diganti) --}}
                                        <span id="edit-resi-locked" style="display: none; font-weight: 600; font-size: 14px; color: #777;">
                                            {{ $transaksi->resi_number }} <span style="font-size: 11px; color: #8a7a63;">🔒 (Locked)</span>
                                        </span>
                                    @else
                                        {{-- Jika resi belum ada, sediakan form input pengisian resi baru --}}
                                        <input id="edit-resi" type="text" name="resi_number" style="display: none; width: 140px; padding: 4px 8px; border: 1px solid #ccbc9f; border-radius: 4px; font-size: 13px; outline: none; text-align: right;" placeholder="Ketik No Resi..." required>
                                    @endif
                                @endif
                            </div>
                        </div>

                        {{-- PANEL CONTROL ADMIN --}}
                        @if(Auth::user()->role == 'admin')
                            <div id="box-simpan" style="display: none; margin-top: 20px;">
                                <button type="submit" class="btn-bayar" style="width: 100%; background: #d97706; border-bottom: 4px solid #b45309; margin: 0;">
                                    Simpan Perubahan
                                </button>
                                <button type="button" id="btn-batal-edit" style="width: 100%; margin-top: 8px; padding: 8px; background: transparent; color: #8a7a63; border: 1px solid #d4c5b0; border-radius: 6px; font-size: 13px; cursor: pointer; font-weight: 600;">
                                    Batal
                                </button>
                            </div>
                        @endif

                    </form>

                    {{-- TOMBOL AKSI (KHUSUS CUSTOMER) --}}
                    @if(Auth::user()->role != 'admin')
                        <div style="margin-top: 24px;">

                            {{-- Jika Belum Dibayar, Munculkan Tombol Lanjut Bayar --}}
                            @if(in_array($transaksi->payment_status, ['Unpaid', 'Menunggu Pembayaran', 'Pending']))
                                <button type="button" id="btn-lanjut-bayar" class="btn-bayar" style="width: 100%; background: #d32f2f; border-bottom: 4px solid #b71c1c; margin-bottom: 12px;">
                                    Lanjut Bayar
                                </button>
                            @endif

                            {{-- Tombol Selesai (Bawaan aslimu) --}}
                            @if($transaksi->status_pesanan == 'Dikirim')
                                <form action="#" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-bayar" style="width: 100%;">Pesanan Selesai</button>
                                </form>
                            @else
                                <button type="button" class="btn-bayar" style="width: 100%; background-color: #cccccc; color: #777777; border-bottom: 4px solid #aaaaaa; cursor: not-allowed;" disabled>
                                    Pesanan Selesai
                                </button>
                                <div style="text-align: center; font-size: 12px; color: #8a7a63; margin-top: 8px;">
                                    Tombol aktif setelah pesanan berstatus dikirim.
                                </div>
                            @endif
                        </div>
                    @endif

                </div>
            </div>
        </div>

    </div>
</div>

@if(Auth::user()->role == 'admin' && strtolower($transaksi->status_pesanan) !== 'selesai')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const btnToggle = document.getElementById('btn-toggle-edit');
        const btnBatal  = document.getElementById('btn-batal-edit');

        const viewStatus = document.getElementById('view-status');
        const editStatus = document.getElementById('edit-status');

        const viewResi = document.getElementById('view-resi');
        const editResi = document.getElementById('edit-resi');
        const editResiLocked = document.getElementById('edit-resi-locked');

        const boxSimpan = document.getElementById('box-simpan');

        if(btnToggle) {
            btnToggle.addEventListener('click', function() {
                viewStatus.style.display = 'none';
                viewResi.style.display = 'none';
                btnToggle.style.display = 'none';

                editStatus.style.display = 'inline-block';

                // Cek apakah pakai kolom input resi baru atau teks resi yang terkunci
                if(editResi) editResi.style.display = 'inline-block';
                if(editResiLocked) editResiLocked.style.display = 'inline-block';

                boxSimpan.style.display = 'block';
            });
        }

        if(btnBatal) {
            btnBatal.addEventListener('click', function() {
                viewStatus.style.display = 'inline-block';
                viewResi.style.display = 'inline-block';
                btnToggle.style.display = 'inline-block';

                editStatus.style.display = 'none';

                if(editResi) editResi.style.display = 'none';
                if(editResiLocked) editResiLocked.style.display = 'none';

                boxSimpan.style.display = 'none';
            });
        }
    });
</script>
@endif

@if(Auth::user()->role != 'admin' && in_array($transaksi->payment_status, ['Unpaid', 'Menunggu Pembayaran', 'Pending']))
    {{-- Memanggil Script Snap Midtrans --}}
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('SB-Mid-client-T3HNRf3mJEV_6ZD4') }}"></script>
    <script>
        document.getElementById('btn-lanjut-bayar').addEventListener('click', function () {
            // Memanggil snap token yang sudah tersimpan di database saat checkout pertama kali
            snap.pay('{{ $transaksi->snap_token }}', {
                onSuccess: function(result){
                    alert("Pembayaran sukses!");
                    window.location.reload(); // Refresh halaman agar status berubah jadi Lunas
                },
                onPending: function(result){
                    alert("Menunggu pembayaran Anda diselesaikan.");
                    window.location.reload();
                },
                onError: function(result){
                    alert("Pembayaran gagal atau dibatalkan!");
                },
                onClose: function(){
                    alert('Anda menutup popup sebelum menyelesaikan pembayaran.');
                }
            });
        });
    </script>
@endif

@endsection
