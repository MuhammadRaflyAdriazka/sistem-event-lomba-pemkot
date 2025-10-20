<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User; // <-- TAMBAHKAN BARIS INI
use Illuminate\Support\Facades\Hash; // <-- TAMBAHKAN JUGA BARIS INI

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin untuk Dinas Kominfotik (ID 1)
        User::create([
            'name' => 'Admin Diskominfotik',
            'email' => 'admin.kominfotik@gmail.com',
            'password' => Hash::make('password123'),
            'peran' => 'admin_dinas',
            'id_dinas' => 1, // Pastikan ada dinas dengan ID 1 di DinasSeeder
        ]);

        // Admin untuk Dinas Budpar (ID 2)
        User::create([
            'name' => 'Admin Disbudpar',
            'email' => 'admin.disbudpar@gmail.com',
            'password' => Hash::make('password123'),
            'peran' => 'admin_dinas',
            'id_dinas' => 2, // Pastikan ada dinas dengan ID 2 di DinasSeeder
        ]);

        // Admin untuk Dinas Disperindag (ID 3)
        User::create([
            'name' => 'Admin Disperindag',
            'email' => 'admin.disperindag@gmail.com',
            'password' => Hash::make('password123'),
            'peran' => 'admin_dinas',
            'id_dinas' => 3, // Pastikan ada dinas dengan ID 3 di DinasSeeder
        ]);
    }
}