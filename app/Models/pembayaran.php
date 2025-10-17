<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pembayaran extends Model
{
    use HasFactory;
    protected $table = 'pembayarans';

    protected $fillable = [
        'reservasi_id',
        'tanggal_bayar',
        'jumlah_bayar',
        'metode_bayar',
        'status_bayar',
    ];

       public function reservasi()
    {
        return $this->belongsTo(Reservasi::class, 'reservasi_id', 'id');
    }

    public function invoice()
    {
        return $this->hasMany(Invoice::class, 'pembayaran_id');
    }
}
