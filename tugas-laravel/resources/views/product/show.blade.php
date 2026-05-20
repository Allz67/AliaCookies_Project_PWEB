@extends('layouts.app')

@section('title', 'Detail Produk')

@section('content')
<div class="page-wrapper" style="padding: 40px 20px; background-color: #FDF5E6; min-height: 100vh;">
    <div class="page-header">
        <h1 class="page-title">Detail <span class="highlight">Produk</span></h1>
    </div>

    <div class="card shadow-lg" style="max-width: 1000px; margin: 0 auto; background: white; border-radius: 24px; padding: 40px; border: 1px solid #EBD9C8;">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px;">
            <div>
                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: bold; font-size: 0.75rem; color: #5D4037; text-transform: uppercase; margin-bottom: 8px;">Nama Produk</label>
                    <input type="text" class="search-input" style="width: 100%; padding: 12px; border-radius: 12px; border: 1.5px solid #F5F5F5; background: #FAFAFA;" value="{{ $product->nama }}" readonly>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div class="form-group">
                        <label style="display: block; font-weight: bold; font-size: 0.75rem; color: #5D4037; text-transform: uppercase; margin-bottom: 8px;">Stok</label>
                        <input type="text" class="search-input" style="width: 100%; padding: 12px; border-radius: 12px; border: 1.5px solid #F5F5F5; background: #FAFAFA;" value="{{ $product->stok }} {{ $product->satuan }}" readonly>
                    </div>
                    <div class="form-group">
                        <label style="display: block; font-weight: bold; font-size: 0.75rem; color: #5D4037; text-transform: uppercase; margin-bottom: 8px;">Kategori</label>
                        <input type="text" class="search-input" style="width: 100%; padding: 12px; border-radius: 12px; border: 1.5px solid #F5F5F5; background: #FAFAFA;" value="{{ $product->kategori }}" readonly>
                    </div>
                </div>

                <div class="form-group">
                    <label style="display: block; font-weight: bold; font-size: 0.75rem; color: #5D4037; text-transform: uppercase; margin-bottom: 8px;">Harga Jual</label>
                    <input type="text" class="search-input" style="width: 100%; padding: 12px; border-radius: 12px; border: 1.5px solid #F5F5F5; background: #FAFAFA;" value="Rp {{ number_format($product->harga, 0, ',', '.') }}" readonly>
                </div>
            </div>

            <div>
                <label style="display: block; font-weight: bold; font-size: 0.75rem; color: #5D4037; text-transform: uppercase; margin-bottom: 8px;">Foto Produk</label>
                <div style="border: 2px solid #F5F5F5; border-radius: 30px; background: #FAFAFA; padding: 30px; text-align: center; min-height: 350px; display: flex; align-items: center; justify-content: center;">
                    <img src="{{ asset('storage/' . $product->foto) }}" alt="Preview" style="max-height: 280px; border-radius: 25px; box-shadow: 0 10px 20px rgba(0,0,0,0.05);">
                </div>
            </div>
        </div>

        <div style="margin-top: 50px; display: flex; align-items: center; justify-content: center; gap: 20px;">
            <a href="{{ route('product.index') }}" class="btn-admin-cancel btn-click-effect" style="text-decoration: none; text-align: center;">
                KEMBALI
            </a>

            <a href="{{ route('product.edit', $product->id) }}" class="btn-admin-save btn-click-effect" style="text-decoration: none; text-align: center; background: #8B4513;">
                <span>EDIT PRODUK</span>
            </a>
        </div>
    </div>
</div>
@endsection
