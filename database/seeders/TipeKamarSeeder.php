<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TipeKamar;

class TipeKamarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipeKamar::create([
            'nama_tipe' => 'Standar',
            'harga' => 762000,
        ]);

        TipeKamar::create([
            'nama_tipe' => 'Deluxe',
            'harga' => 983000,
        ]);

        TipeKamar::create([
            'nama_tipe' => 'Premium',
            'harga' => 1145000,
        ]);
    }
}
