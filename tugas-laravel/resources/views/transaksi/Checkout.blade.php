@extends('layouts.store')

@section('title', 'Checkout — Alia Cookies')

@section('content')

{{-- 1. BREADCRUMB SELARAS UI (Sesuai Screenshot-mu) --}}
<div class="sc-breadcrumb">
    <div class="sc-breadcrumb__inner">
        <a href="{{ route('home') }}">Beranda</a>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>

        @if($sumberOrder == 'keranjang')
            <a href="{{ route('cart.index') }}">Keranjang</a>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
        @else
            <a href="{{ route('product.detail', $items[0]['id']) }}">{{ $items[0]['nama'] }}</a>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
        @endif

        <span>Checkout</span>
    </div>
</div>

<div class="co-page">
    <div class="co-header">
        <div class="co-header__eyebrow">Secure Checkout</div>
        <h1 class="co-header__title">Selesaikan <span>Pesananmu</span></h1>
    </div>

    <div class="co-grid">
        {{-- ===================================================================
             KOLOM KIRI: FORM PENGIRIMAN
             =================================================================== --}}
        <div class="co-main-col">
            <div class="co-card">
                <div class="co-card__head">
                    <div class="co-card__head-icon">📦</div>
                    <div class="co-card__head-title">Metode Pengiriman</div>
                </div>
                <div class="co-card__body">

                    {{-- Pilihan Pickup / Delivery --}}
                    <div class="method-grid">
                        <input type="radio" class="method-radio" name="metode_pengiriman" id="radio-pickup" value="pickup" checked>
                        <label class="method-label" for="radio-pickup">
                            <span class="method-label__icon">🏪</span>
                            <span class="method-label__title">Ambil di Tempat (Pickup)</span>
                            <span class="method-label__sub">Gratis, ambil langsung di toko</span>
                        </label>

                        <input type="radio" class="method-radio" name="metode_pengiriman" id="radio-delivery" value="delivery">
                        <label class="method-label" for="radio-delivery">
                            <span class="method-label__icon">🚚</span>
                            <span class="method-label__title">Kirim via Kurir</span>
                            <span class="method-label__sub">Hitung ongkir ke tujuan</span>
                        </label>
                    </div>

                    {{-- PANEL A: Pickup --}}
                    <div id="panel-pickup" class="co-panel visible">
                        <div class="store-info-box">
                            <div class="store-info-box__pin">📍</div>
                            <div>
                                <div class="store-info-box__name">Alamat Toko Alia Cookies</div>
                                <div class="store-info-box__address">Jl. Letjend Suprapto IV Garuda No. 12, Kec. Sumbersari,<br>Kota Jember, Jawa Timur 68122</div>
                                <div class="store-info-box__meta">085648569562 · Buka Senin–Sabtu, 08.00–17.00 WIB</div>
                                <div class="free-tag">✓ Bebas Ongkir</div>
                            </div>
                        </div>
                    </div>

                    {{-- PANEL B: Delivery --}}
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
                            <input type="hidden" id="kota-id-profil" value="{{ Auth::user()->kota_id }}">
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
                            <div class="co-field span2">
                                <label class="co-label">Alamat Lengkap</label>
                                <textarea class="co-textarea" id="alamat-detail-lain" placeholder="Nama jalan, no. rumah, RT/RW..."></textarea>
                            </div>
                        </div>

                        <button type="button" class="btn-hitung" id="btn-hitung-ongkir">
                            <div class="spinner"></div>
                            <span class="btn-hitung-icon">🔍</span>
                            <span class="btn-hitung-text">Hitung Ongkos Kirim</span>
                        </button>

                        <div id="hasil-ongkir" class="ongkir-result">
                            <div class="section-divider"></div>
                            <div class="ongkir-result__label">Pilih Layanan Pengiriman:</div>
                            <div id="list-kurir"></div>
                            <div id="alert-tidak-ada-kurir" class="alert-no-kurir">
                                Tidak ada layanan pengiriman tersedia ke kota ini.
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- ===================================================================
             KOLOM KANAN: RINGKASAN PESANAN
             =================================================================== --}}
        <div class="co-summary-col">
            <div class="co-card">
                <div class="co-card__head">
                    <div class="co-card__head-icon">🛍️</div>
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
                            <td id="tampil-ongkir">Rp 0</td>
                        </tr>
                        <tr class="divider"><td colspan="2"></td></tr>
                        <tr class="total-row">
                            <td>Total Pembayaran</td>
                            <td id="tampil-total" class="total-amount">Rp {{ number_format($totalHarga, 0, ',', '.') }}</td>
                        </tr>
                    </table>

                    <button type="button" class="btn-bayar" id="btn-bayar" disabled>
                        Lanjut ke Pembayaran
                        <span class="btn-bayar__sub">Bayar aman via Midtrans</span>
                    </button>
                    <div class="secure-badge">
                        <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                        SSL Secure Checkout
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<input type="hidden" id="total-harga-produk" value="{{ $totalHarga }}">
<input type="hidden" id="total-berat" value="{{ $totalBerat }}">
<input type="hidden" id="sumber-order" value="{{ $sumberOrder }}">
<input type="hidden" id="csrf-token" value="{{ csrf_token() }}">

