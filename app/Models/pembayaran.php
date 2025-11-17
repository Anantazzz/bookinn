<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;
    protected $table = 'pembayarans';

    protected $fillable = [
        'reservasi_id',
        'tanggal_bayar',
        'jumlah_bayar',
        'metode_bayar',
        'bank',
        'nomor_rekening',
        'atas_nama',
        'status_bayar',
        'bukti_transfer',
        'discount_code',
        'discount_amount',
    ];

    public function reservasi()
    {
        return $this->belongsTo(Reservasi::class, 'reservasi_id', 'id');
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'pembayaran_id');
    }
}
