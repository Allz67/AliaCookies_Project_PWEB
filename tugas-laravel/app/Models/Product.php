<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes; // Tambahkan ini untuk fitur hapus aman

class Product extends Model
{
    use SoftDeletes; // Mengaktifkan fitur Soft Deletes

    // Tambahkan 'foto' ke dalam fillable agar bisa menyimpan nama file gambar [cite: 562, 629]
    protected $fillable = [
        'kode',
        'nama',
        'stok',
        'satuan',
        'kategori',
        'harga',
        'is_active',
        'foto'
    ];

    protected $table = 'products';

    protected $casts = [
        'stok' => 'integer',
        'is_active' => 'boolean',
        'harga' => 'decimal:2'
    ];

    protected $dates = ['deleted_at'];

    /**
     * Local Scope untuk mempermudah pengambilan produk yang statusnya aktif
     */
    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Relasi Many-to-Many untuk fitur Hampers (Aktivitas 4 No. 8)
     */
    public function isiHampers()
    {
        return $this->belongsToMany(
            Product::class,
            'product_hampers',
            'hampers_id',
            'product_id'
        );
    }
}
