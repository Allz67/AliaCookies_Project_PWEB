@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')
<div class="page-wrapper" style="padding: 40px 20px; background-color: #FDF5E6; min-height: 100vh;">

    <div class="page-header">
        <h1 class="page-title">Edit <span class="highlight">Produk</span></h1>
    </div>

    <div class="card shadow-lg" style="max-width: 1000px; margin: 0 auto; background: white; border-radius: 24px; padding: 40px; border: 1px solid #EBD9C8;">
        <form action="{{ route('product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px;">
                <div>
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label style="display: block; font-weight: bold; font-size: 0.75rem; color: #5D4037; text-transform: uppercase; margin-bottom: 8px;">Nama Produk</label>
                        <input type="text" name="nama" class="search-input" style="width: 100%; padding: 12px; border-radius: 12px; border: 1.5px solid #EBD9C8;" placeholder="Contoh: Nastar Premium" value="{{ old('nama', $product->nama) }}" required>
                        @error('nama') <small style="color: #A0522D;">{{ $message }}</small> @enderror
                    </div>
                    <div class="form-group" style="margin-bottom: 20px;"> <label style="display: block; font-weight: bold; font-size: 0.75rem; color: #5D4037; text-transform: uppercase; margin-bottom: 8px;">Harga Jual (Rp)</label>
                        <input type="number" name="harga" class="search-input" style="width: 100%; padding: 12px; border-radius: 12px; border: 1.5px solid #EBD9C8;" placeholder="Min. 5.000 - Maks. 100.000.000" value="{{ old('harga', $product->harga) }}" required>
                        @error('harga') <small style="color: #A0522D;">{{ $message }}</small> @enderror
                    </div>


                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                        <div class="form-group">
                            <label style="display: block; font-weight: bold; font-size: 0.75rem; color: #5D4037; text-transform: uppercase; margin-bottom: 8px;">Stok Awal</label>
                            <input type="number" name="stok" class="search-input" style="width: 100%; padding: 12px; border-radius: 12px; border: 1.5px solid #EBD9C8;" value="{{ old('stok', $product->stok) }}" min="0" required>
                        </div>
                        <div class="form-group">
                            <label style="display: block; font-weight: bold; font-size: 0.75rem; color: #5D4037; text-transform: uppercase; margin-bottom: 8px;">Kategori</label>
                            <select name="kategori" class="search-input" style="width: 100%; padding: 12px; border-radius: 12px; border: 1.5px solid #EBD9C8;">
                                <option value="Cookies" {{ old('kategori', $product->kategori) == 'Cookies' ? 'selected' : '' }}>Cookies</option>
                                <option value="Hampers" {{ old('kategori', $product->kategori) == 'Hampers' ? 'selected' : '' }}>Hampers</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 20px;">
                        <label style="display: block; font-weight: bold; font-size: 0.75rem; color: #5D4037; text-transform: uppercase; margin-bottom: 8px;">Satuan</label>
                        <select name="satuan" class="search-input" style="width: 100%; padding: 12px; border-radius: 12px; border: 1.5px solid #EBD9C8;">
                            <option value="Toples" {{ old('satuan', $product->satuan) == 'Toples' ? 'selected' : '' }}>Toples (Reguler)</option>
                            <option value="Box" {{ old('satuan', $product->satuan) == 'Box' ? 'selected' : '' }}>Box (Hampers)</option>
                            <option value="Pcs" {{ old('satuan', $product->satuan) == 'Pcs' ? 'selected' : '' }}>Pcs (Eceran)</option>
                        </select>
                    </div>
                </div>

                <div class="upload-box"> <div id="preview-container" style="{{ $product->foto ? 'display: block;' : 'display: none;' }} width: 100%; text-align: center;">
                        <div style="position: relative; display: inline-block;">
                            <img id="image-preview"
                                src="{{ $product->foto ? asset('storage/' . $product->foto) : '#' }}"
                                alt="Preview"
                                style="max-height: 280px; border-radius: 25px; box-shadow: 0 15px 35px rgba(93, 64, 55, 0.15); border: 5px solid white;">

                            <button type="button" onclick="resetUpload()" class="btn-remove-photo">✕</button>
                        </div>
                        <p id="preview-status" style="color: #8B4513; font-size: 0.8rem; margin-top: 15px; font-weight: 600;">
                            {{ $product->foto ? 'Foto Saat Ini' : 'Foto siap diunggah!' }}
                        </p>
                    </div>

                    <div id="upload-placeholder" style="{{ $product->foto ? 'display: none;' : 'display: block;' }} text-align: center;">
                        <div style="background: #EFEBE9; width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                            <svg viewBox="0 0 24 24" width="35" height="35" fill="none" stroke="#8B4513" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                <polyline points="21 15 16 10 5 21"></polyline>
                            </svg>
                        </div>
                        <h4 style="color: #5D4037; margin-bottom: 5px; font-size: 1rem;">Ganti Foto Produk</h4>
                        <p style="color: #8B4513; font-size: 0.75rem; opacity: 0.7; margin-bottom: 25px;">Format: JPG, PNG (Maks. 2MB)</p>

                        <input type="file" name="foto" id="foto-input" style="display: none;" accept="image/*">
                        <label for="foto-input" class="btn-upload-action">
                            PILIH FOTO BARU
                        </label>
                    </div>

                    @error('foto')
                        <div style="margin-top: 15px; padding: 10px; background: #FDE8E8; border-radius: 10px; border: 1px solid #F5C6C6;">
                            <small style="color: #D32F2F; font-weight: bold; display: block;">
                                ⚠️ {{ $message }}
                            </small>
                        </div>
                    @enderror

                </div>
            </div>

            <div style="margin-top: 50px; display: flex; align-items: center; justify-content: center; gap: 20px;">

                <a href="{{ route('product.index') }}" class="btn-admin-cancel btn-click-effect">
                    BATALKAN
                </a>

                <button type="submit" class="btn-admin-save btn-click-effect">
                    <span>SIMPAN PRODUK</span>
                </button>
            </div>
            </form>
            </div>
            </div>

<script>
    // Inisialisasi elemen-elemen DOM
    const fotoInput = document.getElementById('foto-input');
    const imagePreview = document.getElementById('image-preview');
    const previewContainer = document.getElementById('preview-container');
    const uploadPlaceholder = document.getElementById('upload-placeholder');
    const previewStatus = document.getElementById('preview-status');

    // Menangani preview saat user memilih file baru
    fotoInput.onchange = evt => {
        const [file] = fotoInput.files;
        if (file) {
            // Membuat URL sementara untuk preview gambar
            imagePreview.src = URL.createObjectURL(file);
            previewContainer.style.display = 'block';
            uploadPlaceholder.style.display = 'none';

            // Update teks status jika elemennya ada
            if (previewStatus) {
                previewStatus.innerText = 'Foto Baru Terpilih!';
            }
        }
    }

    // Fungsi untuk menghapus pilihan foto dan mereset tampilan
    function resetUpload() {
        fotoInput.value = ""; // Menghapus file yang terpilih
        previewContainer.style.display = 'none';
        uploadPlaceholder.style.display = 'block';
    }
</script>
@endsection
