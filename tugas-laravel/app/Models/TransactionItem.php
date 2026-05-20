<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{
    // KUNCI UTAMA: Sesuaikan dengan nama tabel di file migrasimu
    protected $table = 'detail_transaksis';

    protected $fillable = ['id_transaksi', 'id_produk', 'nama_produk', 'jumlah', 'harga_satuan', 'subtotal'];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'id_transaksi', 'id_transaksi');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'id_produk', 'id');
    }
}
