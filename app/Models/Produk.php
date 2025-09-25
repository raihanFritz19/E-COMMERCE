<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    // Daftar kolom yang boleh diisi secara massal
    protected $fillable = [
        'nama',
        'kategori',
        'harga',
        'stok',
        'deskripsi',
        'foto',
    ];

    // Relasi ke model Testimoni (jika ada)
    public function testimonis()
    {
        return $this->hasMany(Testimoni::class);
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'produk_id');
    }
}   
