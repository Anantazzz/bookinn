<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         User::create([
            'name' => 'Admin Hotel',
            'email' => 'admin@hotel.com',
            'password' => Hash::make('admin123'),
            'alamat' => '',
            'no_hp' => '',
            'role' => 'admin'
        ]);

        User::create([
            'name' => 'Resepsionis Juwita',
            'email' => 'resep@mail.com',
            'password' => Hash::make('resep123'),
            'alamat' => '',
            'no_hp' => '082233445566',
            'role' => 'resepsionis'
        ]);

        User::create([
            'name' => 'Owner1',
            'email' => 'owner1@mail.com',
            'password' => Hash::make('owner123'),
            'alamat' => '',
            'no_hp' => '074783657623',
            'role' => 'owner'
        ]);

        User::create([
            'name' => 'Yunia',
            'email' => 'yunia@mail.com',
            'password' => Hash::make('yunia123'),
            'alamat' => 'Jl. mekar No. 99',
            'no_hp' => '081426561739',
            'role' => 'user'
        ]);
    }
}
