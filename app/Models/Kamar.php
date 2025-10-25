<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kamar extends Model
{
    use HasFactory;
    protected $table = 'kamars';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'hotel_id',
        'nomor_kamar',
        'tipe_kamar_id', 
        'harga',
        'status',
        'kapasitas',
        'jumlah_bed',
        'internet',
        'gambar',
    ];

      public function hotel()
    {
         return $this->belongsTo(Hotel::class, 'hotel_id', 'id');
    }
    public function tipeKamar()
    {
          return $this->belongsTo(TipeKamar::class, 'tipe_kamar_id');
    }

    public function reservasis()
    {
        return $this->hasMany(\App\Models\Reservasi::class, 'kamar_id');
    }
}
