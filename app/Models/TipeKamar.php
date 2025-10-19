<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipeKamar extends Model
{
    use HasFactory;

    protected $table = 'tipe_kamars'; 

    protected $fillable = [
        'nama_tipe',
        'harga',
        'deskripsi',
    ];

    public function kamars()
    {
        return $this->hasMany(Kamar::class, 'tipe_kamar_id');
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'hotel_id');
    }
}
