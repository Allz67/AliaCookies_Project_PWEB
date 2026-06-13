<div class="table-responsive">
    <table class="data-table" id="trxTable">
        <thead>
            <tr>
                <th>ID Transaksi</th>
                <th>Pelanggan</th>
                <th>Produk</th>
                <th>Status</th>
                <th>Total</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $trx)
            <tr>
                <td><span class="trx-id">{{ $trx->id }}</span></td>
                <td>{{ $trx->user ? $trx->user->name : 'Pelanggan Umum' }}</td>
                <td>{{ $trx->produk_ringkasan }}</td>
                <td>
                    <span class="status-badge status-{{ strtolower($trx->status_pesanan) }}">
                        {{ $trx->status_pesanan }}
                    </span>
                </td>
                <td class="td-money">Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                <td class="td-date">{{ $trx->tanggal_transaksi }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; color: var(--text-light); padding: 20px;">Data transaksi tidak ditemukan</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="pagination-wrapper" style="margin-top: 20px; display: flex; justify-content: flex-end;">
    {{ $transactions->links() }}
</div>