@endsection

@push('scripts')
<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('SB-Mid-client-T3HNRf3mJEV_6ZD4') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const radioPickup          = document.getElementById('radio-pickup');
    const radioDelivery        = document.getElementById('radio-delivery');
    const panelPickup          = document.getElementById('panel-pickup');
    const panelDelivery        = document.getElementById('panel-delivery');

    const radioAlamatProfil    = document.getElementById('radio-alamat-profil');
    const radioAlamatLain      = document.getElementById('radio-alamat-lain');
    const infoAlamatProfil     = document.getElementById('info-alamat-profil');
    const formAlamatLain       = document.getElementById('form-alamat-lain');

    const selectProvinsiLain   = document.getElementById('select-provinsi-lain');
    const selectKotaLain       = document.getElementById('select-kota-lain');
    const btnHitungOngkir      = document.getElementById('btn-hitung-ongkir');
    const btnHitungText        = document.querySelector('.btn-hitung-text');
    const hasilOngkir          = document.getElementById('hasil-ongkir');
    const listKurir            = document.getElementById('list-kurir');
    const alertTidakAdaKurir   = document.getElementById('alert-tidak-ada-kurir');

    const tampilOngkir         = document.getElementById('tampil-ongkir');
    const tampilTotal          = document.getElementById('tampil-total');
    const btnBayar             = document.getElementById('btn-bayar');

    const totalHargaProduk     = parseInt(document.getElementById('total-harga-produk').value);
    const totalBerat           = document.getElementById('total-berat').value;
    const csrfToken            = document.getElementById('csrf-token').value;
    const kotaIdProfil         = document.getElementById('kota-id-profil')?.value ?? '';

    let ongkirDipilih = 0;

    const formatRupiah = (angka) => 'Rp ' + parseInt(angka).toLocaleString('id-ID');

    function updateTotalPembayaran() {
        const total = totalHargaProduk + ongkirDipilih;
        tampilOngkir.textContent = formatRupiah(ongkirDipilih);
        tampilTotal.textContent  = formatRupiah(total);
    }

    function switchMetode(metode) {
        const isPickup = (metode === 'pickup');
        panelPickup.classList.toggle('visible', isPickup);
        panelPickup.classList.toggle('hidden', !isPickup);
        panelDelivery.classList.toggle('visible', !isPickup);
        panelDelivery.classList.toggle('hidden', isPickup);

        ongkirDipilih = 0;
        updateTotalPembayaran();
        btnBayar.disabled = !isPickup;
    }

    radioPickup.addEventListener('change', () => switchMetode('pickup'));
    radioDelivery.addEventListener('change', () => switchMetode('delivery'));
    switchMetode('pickup');

    radioAlamatProfil.addEventListener('change', function () {
        infoAlamatProfil.classList.remove('hidden');
        formAlamatLain.classList.add('hidden');
        resetHasilOngkir();
    });

    radioAlamatLain.addEventListener('change', function () {
        infoAlamatProfil.classList.add('hidden');
        formAlamatLain.classList.remove('hidden');
        resetHasilOngkir();
        if (selectProvinsiLain.options.length <= 1) loadProvinsi();
    });

    function resetHasilOngkir() {
        hasilOngkir.classList.remove('visible');
        listKurir.innerHTML = '';
        ongkirDipilih = 0;
        updateTotalPembayaran();
        btnBayar.disabled = true;
    }

    // 3. FIX API PROVINSI: Dibuat kebal menghadapi LokasiController maupun CheckoutController
    function loadProvinsi() {
        selectProvinsiLain.innerHTML = '<option value="">Memuat provinsi...</option>';
        selectProvinsiLain.disabled = true;

        fetch('/api/provinsi')
        .then(res => res.json())
        .then(json => {
            selectProvinsiLain.innerHTML = '<option value="">-- Pilih Provinsi --</option>';

            // Logika ajaib: Kalau responnya Array langsung hajar, kalau Object ambil .data-nya!
            const provinsiList = Array.isArray(json) ? json : (json.data ?? []);

            provinsiList.forEach(prov => {
                const opt = document.createElement('option');
                opt.value = prov.province_id ?? prov.id;
                opt.textContent = prov.province ?? prov.name;
                selectProvinsiLain.appendChild(opt);
            });
            selectProvinsiLain.disabled = false;
        })
        .catch(() => {
            selectProvinsiLain.innerHTML = '<option value="">Gagal memuat API</option>';
        });
    }

    selectProvinsiLain.addEventListener('change', function () {
        const provinsiId = this.value;
        selectKotaLain.innerHTML = '<option value="">Memuat kota...</option>';
        selectKotaLain.disabled = true;
        resetHasilOngkir();

        if (provinsiId) {
            fetch('/api/kota/' + provinsiId)
            .then(res => res.json())
            .then(json => {
                selectKotaLain.innerHTML = '<option value="">-- Pilih Kota --</option>';

                const kotaList = Array.isArray(json) ? json : (json.data ?? []);

                kotaList.forEach(kota => {
                    const opt = document.createElement('option');
                    opt.value = kota.city_id ?? kota.id;
                    opt.textContent = (kota.type ? kota.type + ' ' : '') + (kota.city_name ?? kota.name);
                    selectKotaLain.appendChild(opt);
                });
                selectKotaLain.disabled = false;
            });
        }
    });

    btnHitungOngkir.addEventListener('click', function () {
        let destinationId = radioAlamatProfil.checked ? kotaIdProfil : selectKotaLain.value;
        if (!destinationId) {
            alert('Tujuan pengiriman belum lengkap!');
            return;
        }

        btnHitungOngkir.classList.add('loading');
        btnHitungText.textContent = "Menghitung...";
        hasilOngkir.classList.remove('visible');
        listKurir.innerHTML = '';
        alertTidakAdaKurir.classList.remove('visible');
        let beratAman = totalBerat > 0 ? totalBerat : 500;

        // 4. FIX ONGKIR: Komerce V2 wajib mengirim parameter 'courier' agar layanannya keluar!
        const formData = new URLSearchParams({
            destination: destinationId,
            weight: beratAman,
            courier: '', // Permintaan paksa multi-kurir
            t: new Date().getTime(),
            _token: csrfToken,
        });

        fetch('{{ route("checkout.hitungOngkir") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: formData.toString(),
        })
        .then(res => res.json())
        .then(json => {
            btnHitungOngkir.classList.remove('loading');
            btnHitungText.textContent = "Hitung Ongkos Kirim";
            hasilOngkir.classList.add('visible');

            if (!json.success || !json.data || json.data.length === 0) {
                alertTidakAdaKurir.classList.add('visible');
                return;
            }

            json.data.forEach((kurir, idx) => {
                const nama = kurir.courier_name ?? kurir.name ?? '-';
                const layanan = kurir.courier_service_name ?? kurir.service ?? '-';
                const harga = kurir.price ?? kurir.cost ?? 0;
                const etd = kurir.etd ?? kurir.estimation ?? '-';

                const div = document.createElement('div');
                div.innerHTML = `
                    <input class="kurir-radio-input kurir-radio" type="radio" name="kurir_dipilih" id="kurir-${idx}" value="${harga}">
                    <label class="kurir-card-label" for="kurir-${idx}">
                        <div class="kurir-card-left">
                            <div class="kurir-check"></div>
                            <div>
                                <div class="kurir-info__name">${nama} <span class="kurir-info__service">${layanan}</span></div>
                                <div class="kurir-info__etd">Estimasi: ${etd} hari</div>
                            </div>
                        </div>
                        <div class="kurir-price">${formatRupiah(harga)}</div>
                    </label>
                `;
                listKurir.appendChild(div);
            });

            document.querySelectorAll('.kurir-radio').forEach(radio => {
                radio.addEventListener('change', function () {
                    ongkirDipilih = parseInt(this.value);
                    updateTotalPembayaran();
                    btnBayar.disabled = false;
                });
            });
        });
    });

    btnBayar.addEventListener('click', function () {
        // Ubah tombol jadi loading
        btnBayar.disabled = true;
        btnBayar.innerHTML = 'Memproses...';

        // 1. Rakit Data (Tanpa menggunakan tag <form>)
        const formData = new FormData();

        const metodePengiriman = document.getElementById('radio-pickup').checked ? 'pickup' : 'delivery';
        formData.append('tipe_pengiriman', metodePengiriman);
        formData.append('total_harga', parseInt(document.getElementById('total-harga-produk').value) + ongkirDipilih);
        formData.append('ongkir', ongkirDipilih);
        formData.append('sumberOrder', document.getElementById('sumber-order').value);

        // Logika Pengiriman
        if (metodePengiriman === 'delivery') {
            const kurirTerpilih = document.querySelector('input[name="kurir_dipilih"]:checked');
            if(kurirTerpilih) {
                const namaKurir = kurirTerpilih.nextElementSibling.querySelector('.kurir-info__name').innerText;
                formData.append('kurir', namaKurir);
            } else {
                alert("Silakan pilih layanan ongkos kirim terlebih dahulu!");
                btnBayar.disabled = false;
                btnBayar.innerHTML = 'Lanjut ke Pembayaran';
                return;
            }

            if (document.getElementById('radio-alamat-profil').checked) {
                // Ambil teks alamat dari profil
                formData.append('alamat_lengkap', document.getElementById('info-alamat-profil').innerText);
            } else {
                const selectKota = document.getElementById('select-kota-lain');
                const selectProv = document.getElementById('select-provinsi-lain');
                const detail = document.getElementById('alamat-detail-lain').value;
                const alamatLengkap = detail + ', ' + selectKota.options[selectKota.selectedIndex].text + ', ' + selectProv.options[selectProv.selectedIndex].text;
                formData.append('alamat_lengkap', alamatLengkap);
            }
        } else {
            formData.append('kurir', 'Pickup');
            formData.append('alamat_lengkap', 'Ambil di Toko Alia Cookies');
        }

        // Ambil data produk (items) dari PHP, ubah jadi JSON
        const itemsData = @json($items);
        formData.append('items', JSON.stringify(itemsData));

        // 2. Kirim Data ke Controller Midtrans
        fetch('{{ route("checkout.prosesPayment") }}', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.getElementById('csrf-token').value
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.snap_token) {
                // 3. Panggil Pop-up Midtrans
                window.snap.pay(data.snap_token, {
                    onSuccess: function(result){
                        alert("Pembayaran sukses!");
                        window.location.href = '/transaksi/' + result.order_id;
                    },
                    onPending: function(result){
                        alert("Menunggu pembayaran Anda!");
                        window.location.href = '/transaksi/' + result.order_id;
                    },
                    onError: function(result){
                        alert("Pembayaran gagal!");
                        btnBayar.disabled = false;
                        btnBayar.innerHTML = 'Lanjut ke Pembayaran';
                    },
                    onClose: function(){
                        alert('Anda menutup popup sebelum menyelesaikan pembayaran');
                        btnBayar.disabled = false;
                        btnBayar.innerHTML = 'Lanjut ke Pembayaran';
                    }
                });
            } else {
                alert('Gagal memproses pesanan: ' + (data.message || 'Kesalahan sistem'));
                btnBayar.disabled = false;
                btnBayar.innerHTML = 'Lanjut ke Pembayaran';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan koneksi saat menghubungi server.');
            btnBayar.disabled = false;
            btnBayar.innerHTML = 'Lanjut ke Pembayaran';
        });
    });
});
</script>
@endpush
