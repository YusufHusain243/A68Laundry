<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        User::create([
            'username' => 'Owner',
            'password' => bcrypt('Owner'), // pastikan terenkripsi
            'nama' => 'owner',
            'no_hp' => '08111111111',
            'email' => 'owner@gmail.com',
            'alamat' => 'alamat',
            'role' => 'Owner',
        ]);

        User::create([
            'username' => 'Staff',
            'password' => bcrypt('Staff'), // pastikan terenkripsi
            'nama' => 'Staff',
            'no_hp' => '08222222222',
            'email' => 'Staff@gmail.com',
            'alamat' => 'alamat',
            'role' => 'Staff',
        ]);
    }

}
