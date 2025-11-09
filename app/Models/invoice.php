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
            Reservasi::class, 
            Pembayaran::class, 
            'id',              
            'id',               
            'pembayaran_id',    
            'reservasi_id'      
        );
    }
}
