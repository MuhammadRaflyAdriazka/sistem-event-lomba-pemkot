<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Dinas;

class DinasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Dinas::create(['name' => 'Dinas Komunikasi, Informatika dan Statistik']);
        Dinas::create(['name' => 'Dinas Kebudayaan, Kepemudaan, Olahraga dan Pariwisata']);
        Dinas::create(['name' => 'Dinas Perdagangan dan Perindustrian & Koperasi']);
    }
}
