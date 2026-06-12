@extends('layouts.store')

@section('title', $produk->nama . ' — Alia Cookies')

@section('content')

{{-- ── Breadcrumb ── --}}
<div class="sc-breadcrumb">
    <div class="sc-breadcrumb__inner">
        <a href="{{ route('home') }}">Beranda</a>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
        <a href="{{ route('home') }}#produk-{{ strtolower($produk->kategori) }}">{{ $produk->kategori }}</a>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
        <span>{{ $produk->nama }}</span>
    </div>
</div>


{{-- ── Main Detail ── --}}
<section class="sc-detail">
    <div class="sc-detail__inner">

        {{-- ════════════════════════════
             KOLOM KIRI — Foto
        ════════════════════════════ --}}
        <div class="sc-detail__gallery">
            <div class="sc-detail__img-frame">
                @if($produk->foto)
                    <img id="mainPhoto"
                         src="{{ asset('storage/' . $produk->foto) }}"
                         alt="{{ $produk->nama }}"
                         class="sc-detail__main-img">
                @else
                    <div class="sc-detail__img-empty">
                        <span>{{ $produk->kategori === 'Hampers' ? '🎁' : '🍪' }}</span>
                        <p>Foto tidak tersedia</p>
                    </div>
                @endif

                <div class="sc-detail__img-badge sc-detail__img-badge--{{ strtolower($produk->kategori) }}">
                    {{ $produk->kategori }}
                </div>
            </div>

            <div class="sc-detail__deco sc-detail__deco--a"></div>
            <div class="sc-detail__deco sc-detail__deco--b"></div>
        </div>


        {{-- ════════════════════════════
             KOLOM KANAN — Info
        ════════════════════════════ --}}
        <div class="sc-detail__info">

            {{-- Kode & Status Stok --}}
            <div class="sc-detail__meta-top">
                <span class="sc-detail__kode">{{ $produk->kode }}</span>
                @if($produk->stok > 0)
                    <span class="sc-detail__stok-badge sc-detail__stok-badge--ada">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                        Tersedia
                    </span>
                @else
                    <span class="sc-detail__stok-badge sc-detail__stok-badge--habis">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        Stok Habis
                    </span>
                @endif
            </div>

            {{-- Nama Produk --}}
            <h1 class="sc-detail__nama">{{ $produk->nama }}</h1>

            {{-- Harga --}}
            <div class="sc-detail__harga-wrap">
                <p class="sc-detail__harga">Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>
                <span class="sc-detail__satuan">/ {{ $produk->satuan }}</span>
            </div>

            {{-- Stok Visual --}}
            <div class="sc-detail__stok-info">
                <div class="sc-detail__stok-row">
                    <span>Sisa Stok</span>
                    <strong>{{ $produk->stok }} {{ $produk->satuan }}</strong>
                </div>
                @php
                    $pct      = min(100, ($produk->stok / max(1, $produk->stok + 20)) * 100);
                    $barColor = $produk->stok > 20 ? 'var(--green)' : ($produk->stok > 5 ? '#e8b84b' : '#d97575');
                @endphp
                <div class="sc-detail__stok-bar">
                    <div class="sc-detail__stok-fill" style="width: {{ $pct }}%; background: {{ $barColor }};"></div>
                </div>
                @if($produk->stok <= 5 && $produk->stok > 0)
                    <p class="sc-detail__stok-warn">⚠️ Stok hampir habis, segera pesan!</p>
                @endif
            </div>

            {{-- Deskripsi --}}
            @if($produk->deskripsi)
            <div class="sc-detail__desc-wrap">
                <h3 class="sc-detail__desc-title">Deskripsi Produk</h3>
                <p class="sc-detail__desc">{{ $produk->deskripsi }}</p>
            </div>
            @endif

            {{-- Perk badges --}}
            <div class="sc-detail__perks">
                <div class="sc-detail__perk"><span>🥚</span><small>Bahan Premium</small></div>
                <div class="sc-detail__perk"><span>👐</span><small>Handmade</small></div>
                <div class="sc-detail__perk"><span>📦</span><small>Kemasan Cantik</small></div>
                <div class="sc-detail__perk"><span>🚚</span><small>Siap Dikirim</small></div>
            </div>

            {{-- ── Buy Area ── --}}
            <div class="sc-detail__buy-area">

                {{-- Pengatur Jumlah --}}
                <div class="sc-detail__qty-row">
                    <span class="sc-detail__qty-label">Jumlah</span>
                    <div class="sc-detail__qty-controls">
                        <div class="ct-qty">
                            <button type="button"
                                    class="ct-qty__btn ct-qty__btn--minus"
                                    onclick="ubahQtyDetail(-1, {{ $produk->stok }})"
                                    id="btnMinus"
                                    {{ $produk->stok <= 0 ? 'disabled' : '' }}>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            </button>
                            <input type="number"
                                   id="detailQty"
                                   class="ct-qty__input"
                                   value="1"
                                   min="1"
                                   max="{{ $produk->stok }}"
                                   readonly>
                            <button type="button"
                                    class="ct-qty__btn ct-qty__btn--plus"
                                    onclick="ubahQtyDetail(1, {{ $produk->stok }})"
                                    id="btnPlus"
                                    {{ $produk->stok <= 0 ? 'disabled' : '' }}>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            </button>
                        </div>
                        <span class="sc-detail__qty-stok">dari {{ $produk->stok }} {{ $produk->satuan }}</span>
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="sc-detail__btn-group">
                    {{-- Masukkan Keranjang --}}
                    <button class="sc-btn sc-btn--ghost sc-detail__btn-cart"
                            id="btnKeranjang"
                            onclick="tambahKeKeranjang({{ $produk->id }}, false, this)"
                            {{ $produk->stok <= 0 ? 'disabled' : '' }}>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
                        Keranjang
                    </button>

                    {{-- Beli Sekarang --}}
                    <button type="button" class="sc-btn sc-btn--primary sc-detail__btn-buy"
                            id="btnBeli"
                            onclick="beliLangsung({{ $produk->id }})"
                            {{ $produk->stok <= 0 ? 'disabled' : '' }}>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                        Beli Sekarang
                    </button>
                </div>

                {{-- Kembali --}}
                <div class="sc-detail__back-row">
                    <a href="{{ route('home') }}" class="sc-detail__back-link">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                        Kembali ke Beranda
                    </a>
                </div>

            </div>{{-- /.sc-detail__buy-area --}}

            {{-- Info tambahan --}}
            <div class="sc-detail__extra">
                <div class="sc-detail__extra-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    <span>Pengiriman 1 hari setelah pemesanan</span>
                </div>
                <div class="sc-detail__extra-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013 7.18a2 2 0 012-2.18h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L9.91 12a16 16 0 006.09 6.09l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 18.92z"/></svg>
                    <span>Pertanyaan? <a href="https://wa.me/6285648569562" target="_blank">Hubungi via WhatsApp</a></span>
                </div>
            </div>

        </div>{{-- /.sc-detail__info --}}
    </div>
