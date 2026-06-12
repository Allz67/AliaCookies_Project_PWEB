<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'id_produk',
        'id_transaksi',
        'rating',
        'comment',
    ];

    /**
     * Relasi balik ke Product
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'id_produk', 'id');
    }

    /**
     * Relasi balik ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Relasi balik ke Transaction
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'id_transaksi', 'id');
    }
}
