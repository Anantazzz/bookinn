<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;
    protected $table = 'hotels';
    protected $primaryKey = 'id';
    public $incrementing = true; 
    protected $keyType = 'int'; 

    protected $fillable = [
        'nama_hotel',
        'gambar',
        'kota',
        'alamat',
        'rating',
        'bintang',
    ];
    
    public function kamars()
    {
        return $this->hasMany(Kamar::class, 'hotel_id');
    }
}
