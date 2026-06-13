<div class="table-responsive">
    <table class="data-table" id="produkTable">
        <thead>
            <tr>
                <th>KODE</th>
                <th>NAMA PRODUK</th>
                <th>KATEGORI</th>
                <th>STOK</th>
                <th>SATUAN</th>
                <th>HARGA</th>
                <th>STATUS</th>
                <th style="text-align: center;">AKSI</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $prod)
            <tr>
                <td><span class="trx-id">{{ $prod->kode }}</span></td>
                <td>{{ $prod->nama }}</td>
                <td>{{ $prod->kategori }}</td>
                <td>{{ $prod->stok }}</td>
                <td>{{ $prod->satuan }}</td>
                <td class="td-money">Rp {{ number_format($prod->harga, 0, ',', '.') }}</td>
                <td>
                    @if($prod->stok == 0)
                        <span class="status-badge status-batal">Habis</span>
                    @elseif($prod->stok <= 10)
                        <span class="status-badge status-proses">Menipis</span>
                    @else
                        <span class="status-badge status-selesai">Tersedia</span>
                    @endif
                </td>
                <td>
                    <div class="action-buttons" style="display: flex; gap: 8px; justify-content: center; align-items: center;">
                        <a href="{{ route('product.show', $prod->id) }}" class="btn-detail" style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center;">
                            Detail
                        </a>

                        <form action="{{ route('product.destroy', $prod->id) }}" method="POST" style="margin: 0; display: inline-block;" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-hapus-premium">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px;"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                Hapus
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center; color: var(--text-light); padding: 20px;">Produk tidak ditemukan</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="trx-pagination" style="width: 100%; display: flex; justify-content: center; margin-top: 20px;">
    {{ $products->appends(request()->query())->links() }}
</div>

<style>
    .btn-hapus-premium {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background-color: #fff1f2;
        color: #e11d48;
        border: 1px solid #ffe4e6;
        padding: 8px 14px;
        font-family: 'DM Sans', sans-serif;
        font-size: 13px;
        font-weight: 500;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
        box-shadow: 0 2px 4px rgba(225, 29, 72, 0.04);
    }

    .btn-hapus-premium:hover {
        background-color: #e11d48;
        color: #ffffff;
        border-color: #e11d48;
        box-shadow: 0 4px 8px rgba(225, 29, 72, 0.2);
        transform: translateY(-1px);
    }

    .btn-hapus-premium:active {
        transform: translateY(0);
    }
</style>
