<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservasi extends Model
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
        'kasur_tambahan',
        'total_harga',
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
    
    public function tipeKamar()
    {
        return $this->belongsTo(TipeKamar::class, 'tipe_kamar_id'); 
    }

    public function tamuOffline()
    {
        return $this->belongsTo(TamuOffline::class, 'tamu_offline_id');
    }
}
