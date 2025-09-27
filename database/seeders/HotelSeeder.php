<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hotel;

class HotelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         Hotel::create([
            'nama_hotel' => 'Cendana Inn',
            'gambar' => 'hotel4.jpg',
            'kota' => 'Jakarta',
            'alamat' => 'Jl. Merdeka No. 1, Jakarta Pusat',
            'rating' => 4.5,
            'bintang' => 5,
        ]);

        Hotel::create([
            'nama_hotel' => 'Nava Inn',
            'gambar' => 'hotel6.jpg',
            'kota' => 'Yogyakarta',
            'alamat' => 'Jl. Merdeka No. 1, Yogyakarta',
            'rating' => 4.5,
            'bintang' => 5,
        ]);

        
         Hotel::create([
            'nama_hotel' => 'Lavenda Hotel',
            'gambar' => 'hotel3.jpg',
            'kota' => 'Palembang',
            'alamat' => 'Jl. Merdeka No. 1, Palembang',
            'rating' => 4.0,
            'bintang' => 4,
        ]);

        Hotel::create([
            'nama_hotel' => 'Astra Inn',
            'gambar' => 'hotel8.jpg',
            'kota' => 'Malang',
            'alamat' => 'Jl. Merdeka No. 1, Malang',
            'rating' => 4.5,
            'bintang' => 3,
        ]);

        Hotel::create([
            'nama_hotel' => 'Azurea Lodge',
            'gambar' => 'hotel6.jpg',
            'kota' => 'Maluku',
            'alamat' => 'Jl. Merdeka No. 1, Maluku',
            'rating' => 4.5,
            'bintang' => 3,
        ]);
            
        Hotel::create([
            'nama_hotel' => 'Aruna Stay',
            'gambar' => 'hotel7.jpg',
            'kota' => 'Maluku',
            'alamat' => 'Jl. Merdeka No. 1, Maluku',
            'rating' => 4.5,
            'bintang' => 3,
        ]);
    }
}
