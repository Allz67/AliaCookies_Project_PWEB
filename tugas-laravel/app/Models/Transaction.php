<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transaksis';

    protected $fillable = [
        'id',
        'user_id',
        'total_harga',
        'snap_token',
        'payment_status',
        'status_pesanan',
        'shipping_address',
        'shipping_cost',
        'courier',
        'resi_number',
        'tanggal_transaksi',
    ];

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // Relasi ke detail_transaksis
    public function items()
    {
        return $this->hasMany(TransactionItem::class, 'id_transaksi', 'id');
    }

    // Aksesor Pintar untuk otomatisasi kalkulasi data di view
    public function getTotalAttribute()
    {
        return $this->items()->sum('subtotal');
    }

    public function getProdukRingkasanAttribute()
    {
        return $this->items()->pluck('nama_produk')->implode(', ');
    }

    public function getQtyTotalAttribute()
    {
        return $this->items()->sum('jumlah');
    }
}
