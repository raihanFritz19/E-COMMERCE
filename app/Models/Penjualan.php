<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Penjualan extends Model
{
    protected $table = 'penjualan';

    protected $fillable = ['tanggal', 'total', 'customer_id'];

    public $timestamps = true;

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
}
