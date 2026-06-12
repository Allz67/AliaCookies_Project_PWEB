<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'carts';

    protected $fillable = [
        'user_id',
        'id_produk',
        'jumlah',
    ];

    // ── Relasi ke User ──
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // ── Relasi ke Product ──
    public function product() {
        return $this->belongsTo(Product::class, 'id_produk'); // Sesuaikan foreign key-nya
    }

    // ── Accessor: subtotal per item ──
    public function getSubtotalAttribute(): int
    {
        return $this->jumlah * ($this->product->harga ?? 0);
    }
}
