<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Acara;
use App\Models\User;
use App\Models\Dinas;
use App\Models\PanitiaAcara;
use App\Models\KolomFormulirAcara;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class LombaSasiranganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ambil Dinas Kebudayaan yang sudah ada
        $dinas = Dinas::where('name', 'like', '%Kebudayaan%')->first();
        
        if (!$dinas) {
            $this->command->error('❌ Dinas Kebudayaan tidak ditemukan! Jalankan DinasSeeder terlebih dahulu.');
            return;
        }

        // 2. Buat akun panitia lomba sasirangan
        $panitiaUser = User::firstOrCreate(
            ['email' => 'ani.panitia.sasirangan@pemkot.id'],
            [
                'name' => 'Ani Rahmawati',
                'password' => Hash::make('password123'),
                'peran' => 'panitia',
                'id_dinas' => $dinas->id,
            ]
        );

        // 3. Buat Acara Lomba Sasirangan
        $acara = Acara::create([
            'judul' => 'Lomba Membuat Kerajinan Sasirangan 2026',
            'tentang' => 'Lomba membuat kerajinan khas Banjarmasin menggunakan teknik sasirangan tradisional. Peserta diharapkan dapat membuat karya sasirangan yang menunjukkan kreativitas dan keterampilan dalam melestarikan warisan budaya Banjar. Lomba ini terbuka untuk umum dengan sistem seleksi berdasarkan portofolio dan proposal desain.',
            'id_dinas' => $dinas->id,
            'tanggal_acara' => Carbon::now()->addDays(60)->format('Y-m-d'),
            'lokasi' => 'Gedung Kerajinan Sasirangan, Jl. Pramuka No. 88, Banjarmasin',
            'biaya' => 'Gratis',
            'kuota' => 50,
            'tanggal_mulai_daftar' => Carbon::now()->format('Y-m-d'),
            'tanggal_akhir_daftar' => Carbon::now()->addDays(30)->format('Y-m-d'),
            'sistem_pendaftaran' => 'Seleksi',
            'kategori_acara' => 'Kesenian & Budaya',
            'persyaratan' => 'Warga Kota Banjarmasin atau berdomisili di Kalimantan Selatan, memiliki pengalaman atau minat dalam kerajinan tangan, menyertakan portofolio karya (jika ada), membuat proposal desain sasirangan yang akan dibuat, menggunakan bahan dan teknik sasirangan tradisional',
            'hadiah' => 'Juara 1: Rp 10.000.000 + Paket Alat Sasirangan Lengkap + Piala + Sertifikat, Juara 2: Rp 7.000.000 + Kain Sasirangan Premium + Piala + Sertifikat, Juara 3: Rp 5.000.000 + Starter Kit Sasirangan + Piala + Sertifikat, 5 Juara Harapan: Voucher Belanja Rp 1.000.000 + Sertifikat',
            'status' => 'active',
            'gambar' => 'lomba-sasirangan-banner.jpg',
        ]);

        // 4. Assign panitia ke acara
        PanitiaAcara::create([
            'id_acara' => $acara->id,
            'id_pengguna' => $panitiaUser->id,
        ]);

        // 5. Buat Kolom Formulir Pendaftaran
        $kolomFormulir = [
            [
                'nama_kolom' => 'nama_lengkap',
                'label_kolom' => 'Nama Lengkap',
                'tipe_kolom' => 'text',
                'wajib_diisi' => true,
                'placeholder' => 'Masukkan nama lengkap',
                'urutan_kolom' => 1,
            ],
            [
                'nama_kolom' => 'nomor_telepon',
                'label_kolom' => 'Nomor Telepon/WhatsApp',
                'tipe_kolom' => 'text',
                'wajib_diisi' => true,
                'placeholder' => 'Contoh: 08123456789',
                'urutan_kolom' => 2,
            ],
            [
                'nama_kolom' => 'alamat',
                'label_kolom' => 'Alamat Lengkap',
                'tipe_kolom' => 'textarea',
                'wajib_diisi' => true,
                'placeholder' => 'Masukkan alamat lengkap',
                'urutan_kolom' => 3,
            ],
            [
                'nama_kolom' => 'tanggal_lahir',
                'label_kolom' => 'Tanggal Lahir',
                'tipe_kolom' => 'text',
                'wajib_diisi' => true,
                'placeholder' => 'DD/MM/YYYY',
                'urutan_kolom' => 4,
            ],
            [
                'nama_kolom' => 'pengalaman_kerajinan',
                'label_kolom' => 'Pengalaman Membuat Kerajinan',
                'tipe_kolom' => 'textarea',
                'wajib_diisi' => true,
                'placeholder' => 'Ceritakan pengalaman Anda dalam membuat kerajinan tangan',
                'urutan_kolom' => 5,
            ],
            [
                'nama_kolom' => 'proposal_desain',
                'label_kolom' => 'Proposal Desain Sasirangan',
                'tipe_kolom' => 'textarea',
                'wajib_diisi' => true,
                'placeholder' => 'Jelaskan konsep desain sasirangan yang akan Anda buat',
                'urutan_kolom' => 6,
            ],
            [
                'nama_kolom' => 'portofolio',
                'label_kolom' => 'File Portofolio Karya (PDF/JPG)',
                'tipe_kolom' => 'file',
                'wajib_diisi' => false,
                'placeholder' => 'Upload portofolio karya Anda (opsional)',
                'urutan_kolom' => 7,
            ],
            [
                'nama_kolom' => 'ktp',
                'label_kolom' => 'Foto KTP/Kartu Identitas',
                'tipe_kolom' => 'file',
                'wajib_diisi' => true,
                'placeholder' => 'Upload KTP (JPG/PNG/PDF)',
                'urutan_kolom' => 8,
            ],
        ];

        foreach ($kolomFormulir as $kolom) {
            KolomFormulirAcara::create(array_merge(['id_acara' => $acara->id], $kolom));
        }

        // 6. TIDAK ADA PESERTA (0 peserta)
        // Tidak membuat data pendaftaran sama sekali

        $this->command->info('✅ Data Lomba Sasirangan berhasil dibuat!');
        $this->command->info('📋 Acara: ' . $acara->judul);
        $this->command->info('🏢 Dinas: ' . $dinas->name);
        $this->command->info('👥 Peserta: 0 orang (belum ada pendaftar)');
        $this->command->info('👨‍💼 Panitia: ' . $panitiaUser->name . ' (' . $panitiaUser->email . ')');
        $this->command->info('📝 Form: 8 kolom termasuk upload KTP dan portofolio');
        $this->command->info('📊 Kuota: 50 peserta');
        $this->command->info('🔍 Sistem: Seleksi (Panitia harus menerima/menolak)');
        $this->command->info('');
        $this->command->info('🔐 Akun panitia: ani.panitia.sasirangan@pemkot.id / password123');
    }
}
