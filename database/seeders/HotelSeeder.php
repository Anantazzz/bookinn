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
            'alamat' => 'Jl. Merdeka No. 1',
            'bintang' => 5,
        ]);

       // Yogyakarta
        Hotel::create([
            'nama_hotel' => 'Merapi Hotel',
            'gambar' => 'hotel16.jpg',
            'kota' => 'Yogyakarta',
            'alamat' => 'Jl. Merdeka No. 1',
            'bintang' => 5,
        ]);

        Hotel::create([
            'nama_hotel' => 'Nava Inn',
            'gambar' => 'hotel6.jpg',
            'kota' => 'Yogyakarta',
            'alamat' => 'Jl. Merdeka No. 1',
            'bintang' => 3,
        ]);

        Hotel::create([
            'nama_hotel' => 'Nirwana Hotel',
            'gambar' => 'hotel13.jpg',
            'kota' => 'Yogyakarta',
            'alamat' => 'Jl. Merdeka No. 1',
            'bintang' => 3,
        ]);

        Hotel::create([
            'nama_hotel' => 'Prambanan Inn',
            'gambar' => 'hotel15.jpg',
            'kota' => 'Yogyakarta',
            'alamat' => 'Jl. Merdeka No. 1',
            'bintang' => 4,
        ]);

          Hotel::create([
            'nama_hotel' => 'Arjuna Hotel',
            'gambar' => 'hotel17.jpg',
            'kota' => 'Yogyakarta',
            'alamat' => 'Jl. Merdeka No. 1',
            'bintang' => 5,
        ]);

          Hotel::create([
            'nama_hotel' => 'Kenanga Stay',
            'gambar' => 'hotel15.jpg',
            'kota' => 'Yogyakarta',
            'alamat' => 'Jl. Merdeka No. 1',
            'bintang' => 3,
        ]);
        
        //Palembang
         Hotel::create([
            'nama_hotel' => 'Lavenda Hotel',
            'gambar' => 'hotel3.jpg',
            'kota' => 'Palembang',
            'alamat' => 'Jl. Merdeka No. 1',
            'bintang' => 4,
        ]);

        Hotel::create([
            'nama_hotel' => 'Astra Inn',
            'gambar' => 'hotel8.jpg',
            'kota' => 'Malang',
            'alamat' => 'Jl. Merdeka No. 1',
            'bintang' => 5,
        ]);

        Hotel::create([
            'nama_hotel' => 'Azurea Lodge',
            'gambar' => 'hotel7.jpg',
            'kota' => 'Maluku',
            'alamat' => 'Jl. Merdeka No. 1',
            'bintang' => 4,
        ]);
            
        Hotel::create([
            'nama_hotel' => 'Aruna Stay',
            'gambar' => 'hotel2.jpg',
            'kota' => 'Maluku',
            'alamat' => 'Jl. Merdeka No. 1',
            'bintang' => 3,
        ]);

        Hotel::create([
            'nama_hotel' => 'Biru Hotel',
            'gambar' => 'hotel9.jpg',
            'kota' => 'Pangandaran',
            'alamat' => 'Jl. Merdeka No. 1',
            'bintang' => 4,
        ]);

         Hotel::create([
            'nama_hotel' => 'Velora Stay',
            'gambar' => 'hotel10.jpg',
            'kota' => 'Sukabumi',
            'alamat' => 'Jl. Merdeka No. 1',
            'bintang' => 4,
        ]);
        
        Hotel::create([
            'nama_hotel' => 'Samudra Hotel',
            'gambar' => 'hotel1.jpg',
            'kota' => 'Bali',
            'alamat' => 'Jl. Merdeka No. 1',
            'bintang' => 5,
        ]);

         Hotel::create([
            'nama_hotel' => 'Cendrawasih Hotel',
            'gambar' => 'hotel20.jpg',
            'kota' => 'Papua',
            'alamat' => 'Jl. Merdeka No. 1',
            'bintang' => 4,
        ]);

        Hotel::create([
            'nama_hotel' => 'Sura Inn',
            'gambar' => 'hotel22.jpg',
            'kota' => 'Surabaya',
            'alamat' => 'Jl. Merdeka No. 1',
            'bintang' => 4,
        ]);
    }
}
