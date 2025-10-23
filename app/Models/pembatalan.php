<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pembatalan extends Model
{
    use HasFactory;

     protected $table = 'pembatalans';

     protected $fillable = [
        'reservasi_id',
        'pembayaran_id',
        'alasan',
        'status',
        'jumlah_refund',
        'tanggal_pembatalan',
    ];

     public function reservasi()
    {
        return $this->belongsTo(Reservasi::class);
    }

     public function pembayaran()
    {
        return $this->belongsTo(Pembayaran::class);
    }
}
