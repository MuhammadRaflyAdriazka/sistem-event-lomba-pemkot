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

class LombaMadihinSeeder extends Seeder
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

        // 2. Buat akun panitia untuk lomba madihin
        $panitiaUser = User::firstOrCreate(
            ['email' => 'hasan.panitia.madihin@pemkot.id'],
            [
                'name' => 'Hasan Basri',
                'password' => Hash::make('password123'),
                'peran' => 'panitia',
                'id_dinas' => $dinas->id,
            ]
        );

        // 3. Buat Acara Lomba Madihin
        $acara = Acara::create([
            'judul' => 'Lomba Madihin Banjarmasin 2026',
            'tentang' => 'Lomba Madihin adalah kompetisi seni bertutur tradisional khas Banjar yang menggabungkan pantun, syair, dan irama rebana. Peserta akan menampilkan madihin dengan tema budaya Banjar, baik secara solo maupun kelompok. Lomba ini terbuka untuk umum dan bertujuan melestarikan warisan budaya Banjarmasin yang kaya akan seni bertutur dan musik tradisional.',
            'id_dinas' => $dinas->id,
            'tanggal_acara' => Carbon::now()->addDays(45)->format('Y-m-d'),
            'lokasi' => 'Gedung Kesenian Banjarmasin, Jl. Lambung Mangkurat No. 25, Banjarmasin',
            'biaya' => 'Gratis',
            'kuota' => 60,
            'tanggal_mulai_daftar' => Carbon::now()->format('Y-m-d'),
            'tanggal_akhir_daftar' => Carbon::now()->addDays(25)->format('Y-m-d'),
            'sistem_pendaftaran' => 'Tanpa Seleksi',
            'kategori_acara' => 'Kesenian & Budaya',
            'persyaratan' => 'Warga Kota Banjarmasin atau Kalimantan Selatan, usia minimal 15 tahun, menguasai bahasa Banjar, memiliki pengetahuan dasar tentang madihin atau seni bertutur Banjar, bersedia tampil dengan kostum tradisional Banjar',
            'hadiah' => 'Juara 1: Rp 8.000.000 + Seperangkat Rebana Premium + Piala + Sertifikat, Juara 2: Rp 5.000.000 + Rebana Tradisional + Piala + Sertifikat, Juara 3: Rp 3.000.000 + Pakaian Adat Banjar + Piala + Sertifikat, 3 Juara Harapan: Voucher Belanja Rp 500.000 + Sertifikat',
            'status' => 'active',
            'gambar' => 'lomba-madihin-banner.jpg',
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
                'nama_kolom' => 'kategori_lomba',
                'tipe_kolom' => 'text',
                'label_kolom' => 'Kategori Lomba (Solo/Kelompok)',
                'wajib_diisi' => true,
                'urutan_kolom' => 5,
            ],
            [
                'nama_kolom' => 'pengalaman_madihin',
                'tipe_kolom' => 'textarea',
                'label_kolom' => 'Pengalaman dalam Madihin atau Seni Bertutur (jika ada)',
                'wajib_diisi' => false,
                'urutan_kolom' => 6,
            ],
            [
                'nama_kolom' => 'video_madihin',
                'tipe_kolom' => 'file',
                'label_kolom' => 'Upload Video Madihin (MP4, maksimal 50MB) - Opsional',
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
        $this->command->info('✅ Data Lomba Madihin berhasil dibuat!');
        $this->command->info('📋 Acara: ' . $acara->judul);
        $this->command->info('🏢 Dinas: ' . $dinas->name);
        $this->command->info('👥 Peserta: 0 orang (belum ada pendaftar)');
        $this->command->info('👨‍💼 Panitia: ' . $panitiaUser->name . ' (' . $panitiaUser->email . ')');
        $this->command->info('📝 Form: 8 kolom termasuk upload KTP dan video madihin');
        $this->command->info('📊 Kuota: 60 peserta');
        $this->command->info('✨ Sistem: Tanpa Seleksi (Auto Diterima)');
        $this->command->info('');
        $this->command->info('🔐 Akun panitia: ' . $panitiaUser->email . ' / password123');
    }
}
