<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FailedTransaction extends Model
{
    protected $fillable = ['order_id', 'status', 'reason', 'order_data'];
}
