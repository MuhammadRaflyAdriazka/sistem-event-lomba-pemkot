<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class KepalaDinasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kepala Dinas Kominfotik (ID 1)
        User::updateOrCreate(
            ['email' => 'kepala.kominfotik@gmail.com'],
            [
                'name' => 'Kepala Dinas Kominfotik',
                'password' => Hash::make('password123'),
                'peran' => 'kepala_dinas',
                'id_dinas' => 1,
            ]
        );

        // Kepala Dinas Budpar (ID 2)
        User::updateOrCreate(
            ['email' => 'kepala.disbudpar@gmail.com'],
            [
                'name' => 'Kepala Dinas Budpar',
                'password' => Hash::make('password123'),
                'peran' => 'kepala_dinas',
                'id_dinas' => 2,
            ]
        );

        // Kepala Dinas Disperindag (ID 3)
        User::updateOrCreate(
            ['email' => 'kepala.disperindag@gmail.com'],
            [
                'name' => 'Kepala Dinas Disperindag',
                'password' => Hash::make('password123'),
                'peran' => 'kepala_dinas',
                'id_dinas' => 3,
            ]
        );
    }
}
