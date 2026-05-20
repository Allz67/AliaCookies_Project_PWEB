@extends('layouts.app')

@section('title', 'Arsip Produk')

@section('content')
<div class="page-wrapper">
    <div class="page-header">
        <div style="display: flex; align-items: center; gap: 20px;">
            <a href="{{ route('product.index') }}" class="btn-admin-cancel" style="padding: 10px; width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; text-decoration: none;">
                <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            </a>
            <div>
                <h1 class="page-title">Arsip <span class="highlight">Produk</span></h1>
                <p class="page-sub">Daftar produk Alia Cookies yang telah diarsipkan</p>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert-toast">
            <div class="toast-content">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"></path></svg>
                {{ session('success') }}
            </div>
        </div>
    @endif

    <div class="card table-card">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Status</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $p)
                    <tr>
                        <td><span class="trx-id">{{ $p->kode }}</span></td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                @if($p->foto)
                                    <img src="{{ asset('storage/'.$p->foto) }}" width="35" height="35" style="border-radius: 8px; object-fit: cover;">
                                @endif
                                <strong>{{ $p->nama }}</strong>
                            </div>
                        </td>
                        <td>
                            <span class="kategori-badge kategori-{{ strtolower($p->kategori) }}">
                                {{ $p->kategori }}
                            </span>
                        </td>
                        <td class="td-money">Rp {{ number_format($p->harga, 0, ',', '.') }}</td>
                        <td>
                            <span class="status-badge status-batal">TERARSIP</span>
                        </td>
                        <td>
                            <div style="display: flex; gap: 10px; justify-content: center;">
                                <form action="{{ route('product.restore', $p->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-admin-save" style="padding: 8px 18px; font-size: 0.75rem; border-radius: 12px;">
                                        <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="3" style="margin-right: 5px;"><polyline points="23 4 23 10 17 10"></polyline><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path></svg>
                                        PULIHKAN
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 40px; color: var(--text-light);">
                            <p>Tidak ada produk di dalam arsip.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
            <div style="padding-top: 20px; border-top: 1px solid var(--border);">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
