<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengiriman extends Model
{
    use HasFactory;

    protected $table = 'pengirimen'; 

    protected $fillable = [
        'user_id',
        'order_id',
        'alamat',
        'kurir',
        'status',
        'no_resi'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function pengiriman()
    {
        return $this->hasOne(\App\Models\Pengiriman::class);
    }
}
