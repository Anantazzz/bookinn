<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kamar; 

class KamarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         Kamar::create([
            'hotel_id' => 1, 
            'nomor_kamar' => '101',
            'tipe_kamar_id' => 1, 
            'harga' => 762000,
            'status' => 'tersedia',
            'kapasitas' => 2,
            'jumlah_bed' => 1,
            'internet' => true,
            'gambar' => 'kamar6.jpg',
        ]);

        Kamar::create([
            'hotel_id' => 1, 
            'nomor_kamar' => '106',
            'tipe_kamar_id' => 2, 
            'harga' => 983000,
            'status' => 'tersedia',
            'kapasitas' => 3,
            'jumlah_bed' => 1,
            'internet' => true,
            'gambar' => 'kamar6.jpg',
        ]);

        Kamar::create([
            'hotel_id' => 1, 
            'nomor_kamar' => '120',
            'tipe_kamar_id' => 3, 
            'harga' => 1145000,
            'status' => 'tersedia',
            'kapasitas' => 4,
            'jumlah_bed' => 2,
            'internet' => true,
            'gambar' => 'kamar6.jpg',
        ]);
    }
}
