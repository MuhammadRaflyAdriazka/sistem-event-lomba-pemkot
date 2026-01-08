<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dinas;
use App\Models\User;
use App\Models\Acara;
use App\Models\PanitiaAcara;
use App\Models\KolomFormulirAcara;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class LombaPantunSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ambil Dinas Kebudayaan
        $dinas = Dinas::where('name', 'Dinas Kebudayaan, Kepemudaan, Olahraga dan Pariwisata')->first();

        if (!$dinas) {
            $this->command->error('Dinas tidak ditemukan! Pastikan DinasSeeder sudah dijalankan.');
            return;
        }

        // 2. Buat akun panitia untuk lomba pantun
        $panitiaUser = User::firstOrCreate(
            ['email' => 'rahmat.panitia.pantun@pemkot.id'],
            [
                'name' => 'Rahmat Hidayat',
                'password' => Hash::make('password123'),
                'peran' => 'panitia',
                'id_dinas' => $dinas->id,
            ]
        );

        // 3. Buat Acara Lomba Pantun - PENDAFTARAN SUDAH DITUTUP
        $acara = Acara::create([
            'judul' => 'Lomba Pantun Banjar 2026',
            'tentang' => 'Lomba berbalas pantun dalam bahasa Banjar yang menampilkan kecerdasan dan kecepatan peserta dalam menyusun pantun dengan tema budaya, adat, dan kehidupan sehari-hari masyarakat Banjar. Lomba ini menguji kemampuan improvisasi, pemahaman bahasa Banjar, dan kreativitas dalam merangkai kata-kata yang indah dan bermakna.',
            'id_dinas' => $dinas->id,
            'tanggal_acara' => '2025-01-01',
            'lokasi' => 'Taman Siring Banjarmasin, Jl. Pierre Tendean, Banjarmasin',
            'biaya' => 'Gratis',
            'kuota' => 50,
            'tanggal_mulai_daftar' => Carbon::now()->subDays(40)->format('Y-m-d'),
            'tanggal_akhir_daftar' => Carbon::now()->subDays(10)->format('Y-m-d'), // Sudah lewat 10 hari yang lalu
            'sistem_pendaftaran' => 'Tanpa Seleksi',
            'kategori_acara' => 'Kesenian & Budaya',
            'persyaratan' => 'Warga Kota Banjarmasin atau Kalimantan Selatan, usia minimal 13 tahun, fasih berbahasa Banjar, memahami struktur dan kaidah pantun, mampu berpantun secara spontan',
            'hadiah' => 'Juara 1: Rp 6.000.000 + Buku Pantun dan Sastra Banjar + Piala + Sertifikat, Juara 2: Rp 4.000.000 + Pakaian Adat Banjar + Piala + Sertifikat, Juara 3: Rp 2.500.000 + Kamus Bahasa Banjar + Piala + Sertifikat, 3 Juara Harapan: Voucher Toko Buku Rp 500.000 + Sertifikat',
            'status' => 'active',
            'gambar' => 'lomba-pantun-banner.jpg',
        ]);

        // 4. Assign panitia ke acara
        PanitiaAcara::create([
            'id_acara' => $acara->id,
            'id_pengguna' => $panitiaUser->id,
        ]);

        // 5. Buat Kolom Formulir Pendaftaran
        $formFields = [
            [
                'nama_kolom' => 'nama_lengkap',
                'tipe_kolom' => 'text',
                'label_kolom' => 'Nama Lengkap',
                'wajib_diisi' => true,
                'urutan_kolom' => 1,
            ],
            [
                'nama_kolom' => 'nomor_telepon',
                'tipe_kolom' => 'text',
                'label_kolom' => 'Nomor Telepon/WhatsApp',
                'wajib_diisi' => true,
                'urutan_kolom' => 2,
            ],
            [
                'nama_kolom' => 'alamat_lengkap',
                'tipe_kolom' => 'textarea',
                'label_kolom' => 'Alamat Lengkap',
                'wajib_diisi' => true,
                'urutan_kolom' => 3,
            ],
            [
                'nama_kolom' => 'tanggal_lahir',
                'tipe_kolom' => 'text',
                'label_kolom' => 'Tanggal Lahir',
                'wajib_diisi' => true,
                'urutan_kolom' => 4,
            ],
            [
                'nama_kolom' => 'tingkat_kemampuan',
                'tipe_kolom' => 'text',
                'label_kolom' => 'Tingkat Kemampuan (Pemula/Menengah/Mahir)',
                'wajib_diisi' => true,
                'urutan_kolom' => 5,
            ],
            [
                'nama_kolom' => 'contoh_pantun',
                'tipe_kolom' => 'textarea',
                'label_kolom' => 'Contoh Pantun Banjar Karya Sendiri (minimal 2 bait)',
                'wajib_diisi' => true,
                'urutan_kolom' => 6,
            ],
            [
                'nama_kolom' => 'pengalaman_berpantun',
                'tipe_kolom' => 'textarea',
                'label_kolom' => 'Pengalaman Berpantun (jika ada)',
                'wajib_diisi' => false,
                'urutan_kolom' => 7,
            ],
            [
                'nama_kolom' => 'foto_ktp',
                'tipe_kolom' => 'file',
                'label_kolom' => 'Upload KTP (Format: JPG/PNG, maksimal 2MB)',
                'wajib_diisi' => true,
                'urutan_kolom' => 8,
            ],
        ];

        foreach ($formFields as $field) {
            KolomFormulirAcara::create([
                'id_acara' => $acara->id,
                'nama_kolom' => $field['nama_kolom'],
                'tipe_kolom' => $field['tipe_kolom'],
                'label_kolom' => $field['label_kolom'],
                'wajib_diisi' => $field['wajib_diisi'],
                'urutan_kolom' => $field['urutan_kolom'],
            ]);
        }

        // Output success message
        $this->command->info('✅ Data Lomba Pantun berhasil dibuat!');
        $this->command->info('📋 Acara: ' . $acara->judul);
        $this->command->info('🏢 Dinas: ' . $dinas->name);
        $this->command->info('👥 Peserta: 0 orang (belum ada pendaftar)');
        $this->command->info('👨‍💼 Panitia: ' . $panitiaUser->name . ' (' . $panitiaUser->email . ')');
        $this->command->info('📝 Form: 8 kolom termasuk upload KTP dan contoh pantun');
        $this->command->info('📊 Kuota: 50 peserta');
        $this->command->info('✨ Sistem: Tanpa Seleksi (Auto Diterima)');
        $this->command->info('⏰ PENDAFTARAN SUDAH DITUTUP (10 hari yang lalu)');
        $this->command->info('📅 Acara akan dilaksanakan 20 hari lagi');
        $this->command->info('');
        $this->command->info('🔐 Akun panitia: ' . $panitiaUser->email . ' / password123');
    }
}
