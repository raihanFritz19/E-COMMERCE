<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_id', // <-- Tambahkan ini!
        'tanggal',
        'total',
        'status',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Order (tambahkan agar bisa akses data order dari transaksi)
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}