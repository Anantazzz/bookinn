<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipeKamar extends Model
{
    use HasFactory;

    protected $table = 'tipe_kamars'; // nama tabel

    protected $fillable = [
        'nama_tipe',
        'harga',
        'deskripsi',
    ];

    // relasi ke kamar
    public function kamars()
    {
        return $this->hasMany(Kamar::class, 'tipe_kamar_id');
    }
}
