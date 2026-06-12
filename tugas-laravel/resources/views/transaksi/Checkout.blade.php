@extends('layouts.store')

@section('title', 'Checkout — Alia Cookies')

@section('content')

<div class="sc-breadcrumb">
    <div class="sc-breadcrumb__inner">
        <a href="{{ route('home') }}">Beranda</a>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>

        @if($sumberOrder == 'keranjang')
            <a href="{{ route('cart.index') }}">Keranjang</a>
        @else
            <a href="{{ route('product.detail', $items[0]['id']) }}">{{ $items[0]['nama'] }}</a>
        @endif
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>

        <span>Checkout</span>
    </div>
</div>

<div class="co-page">
    <div class="co-header">
        <div class="co-header__eyebrow">Secure Checkout</div>
        <h1 class="co-header__title">Selesaikan <span>Pesananmu</span></h1>
    </div>

    <div class="co-grid">
        {{-- KOLOM KIRI: FORM PENGIRIMAN --}}
        <div class="co-main-col">
            <div class="co-card">
                <div class="co-card__head">
                    <div class="co-card__head-title">Metode Pengiriman</div>
                </div>
                <div class="co-card__body">

                    <div class="method-grid">
                        <input type="radio" class="method-radio" name="metode_pengiriman" id="radio-pickup" value="pickup" checked>
                        <label class="method-label" for="radio-pickup">
                            <span class="method-label__title">Ambil di Tempat (Pickup)</span>
                            <span class="method-label__sub">Gratis, ambil langsung di toko</span>
                        </label>

                        <input type="radio" class="method-radio" name="metode_pengiriman" id="radio-delivery" value="delivery">
                        <label class="method-label" for="radio-delivery">
                            <span class="method-label__title">Kirim via Kurir</span>
                            <span class="method-label__sub">Ongkos kirim akan dihitung oleh Admin</span>
                        </label>
                    </div>

                    {{-- PANEL PICKUP --}}
                    <div id="panel-pickup" class="co-panel visible">
                        <div class="store-info-box">
                            <div>
                                <div class="store-info-box__name">Alamat Toko Alia Cookies</div>
                                <div class="store-info-box__address">Jl. Letjend Suprapto IV Blok Garuda No. 12, Kec. Sumbersari,<br>Kota Jember, Jawa Timur 68122</div>
                                <div class="store-info-box__meta">085648569562</div>
                                <div class="store-info-box__meta">Buka Senin–Jumat, 08.00–20.00 WIB<br>Buka Sabtu–Minggu, 09.00–21.00 WIB</div>
                                <div class="free-tag">✓ Bebas Ongkir</div>
                            </div>
                        </div>
                    </div>

                    {{-- PANEL DELIVERY --}}
                    <div id="panel-delivery" class="co-panel">
                        <div class="addr-options">
                            <input type="radio" class="addr-opt-radio" name="opsi_alamat" id="radio-alamat-profil" value="profil" checked>
                            <label class="addr-opt-label" for="radio-alamat-profil">
                                <div class="addr-opt-label__dot"></div> Gunakan Alamat Profil
                            </label>

                            <input type="radio" class="addr-opt-radio" name="opsi_alamat" id="radio-alamat-lain" value="lain">
                            <label class="addr-opt-label" for="radio-alamat-lain">
                                <div class="addr-opt-label__dot"></div> Kirim ke Alamat Lain
                            </label>
                        </div>

                        <div id="info-alamat-profil" class="profile-addr-box mb-3">
                            <strong>{{ Auth::user()->name }}</strong>
                            {{ Auth::user()->no_hp ?? Auth::user()->phone }}<br>
                            {{ Auth::user()->alamat_detail ?? Auth::user()->detail_alamat }}<br>
                            {{ Auth::user()->namaKota ?? Auth::user()->kota_nama ?? 'Kota belum diisi' }},
                            {{ Auth::user()->namaProvinsi ?? Auth::user()->provinsi_nama ?? '' }}
                        </div>

                        <div id="form-alamat-lain" class="co-form-grid hidden">
                            <div class="co-field">
                                <label class="co-label">Nama Penerima</label>
                                <input type="text" class="co-input" id="nama-penerima" placeholder="Nama lengkap">
                            </div>
                            <div class="co-field">
                                <label class="co-label">No. Telepon</label>
                                <input type="text" class="co-input" id="telp-penerima" placeholder="08xx-xxxx-xxxx">
                            </div>
                            <div class="co-field">
                                <label class="co-label">Provinsi</label>
                                <select class="co-select" id="select-provinsi-lain">
                                    <option value="">-- Memuat Provinsi... --</option>
                                </select>
                            </div>
                            <div class="co-field">
                                <label class="co-label">Kota / Kabupaten</label>
                                <select class="co-select" id="select-kota-lain" disabled>
                                    <option value="">-- Pilih Provinsi Dulu --</option>
                                </select>
                            </div>
                            {{-- TAMBAHAN: Kecamatan & Kode Pos --}}
                            <div class="co-field">
                                <label class="co-label">Kecamatan</label>
                                <input type="text" class="co-input" id="kecamatan-lain" placeholder="Cth: Sumbersari">
                            </div>
                            <div class="co-field">
                                <label class="co-label">Kode Pos</label>
                                <input type="number" class="co-input" id="kodepos-lain" placeholder="Cth: 68121">
                            </div>
                            {{-- END TAMBAHAN --}}
                            <div class="co-field span2">
                                <label class="co-label">Detail Alamat (Jalan, RT/RW, Patokan)</label>
                                <textarea class="co-textarea" id="alamat-detail-lain" placeholder="Nama jalan, no. rumah, RT/RW..."></textarea>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: RINGKASAN PESANAN --}}
        <div class="co-summary-col">
            <div class="co-card">
                <div class="co-card__head">
                    <div class="co-card__head-title">Ringkasan Pesanan</div>
                </div>
                <div class="co-card__body">
                    <table class="order-items">
                        @foreach ($items as $item)
                        <tr>
                            <td>
                                <div class="item-cell">
                                    <div class="item-thumb">
                                        @if ($item['gambar'])
                                            <img src="{{ asset('storage/' . $item['gambar']) }}" alt="{{ $item['nama'] }}">
                                        @else
                                            🍪
                                        @endif
                                    </div>
                                    <div class="item-details">
                                        <div class="item-name">{{ $item['nama'] }}</div>
                                        <div class="item-qty">{{ $item['qty'] }}x @ Rp {{ number_format($item['harga'], 0, ',', '.') }}</div>
                                    </div>
                                    <div class="item-price">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </table>

                    <table class="cost-rows">
                        <tr>
                            <td>Total Produk</td>
                            <td>Rp {{ number_format($totalHarga, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td>Ongkos Kirim</td>
                            <td id="tampil-ongkir">Rp 0 (Pickup)</td>
                        </tr>
                        <tr class="divider"><td colspan="2"></td></tr>
                        <tr class="total-row">
                            <td>Total Tagihan</td>
                            <td id="tampil-total" class="total-amount">Rp {{ number_format($totalHarga, 0, ',', '.') }}</td>
                        </tr>
                    </table>

                    <button type="button" class="btn-bayar" id="btn-bayar">
                        Buat Pesanan Sekarang
                    </button>
                    <div class="secure-badge">
                        <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                        SSL Secure System
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<input type="hidden" id="total-harga-produk" value="{{ $totalHarga }}">
<input type="hidden" id="sumber-order" value="{{ $sumberOrder }}">
<input type="hidden" id="csrf-token" value="{{ csrf_token() }}">

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const radioPickup        = document.getElementById('radio-pickup');
    const radioDelivery      = document.getElementById('radio-delivery');
    const panelPickup        = document.getElementById('panel-pickup');
    const panelDelivery      = document.getElementById('panel-delivery');
    const radioAlamatProfil  = document.getElementById('radio-alamat-profil');
    const radioAlamatLain    = document.getElementById('radio-alamat-lain');
    const infoAlamatProfil   = document.getElementById('info-alamat-profil');
    const formAlamatLain     = document.getElementById('form-alamat-lain');
    const selectProvinsiLain = document.getElementById('select-provinsi-lain');
    const selectKotaLain     = document.getElementById('select-kota-lain');
    const tampilOngkir       = document.getElementById('tampil-ongkir');
    const btnBayar           = document.getElementById('btn-bayar');
    const csrfToken          = document.getElementById('csrf-token').value;

    // Toggle Metode Pengiriman
    function switchMetode(metode) {
        const isPickup = metode === 'pickup';
        panelPickup.classList.toggle('visible', isPickup);
        panelPickup.classList.toggle('hidden', !isPickup);
        panelDelivery.classList.toggle('visible', !isPickup);
        panelDelivery.classList.toggle('hidden', isPickup);

        if (isPickup) {
            tampilOngkir.textContent = 'Rp 0 (Pickup)';
        } else {
            tampilOngkir.innerHTML = '<span style="color: #a98060; font-style: italic; font-size: 0.8rem;">Akan dihitung Admin</span>';
        }
    }

    radioPickup.addEventListener('change', () => switchMetode('pickup'));
    radioDelivery.addEventListener('change', () => switchMetode('delivery'));
    switchMetode('pickup');

    // Toggle Alamat Profil vs Alamat Lain
    radioAlamatProfil.addEventListener('change', function () {
        infoAlamatProfil.classList.remove('hidden');
        formAlamatLain.classList.add('hidden');
    });

    radioAlamatLain.addEventListener('change', function () {
        infoAlamatProfil.classList.add('hidden');
        formAlamatLain.classList.remove('hidden');
        if (selectProvinsiLain.options.length <= 1) loadProvinsi();
    });

    // Load Provinsi dari LokasiController
    function loadProvinsi() {
        selectProvinsiLain.innerHTML = '<option value="">Memuat provinsi...</option>';
        fetch('/api/provinsi')
            .then(r => r.json())
            .then(json => {
                selectProvinsiLain.innerHTML = '<option value="">-- Pilih Provinsi --</option>';
                const list = Array.isArray(json) ? json : (json.data ?? []);
                list.forEach(p => {
                    const o = document.createElement('option');
                    o.value = p.province_id ?? p.id;
                    o.textContent = p.province ?? p.name;
                    selectProvinsiLain.appendChild(o);
                });
                selectProvinsiLain.disabled = false;
            });
    }

    // Load Kota ketika Provinsi diubah
    selectProvinsiLain.addEventListener('change', function () {
        selectKotaLain.innerHTML = '<option value="">Memuat kota...</option>';
        fetch('/api/kota/' + this.value)
            .then(r => r.json())
            .then(json => {
                selectKotaLain.innerHTML = '<option value="">-- Pilih Kota --</option>';
                const list = Array.isArray(json) ? json : (json.data ?? []);
                list.forEach(k => {
                    const o = document.createElement('option');
                    o.value = k.city_id ?? k.id;
                    o.textContent = (k.type ? k.type + ' ' : '') + (k.city_name ?? k.name);
                    selectKotaLain.appendChild(o);
                });
                selectKotaLain.disabled = false;
            });
    });

    // Proses Buat Pesanan
    btnBayar.addEventListener('click', function () {
        btnBayar.disabled = true;
        btnBayar.innerHTML = '<div class="spinner"></div> Memproses...';

        const metodePengiriman = radioPickup.checked ? 'pickup' : 'delivery';
        const formData = new FormData();

        formData.append('tipe_pengiriman', metodePengiriman);
        formData.append('total_harga', document.getElementById('total-harga-produk').value);
        formData.append('sumberOrder', document.getElementById('sumber-order').value);

        if (metodePengiriman === 'delivery') {
            if (radioAlamatProfil.checked) {
                formData.append('alamat_lengkap', infoAlamatProfil.innerText.trim());
            } else {
                const prov = selectProvinsiLain;
                const kota = selectKotaLain;
                const detail = document.getElementById('alamat-detail-lain').value;
                if (!prov.value || !kota.value || !detail) {
                    alert('Harap lengkapi alamat pengiriman secara menyeluruh!');
                    btnBayar.disabled = false;
                    btnBayar.textContent = 'Buat Pesanan Sekarang';
                    return;
                }
                formData.append('alamat_lengkap', detail + ', ' + kota.options[kota.selectedIndex].text + ', ' + prov.options[prov.selectedIndex].text);
            }
        }

        const itemsData = @json($items);
        formData.append('items', JSON.stringify(itemsData));

        // Submit data ke Controller
        fetch('{{ route("checkout.prosesPayment") }}', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: formData,
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                // Arahkan ke halaman detail transaksi, biarkan detailTransaksi yang panggil Midtrans
                window.location.href = '/transaksi/' + data.order_id;
            } else {
                alert('Gagal membuat pesanan: ' + data.message);
                btnBayar.disabled = false;
                btnBayar.textContent = 'Buat Pesanan Sekarang';
            }
        })
        .catch(err => {
            alert('Terjadi kesalahan jaringan.');
            btnBayar.disabled = false;
            btnBayar.textContent = 'Buat Pesanan Sekarang';
        });
    });
});
</script>
@endpush
