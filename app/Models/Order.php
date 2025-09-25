<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'id',
        'user_id',
        'nama',
        'alamat',
        'telepon',
        'ongkir',
        'subtotal',
        'total',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function konfirmasiPembayaran()
    {
        return $this->hasOne(KonfirmasiPembayaran::class, 'order_id', 'id');
    }

    public function pengiriman()
    {
        return $this->hasOne(Pengiriman::class, 'order_id', 'id');
    }
}