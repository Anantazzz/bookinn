<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class reservasi extends Model
{
    use HasFactory;

    protected $table = 'reservasis';

    protected $fillable = [
        'user_id',
        'kamar_id',
        'tanggal_checkin',
        'jam_checkin',
        'tanggal_checkout',
        'jam_checkout',
        'status',
    ];

     public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function kamar()
    {
        return $this->belongsTo(Kamar::class, 'kamar_id', 'id');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'reservasi_id', 'id');
    }
}
