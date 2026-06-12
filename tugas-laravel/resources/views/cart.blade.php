@extends('layouts.store')

@section('title', 'Keranjang Belanja — Alia Cookies')

@section('content')

{{-- ── Breadcrumb ── --}}
<div class="sc-breadcrumb">
    <div class="sc-breadcrumb__inner">
        <a href="{{ route('home') }}">Beranda</a>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
        <span>Keranjang Belanja</span>
    </div>
</div>


<section class="ct-page">
    <div class="ct-container">

        {{-- ── Page Header ── --}}
        <div class="ct-header">
            <div>
                <h1 class="ct-title">
                    Keranjang <em>Belanja</em>
                </h1>
                <p class="ct-subtitle">
                    @if($cartItems->count())
                        {{ $cartItems->count() }} produk dalam keranjangmu
                    @else
                        Keranjangmu masih kosong
                    @endif
                </p>
            </div>

            @if($cartItems->count())
            <form action="{{ route('cart.clear') }}" method="POST" id="clearForm">
                @csrf
                @method('DELETE')
                <button type="button" class="ct-btn-clear" onclick="confirmClear()">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/></svg>
                    Kosongkan Keranjang
                </button>
            </form>
            @endif
        </div>


        {{-- ── Flash Messages ── --}}
        @if(session('success'))
            <div class="ct-alert ct-alert--success" id="flashAlert">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                {{ session('success') }}
                <button class="ct-alert__close" onclick="this.parentElement.remove()">×</button>
            </div>
        @endif
        @if(session('error'))
            <div class="ct-alert ct-alert--error" id="flashAlert">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                {{ session('error') }}
                <button class="ct-alert__close" onclick="this.parentElement.remove()">×</button>
            </div>
        @endif


        @if($cartItems->count())
        {{-- ============================================================
             CART ITEMS LIST
        ============================================================ --}}
        <div class="ct-list" id="cartList">

            {{-- List Header (desktop only) --}}
            <div class="ct-list__head">
                <span class="ct-list__col ct-list__col--product">Produk</span>
                <span class="ct-list__col ct-list__col--price">Harga Satuan</span>
                <span class="ct-list__col ct-list__col--qty">Jumlah</span>
                <span class="ct-list__col ct-list__col--subtotal">Subtotal</span>
                <span class="ct-list__col ct-list__col--action"></span>
            </div>

            {{-- Items --}}
            @foreach($cartItems as $item)
            <div class="ct-item" id="cartItem-{{ $item->id }}" data-id="{{ $item->id }}">

                {{-- Foto + Info --}}
                <div class="ct-item__product">
                    <a href="{{ route('product.detail', $item->id_produk) }}" class="ct-item__img-wrap">
                        @if($item->product && $item->product->foto)
                            <img src="{{ asset('storage/' . $item->product->foto) }}"
                                 alt="{{ $item->product->nama }}" loading="lazy">
                        @else
                            <div class="ct-item__img-fallback">
                                {{ ($item->product->kategori ?? '') === 'Hampers' ? '🎁' : '🍪' }}
                            </div>
                        @endif
                    </a>
                    <div class="ct-item__info">
                        <span class="ct-item__kategori ct-item__kategori--{{ strtolower($item->product->kategori ?? 'cookies') }}">
                            {{ $item->product->kategori ?? '-' }}
                        </span>
                        <a href="{{ route('product.detail', $item->id_produk) }}" class="ct-item__nama">
                            {{ $item->product->nama ?? 'Produk tidak tersedia' }}
                        </a>
                        <span class="ct-item__stok">
                            Stok: {{ $item->product->stok ?? 0 }} {{ $item->product->satuan ?? '' }}
                        </span>
                        {{-- Harga mobile --}}
                        <span class="ct-item__harga-mobile">
                            Rp {{ number_format($item->product->harga ?? 0, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                {{-- Harga Satuan (desktop) --}}
                <div class="ct-item__price">
                    Rp {{ number_format($item->product->harga ?? 0, 0, ',', '.') }}
                </div>

                {{-- Qty Control --}}
                <div class="ct-item__qty">
                    <div class="ct-qty">
                        <button type="button"
                                class="ct-qty__btn ct-qty__btn--minus"
                                onclick="updateQty({{ $item->id }}, -1, {{ $item->product->stok ?? 0 }})"
                                {{ $item->jumlah <= 1 ? 'disabled' : '' }}>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        </button>
                        <input type="number"
                               class="ct-qty__input"
                               id="qty-{{ $item->id }}"
                               value="{{ $item->jumlah }}"
                               min="1"
                               max="{{ $item->product->stok ?? 99 }}"
                               readonly>
                        <button type="button"
                                class="ct-qty__btn ct-qty__btn--plus"
                                onclick="updateQty({{ $item->id }}, 1, {{ $item->product->stok ?? 0 }})"
                                {{ $item->jumlah >= ($item->product->stok ?? 0) ? 'disabled' : '' }}>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        </button>
                    </div>
                    {{-- Error stok --}}
                    <span class="ct-qty__error" id="qtyError-{{ $item->id }}"></span>
                </div>

                {{-- Subtotal --}}
                <div class="ct-item__subtotal" id="subtotal-{{ $item->id }}">
                    Rp {{ number_format($item->jumlah * ($item->product->harga ?? 0), 0, ',', '.') }}
                </div>

                {{-- Hapus --}}
                <div class="ct-item__action">
                    <form action="{{ route('cart.destroy', $item->id) }}" method="POST"
                          onsubmit="return confirmDelete(event, {{ $item->id }})">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="ct-btn-delete" title="Hapus item">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/></svg>
                        </button>
                    </form>
                </div>

            </div>
            @endforeach

        </div>


        {{-- ============================================================
             SUMMARY SECTION (below list)
        ============================================================ --}}
        <div class="ct-summary-wrap">

            {{-- Kiri: lanjut belanja --}}
            <div class="ct-summary-left">
                <a href="{{ route('home') }}" class="ct-btn-continue">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                    Lanjut Belanja
                </a>
                <p class="ct-note">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    Ongkir akan dihitung saat checkout
                </p>
            </div>

            {{-- Kanan: ringkasan harga --}}
            <div class="ct-summary">
                <div class="ct-summary__inner">
                    <h3 class="ct-summary__title">Ringkasan Pesanan</h3>

                    <div class="ct-summary__rows">
                        <div class="ct-summary__row">
                            <span>Subtotal ({{ $cartItems->count() }} produk)</span>
                            <strong id="grandTotal">
                                Rp {{ number_format($subtotal, 0, ',', '.') }}
                            </strong>
                        </div>
                        <div class="ct-summary__row ct-summary__row--muted">
                            <span>Ongkos Kirim</span>
                            <span>Dihitung saat checkout</span>
                        </div>
                        <div class="ct-summary__row ct-summary__row--muted">
                            <span>Estimasi Pajak</span>
                            <span>—</span>
                        </div>
                    </div>

                    <div class="ct-summary__divider"></div>

                    <div class="ct-summary__total">
                        <span>Total Pembayaran</span>
                        <strong id="grandTotalBig">
                            Rp {{ number_format($subtotal, 0, ',', '.') }}
                        </strong>
                    </div>

                    <a href="{{ route('checkout.index') }}" class="ct-btn-checkout">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
                        Lanjut ke Checkout
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                    </a>

                    <div class="ct-summary__trust">
                        <div class="ct-trust-item">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            <span>Transaksi Aman</span>
                        </div>
                        <div class="ct-trust-item">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                            <span>Berbagai Metode Bayar</span>
                        </div>
                        <div class="ct-trust-item">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                            <span>Pengiriman ke Seluruh Indonesia</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>


        @else
        {{-- ============================================================
             EMPTY STATE
        ============================================================ --}}
        <div class="ct-empty">
            <div class="ct-empty__icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2">
                    <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
                    <line x1="3" y1="6" x2="21" y2="6"/>
                    <path d="M16 10a4 4 0 01-8 0"/>
                </svg>
            </div>
            <h2 class="ct-empty__title">Keranjangmu <em>kosong</em></h2>
            <p class="ct-empty__sub">
                Yuk, temukan cookies handmade favorit kamu dan mulai belanja!
            </p>
            <a href="{{ route('home') }}" class="sc-btn sc-btn--primary ct-empty__cta">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                Lihat Produk Kami
            </a>

            {{-- Produk rekomendasi --}}
            @php
                $rekomendasi = \App\Models\Product::where('is_active', 1)
                    ->whereNull('deleted_at')
                    ->inRandomOrder()
                    ->limit(4)
                    ->get();
            @endphp

            @if($rekomendasi->count())
            <div class="ct-empty__reko">
                <p class="ct-empty__reko-label">Mungkin kamu suka ini</p>
                <div class="ct-empty__reko-grid">
                    @foreach($rekomendasi as $p)
                    <a href="{{ route('product.detail', $p->id) }}" class="sc-pcard sc-pcard--grid">
                        <div class="sc-pcard__img-wrap">
                            @if($p->foto)
                                <img src="{{ asset('storage/' . $p->foto) }}" alt="{{ $p->nama }}" loading="lazy">
                            @else
                                <div class="sc-pcard__img-fallback">{{ $p->kategori === 'Hampers' ? '🎁' : '🍪' }}</div>
                            @endif
                            <div class="sc-pcard__overlay"><span>Lihat Detail</span></div>
                        </div>
                        <div class="sc-pcard__body">
                            <span class="sc-pcard__kategori sc-pcard__kategori--{{ strtolower($p->kategori) }}">{{ $p->kategori }}</span>
                            <h3 class="sc-pcard__nama">{{ $p->nama }}</h3>
                            <p class="sc-pcard__harga">Rp {{ number_format($p->harga, 0, ',', '.') }}</p>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        @endif

    </div>
</section>


{{-- ── Confirm Delete Modal ── --}}
<div class="ct-modal-overlay" id="deleteModal" style="display:none">
    <div class="ct-modal">
        <div class="ct-modal__icon">🗑️</div>
        <h3>Hapus item ini?</h3>
        <p>Item akan dihapus dari keranjang belanjamu.</p>
        <div class="ct-modal__actions">
            <button class="ct-modal__cancel" onclick="closeModal()">Batal</button>
            <button class="ct-modal__confirm" id="modalConfirmBtn">Ya, Hapus</button>
        </div>
    </div>
</div>

{{-- ── Clear Cart Confirm ── --}}
<div class="ct-modal-overlay" id="clearModal" style="display:none">
    <div class="ct-modal">
        <div class="ct-modal__icon">🧹</div>
        <h3>Kosongkan keranjang?</h3>
        <p>Semua item akan dihapus dari keranjangmu. Aksi ini tidak bisa dibatalkan.</p>
        <div class="ct-modal__actions">
            <button class="ct-modal__cancel" onclick="closeClearModal()">Batal</button>
            <button class="ct-modal__confirm" onclick="document.getElementById('clearForm').submit()">
                Ya, Kosongkan
            </button>
        </div>
    </div>
</div>

@endsection


@push('scripts')
<script>
// ── CSRF Token untuk AJAX ──
const CSRF = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

// ── Update Qty via AJAX ──
async function updateQty(cartId, delta, maxStok) {
    const input    = document.getElementById('qty-' + cartId);
    const errEl    = document.getElementById('qtyError-' + cartId);
    const minusBtn = document.querySelector(`#cartItem-${cartId} .ct-qty__btn--minus`);
    const plusBtn  = document.querySelector(`#cartItem-${cartId} .ct-qty__btn--plus`);

    let current = parseInt(input.value);
    let newVal  = current + delta;

    if (newVal < 1)       return;
    if (newVal > maxStok) return;

    // Optimistic UI
    input.value = newVal;
    minusBtn.disabled = newVal <= 1;
    plusBtn.disabled  = newVal >= maxStok;

    // Show loading on subtotal
    const subtotalEl = document.getElementById('subtotal-' + cartId);
    subtotalEl.classList.add('ct-item__subtotal--loading');

    try {
        const res = await fetch(`/cart/${cartId}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ jumlah: newVal }),
        });

        const data = await res.json();

        if (data.success) {
            subtotalEl.textContent = 'Rp ' + formatRupiah(data.subtotal);
            updateGrandTotal(data.cart_total);
            errEl.textContent = '';
        } else {
            // Revert
            input.value = current;
            minusBtn.disabled = current <= 1;
            plusBtn.disabled  = current >= maxStok;
            errEl.textContent = data.message || 'Gagal update.';
        }
    } catch (e) {
        input.value = current;
        errEl.textContent = 'Koneksi bermasalah. Coba lagi.';
    } finally {
        subtotalEl.classList.remove('ct-item__subtotal--loading');
    }
}

function updateGrandTotal(total) {
    const fmt = 'Rp ' + formatRupiah(total);
    const el1 = document.getElementById('grandTotal');
    const el2 = document.getElementById('grandTotalBig');
    if (el1) el1.textContent = fmt;
    if (el2) el2.textContent = fmt;
}

function formatRupiah(num) {
    return parseInt(num).toLocaleString('id-ID');
}

// ── Delete Confirm Modal ──
let pendingDeleteForm = null;

function confirmDelete(e, cartId) {
    e.preventDefault();
    pendingDeleteForm = e.target;
    document.getElementById('deleteModal').style.display = 'flex';
    document.getElementById('modalConfirmBtn').onclick = () => {
        // Animate item out
        const item = document.getElementById('cartItem-' + cartId);
        if (item) {
            item.style.transition = 'all 0.3s ease';
            item.style.opacity    = '0';
            item.style.transform  = 'translateX(30px)';
            setTimeout(() => pendingDeleteForm.submit(), 280);
        } else {
            pendingDeleteForm.submit();
        }
    };
    return false;
}

function closeModal() {
    document.getElementById('deleteModal').style.display = 'none';
    pendingDeleteForm = null;
}

// ── Clear Cart Confirm ──
function confirmClear() {
    document.getElementById('clearModal').style.display = 'flex';
}
function closeClearModal() {
    document.getElementById('clearModal').style.display = 'none';
}

// Tutup modal kalau klik overlay
document.querySelectorAll('.ct-modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', function(e) {
        if (e.target === this) {
            this.style.display = 'none';
        }
    });
});

// Auto-hide flash alert setelah 4 detik
const flash = document.getElementById('flashAlert');
if (flash) {
    setTimeout(() => {
        flash.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        flash.style.opacity    = '0';
        flash.style.transform  = 'translateY(-8px)';
        setTimeout(() => flash.remove(), 500);
    }, 4000);
}
</script>
@endpush
