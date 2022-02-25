<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MensajesWhatsApp extends Model
{
    use HasFactory;
    protected $table = 'mensajes_whatsapp';
    public $timestamps = false;
}
