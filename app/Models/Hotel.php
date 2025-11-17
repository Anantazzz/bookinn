<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $nama_hotel
 * @property string $gambar
 * @property string $kota
 * @property string $alamat
 * @property float $rating
 * @property int $bintang
 * @property string $norek
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
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
        'norek',
        'bank',
    ];
    
    public function kamars()
    {
        return $this->hasMany(Kamar::class, 'hotel_id');
    }

    public function resepsionis()
    {
        return $this->hasMany(User::class);
    }
}
