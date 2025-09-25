<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; 

class ChatLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_message',
        'bot_reply',
        'source',
        'created_at',
        'updated_at',
    ];
}
