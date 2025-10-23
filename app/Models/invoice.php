<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class invoice extends Model
{
    use HasFactory;

    protected $table = 'invoices';

    protected $fillable = [
        'kode_unik',
        'pembayaran_id',
        'tanggal_cetak',
        'total',
        'file_invoice',
    ];

     public function pembayaran()
    {
        return $this->belongsTo(Pembayaran::class, 'pembayaran_id', 'id');
    }

     // Relasi ke tabel reservasi (lewat pembayaran)
    public function reservasi()
    {
        return $this->hasOneThrough(
            Reservasi::class,   // model tujuan akhir
            Pembayaran::class,  // model perantara
            'id',               // kolom id di tabel pembayaran
            'id',               // kolom id di tabel reservasi
            'pembayaran_id',    // kolom di tabel invoice
            'reservasi_id'      // kolom di tabel pembayaran
        );
    }
}
