<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    // Tambahkan baris ini sebagai "Surat Izin" mass-assignment
    protected $fillable = ['key', 'value'];
}
