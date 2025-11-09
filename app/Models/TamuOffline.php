<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TamuOffline extends Model
{
    use HasFactory;

    protected $table = 'tamu_offline';
    protected $fillable = ['nama', 'no_hp'];
}