</section>


{{-- ── Produk Lainnya ── --}}
@php
    $lainnya = \App\Models\Product::where('is_active', 1)
        ->where('kategori', $produk->kategori)
        ->where('id', '!=', $produk->id)
        ->whereNull('deleted_at')
        ->limit(4)
        ->get();
@endphp

@if($lainnya->count())
<section class="sc-related">
    <div class="sc-related__header">
        <div class="sc-section-label">
            <span class="sc-label-dot sc-label-dot--mocha"></span>
            Produk Serupa
        </div>
        <h2 class="sc-section-title">{{ $produk->kategori }} <em>Lainnya</em></h2>
    </div>

    <div class="sc-related__grid">
        @foreach($lainnya as $item)
        <a href="{{ route('product.detail', $item->id) }}" class="sc-pcard sc-pcard--grid">
            <div class="sc-pcard__img-wrap">
                @if($item->foto)
                    <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama }}" loading="lazy">
                @else
                    <div class="sc-pcard__img-fallback">{{ $item->kategori === 'Hampers' ? '🎁' : '🍪' }}</div>
                @endif
                <div class="sc-pcard__overlay"><span>Lihat Detail</span></div>
            </div>
            <div class="sc-pcard__body">
                <span class="sc-pcard__kategori sc-pcard__kategori--{{ strtolower($item->kategori) }}">{{ $item->kategori }}</span>
                <h3 class="sc-pcard__nama">{{ $item->nama }}</h3>
                <p class="sc-pcard__harga">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
            </div>
        </a>
        @endforeach
    </div>
</section>
@endif

@endsection


@push('scripts')
<script>
// ── Qty Control ──
function ubahQtyDetail(delta, maxStok) {
    const input    = document.getElementById('detailQty');
    const btnMinus = document.getElementById('btnMinus');
    const btnPlus  = document.getElementById('btnPlus');
    let val = parseInt(input.value) + delta;
    if (val < 1 || val > maxStok) return;
    input.value       = val;
    btnMinus.disabled = val <= 1;
    btnPlus.disabled  = val >= maxStok;
}

// ── Tambah ke Keranjang ──
function tambahKeKeranjang(produkId, beliSekarang, btn) {
    const jumlah      = parseInt(document.getElementById('detailQty').value) || 1;
    const originalHTML = btn.innerHTML;

    btn.innerHTML = '<span style="opacity:.7">Memproses...</span>';
    btn.disabled  = true;

    fetch('{{ route("cart.store") }}', {
        method:  'POST',
        headers: {
            'Content-Type':  'application/json',
            'X-CSRF-TOKEN':  '{{ csrf_token() }}',
            'Accept':        'application/json',
        },
        body: JSON.stringify({ id_produk: produkId, jumlah: jumlah }),
    })
    .then(async response => {
        const isJson = response.headers.get('content-type')?.includes('application/json');
        const data   = isJson ? await response.json() : null;

        if (response.status === 401) {
            alert('Kamu harus login dulu untuk belanja!');
            window.location.href = '{{ route("login") }}';
            return;
        }

        if (response.ok) {
            if (beliSekarang) {
                // Beli Sekarang → langsung pindah ke cart
                window.location.href = '{{ route("cart.index") }}';
            } else {
                // Masukkan Keranjang → Reload halaman otomatis untuk memicu Toast Global
                window.location.reload();
            }
        } else {
            btn.innerHTML = originalHTML;
            btn.disabled  = false;
            const msg = data?.message || 'Gagal menambah ke keranjang.';
            alert(msg);
        }
    })
    .catch(() => {
        btn.innerHTML = originalHTML;
        btn.disabled  = false;
        alert('Gagal menghubungi server. Cek koneksi internetmu.');
    });
}


function beliLangsung(productId) {
    let qtyInput = document.getElementById('detailQty');

    let qty = qtyInput ? qtyInput.value : 1;

    window.location.href = "{{ route('checkout.index') }}?product_id=" + productId + "&qty=" + qty;
}
</script>
@endpush
