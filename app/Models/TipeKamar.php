<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $nama_tipe
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class TipeKamar extends Model
{
    use HasFactory;

    protected $table = 'tipe_kamars'; 

    protected $fillable = [
        'nama_tipe',
    ];

    public function kamars()
    {
        return $this->hasMany(Kamar::class, 'tipe_kamar_id');
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'hotel_id');
    }

    public function reservasis()
    {
        return $this->hasMany(Reservasi::class, 'tipe_kamar_id');
    }
}
