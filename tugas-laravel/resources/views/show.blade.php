@extends('layouts.app')

@section('title', 'Detail Produk')

@section('content')
<div class="page-wrapper">
    <div class="page-header">
        <h1 class="page-title">Detail <span class="highlight">Produk</span></h1>
        <a href="{{ route('product.index') }}" class="btn-outline" style="text-decoration: none;">Kembali</a>
    </div>

    <div class="card" style="display: grid; grid-template-columns: 300px 1fr; gap: 30px; margin-top: 20px;">
        <div class="product-image-preview">
            @if($product->foto)
                <img src="{{ asset('storage/'.$product->foto) }}" style="width: 100%; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
            @else
                <div style="width: 100%; height: 300px; background: #f5ebe0; display: flex; align-items: center; justify-content: center; border-radius: 12px; color: #a77761; font-size: 3rem;">🍪</div>
            @endif
        </div>

        <div class="product-info">
            <h2 style="font-family: 'Cormorant Garamond', serif; font-size: 2.5rem; color: #3d2b1f; margin-bottom: 10px;">{{ $product->nama }}</h2>
            <span class="chip-tag" style="font-size: 14px; padding: 6px 16px;">{{ $product->kategori }}</span>

            <div style="margin-top: 25px; display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="info-group">
                    <label style="display: block; color: #9c8275; font-size: 12px; text-transform: uppercase; letter-spacing: 1px;">Kode Produk</label>
                    <p style="font-weight: 600; font-size: 18px; color: #3d2b1f;">#{{ $product->kode }}</p>
                </div>
                <div class="info-group">
                    <label style="display: block; color: #9c8275; font-size: 12px; text-transform: uppercase; letter-spacing: 1px;">Harga Satuan</label>
                    <p style="font-weight: 600; font-size: 18px; color: #a77761;">Rp {{ number_format($product->harga, 0, ',', '.') }}</p>
                </div>
                <div class="info-group">
                    <label style="display: block; color: #9c8275; font-size: 12px; text-transform: uppercase; letter-spacing: 1px;">Stok Tersedia</label>
                    <p style="font-weight: 600; font-size: 18px; color: #3d2b1f;">{{ $product->stok }} {{ $product->satuan }}</p>
                </div>
                <div class="info-group">
                    <label style="display: block; color: #9c8275; font-size: 12px; text-transform: uppercase; letter-spacing: 1px;">Status</label>
                    <span class="status-badge status-{{ $product->stok > 0 ? 'selesai' : 'batal' }}">
                        {{ $product->stok > 0 ? 'Aktif di Katalog' : 'Tidak Tersedia' }}
                    </span>
                </div>
            </div>

            <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #f5ebe0; display: flex; gap: 15px;">
                <a href="{{ route('product.edit', $product->id) }}" class="btn-login" style="text-decoration: none; width: auto; padding: 10px 25px;">Edit Data</a>
            </div>
        </div>
    </div>
</div>
@endsection
