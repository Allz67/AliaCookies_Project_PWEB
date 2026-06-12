@extends(Auth::user()->role == 'admin' ? 'layouts.app' : 'layouts.store')

@section('title', 'Daftar Transaksi — Alia Cookies')

@section('content')

<div class="page-wrapper">

    {{-- ===================================================================
         BAGIAN HEADER DINAMIS (ADMIN VS CUSTOMER)
         =================================================================== --}}
    @if(Auth::user()->role == 'admin')
        {{-- Kita panggil murni class bawaanmu tanpa embel-embel style inline --}}
        <div class="page-header">
            <div>
                <p class="greeting" id="greeting">Halo, Selamat Pagi 👋</p>
                <h1 class="page-title">Pengelolaan <span class="highlight">Daftar Transaksi</span></h1>
                <p class="page-sub">Selamat datang, <strong>{{ auth()->user()->name }}</strong>. Kelola dan pantau seluruh pesanan kustomer Alia Cookies dengan mudah.</p>
            </div>
        </div>
    @else
        <div class="co-header">
            <h1 class="co-header__title">Daftar <span>Transaksi</span></h1>
        </div>
    @endif

    {{-- ============================================================
         BUNGKUSAN CARD TRANSAKSI
         (Hapus style 1000px, biarkan menyesuaikan page-wrapper)
         ============================================================ --}}
    <div class="trx-page-wrap">

        @if($transaksis->isEmpty())
            <div class="trx-empty">
                <div class="trx-empty__icon">🍪</div>
                <p class="trx-empty__title">Belum ada daftar transaksi.</p>
                <p class="trx-empty__sub">Kamu belum pernah melakukan pemesanan. Yuk, mulai belanja!</p>
            </div>

        @else

            {{-- ============================================================
                 LOOP TRANSAKSI
                 ============================================================ --}}
            @foreach($transaksis as $trx)

                @php
                    $statusMap = [
                        'menunggu_pembayaran' => ['label' => 'Menunggu Pembayaran', 'class' => 'trx-badge--menunggu'],
                        'proses'              => ['label' => 'Diproses',            'class' => 'trx-badge--proses'],
                        'dikirim'             => ['label' => 'Dikirim',             'class' => 'trx-badge--dikirim'],
                        'selesai'             => ['label' => 'Selesai',             'class' => 'trx-badge--selesai'],
                    ];
                    $status = $statusMap[$trx->status_pesanan ?? ''] ?? ['label' => strtoupper($trx->status_pesanan ?? 'PROSES'), 'class' => 'trx-badge--proses'];

                    $visibleItems = $trx->items->take(3);
                    $hiddenCount  = $trx->items->count() - 3;
                @endphp

                <div class="trx-card">

                    {{-- ---- HEADER CARD ---- --}}
                    {{-- ---- HEADER CARD ---- --}}
                    <div class="trx-card__head">
                        <div class="trx-card__head-left">
                            <div class="trx-card__id">#{{ ltrim($trx->id, '#') }}</div>
                            <div class="trx-card__date">{{ $trx->created_at->format('d M Y, H:i') }} WIB</div>

                            {{-- LABEL STATUS PEMBAYARAN BARU --}}
                            @if(in_array($trx->payment_status, ['Unpaid', 'Menunggu Pembayaran', 'Pending']))
                                <div style="font-size: 11px; color: #d32f2f; font-weight: bold; margin-top: 6px;">
                                    BELUM DIBAYAR
                                </div>
                            @elseif(in_array($trx->payment_status, ['Dibayar', 'Settlement', 'Capture']))
                                <div style="font-size: 11px; color: #2e7d32; font-weight: bold; margin-top: 6px;">
                                    LUNAS
                                </div>
                            @endif

                            {{-- Nama pembeli — hanya tampil untuk admin --}}
                            @if(Auth::user()->role == 'admin' && $trx->user)
                                <div class="trx-card__buyer" style="margin-top: 4px;">
                                    {{ $trx->user->name }}
                                </div>
                            @endif
                        </div>

                        {{-- STATUS BADGE (Status Pesanan) --}}
                        <span class="trx-badge {{ $status['class'] }}">
                            {{ $status['label'] }}
                        </span>
                    </div>

                    {{-- ---- BODY CARD — daftar produk ---- --}}
                    <div class="trx-card__body">
                        @foreach($visibleItems as $item)
                            <div class="trx-item">
                                <div class="trx-item__left">
                                    <div class="trx-item__thumb">
                                        @if($item->product && $item->product->foto)
                                            <img src="{{ asset('storage/' . $item->product->foto) }}" alt="{{ $item->product->nama }}">
                                        @else
                                            🍪
                                        @endif
                                    </div>
                                    <div>
                                        <div class="trx-item__name">
                                            {{ $item->product->nama ?? $item->nama_produk ?? 'Produk Alia Cookies' }}
                                        </div>
                                        <div class="trx-item__qty">{{ $item->jumlah ?? $item->qty }} Qty</div>
                                    </div>
                                </div>
                                <div class="trx-item__price">
                                    Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}
                                </div>
                            </div>
                        @endforeach

                        @if($hiddenCount > 0)
                            <div class="trx-item-more">+ {{ $hiddenCount }} produk lainnya...</div>
                        @endif
                    </div>

                    {{-- ---- FOOTER CARD ---- --}}
                    <div class="trx-card__foot">
                        <div>
                            <div class="trx-card__total-label">Total Belanja</div>
                            <div class="trx-card__total-amount">
                                Rp {{ number_format($trx->total_harga, 0, ',', '.') }}
                            </div>
                        </div>

                        @if(Auth::user()->role == 'admin')
                            <a href="{{ route('transaksi.detail', ltrim($trx->id, '#')) }}" class="trx-btn trx-btn--outline">
                                🛠 Kelola Pesanan
                            </a>
                        @else
                            <a href="{{ route('transaksi.detail', ltrim($trx->id, '#')) }}" class="trx-btn trx-btn--primary">
                                Lihat Detail →
                            </a>
                        @endif
                    </div>

                </div>{{-- end trx-card --}}

            @endforeach

            {{-- PAGINATION --}}
            <div class="trx-pagination">
                {{ $transaksis->links() }}
            </div>

        @endif

    </div>{{-- end trx-page-wrap --}}
</div>{{-- end co-page --}}

@endsection
