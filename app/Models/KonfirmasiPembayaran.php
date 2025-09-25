<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KonfirmasiPembayaran extends Model
{
    protected $table = 'konfirmasi_pembayarans';

    protected $fillable = [
        'user_id',
        'order_id',
        'nama_penyetor',
        'bukti',
        'no_resi',
        'status',
        'status_pengiriman',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}