<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Acara;
use App\Models\User;
use App\Models\Dinas;
use App\Models\PanitiaAcara;
use App\Models\KolomFormulirAcara;
use App\Models\Pendaftaran;
use App\Models\DataPendaftaran;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class LombaMemasakSeeder extends Seeder
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

        // 2. Ambil admin dinas yang sudah ada
        $adminDinas = User::where('id_dinas', $dinas->id)
            ->where('peran', 'admin_dinas')
            ->first();

        if (!$adminDinas) {
            $this->command->error('❌ Admin dinas tidak ditemukan!');
            return;
        }

        // 3. Buat atau ambil 1 akun panitia lomba memasak
        $panitiaUser = User::firstOrCreate(
            ['email' => 'siti.panitia.memasak@pemkot.id'],
            [
                'name' => 'Siti Nurhaliza',
                'password' => Hash::make('password123'),
                'peran' => 'panitia',
                'id_dinas' => $dinas->id,
            ]
        );

        // 4. Buat Acara Lomba Memasak
        $acara = Acara::create([
            'judul' => 'Lomba Memasak Tradisional Nusantara 2026',
            'tentang' => 'Lomba memasak untuk melestarikan kuliner tradisional Indonesia. Peserta akan memasak menu makanan khas daerah dengan menggunakan resep turun temurun. Lomba ini bertujuan untuk mengenalkan dan melestarikan kekayaan kuliner nusantara kepada generasi muda.',
            'id_dinas' => $dinas->id,
            'tanggal_acara' => Carbon::now()->addDays(30)->format('Y-m-d'),
            'lokasi' => 'Gedung Serbaguna Pemkot, Jl. Merdeka No. 123',
            'biaya' => 'Gratis',
            'kuota' => 100,
            'tanggal_mulai_daftar' => Carbon::now()->subDays(7)->format('Y-m-d'),
            'tanggal_akhir_daftar' => Carbon::now()->addDays(14)->format('Y-m-d'),
            'sistem_pendaftaran' => 'Seleksi',
            'kategori_acara' => 'Kuliner',
            'persyaratan' => 'Berusia minimal 18 tahun, memiliki sertifikat memasak atau pengalaman memasak minimal 1 tahun, membawa peralatan memasak sendiri, menguasai resep masakan tradisional',
            'hadiah' => 'Juara 1: Rp 10.000.000 + Piala + Sertifikat, Juara 2: Rp 7.500.000 + Piala + Sertifikat, Juara 3: Rp 5.000.000 + Piala + Sertifikat',
            'status' => 'active',
            'gambar' => 'lomba-memasak-banner.jpg',
        ]);

        // 5. Assign panitia ke acara
        PanitiaAcara::create([
            'id_acara' => $acara->id,
            'id_pengguna' => $panitiaUser->id,
        ]);

        // 6. Buat Kolom Formulir Pendaftaran
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
                'label_kolom' => 'Nomor Telepon',
                'tipe_kolom' => 'text',
                'wajib_diisi' => true,
                'placeholder' => 'Contoh: 08123456789',
                'urutan_kolom' => 2,
            ],
            [
                'nama_kolom' => 'alamat_lengkap',
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
                'nama_kolom' => 'jenis_kelamin',
                'label_kolom' => 'Jenis Kelamin',
                'tipe_kolom' => 'text',
                'wajib_diisi' => true,
                'placeholder' => 'Laki-laki / Perempuan',
                'urutan_kolom' => 5,
            ],
            [
                'nama_kolom' => 'sertifikat_memasak',
                'label_kolom' => 'Sertifikat Memasak',
                'tipe_kolom' => 'file',
                'wajib_diisi' => true,
                'placeholder' => 'Upload sertifikat (PDF/JPG)',
                'urutan_kolom' => 6,
            ],
            [
                'nama_kolom' => 'pengalaman_memasak',
                'label_kolom' => 'Pengalaman Memasak',
                'tipe_kolom' => 'textarea',
                'wajib_diisi' => true,
                'placeholder' => 'Ceritakan pengalaman memasak Anda',
                'urutan_kolom' => 7,
            ],
            [
                'nama_kolom' => 'menu_masakan',
                'label_kolom' => 'Menu yang Akan Dimasak',
                'tipe_kolom' => 'text',
                'wajib_diisi' => true,
                'placeholder' => 'Contoh: Rendang Daging Sapi',
                'urutan_kolom' => 8,
            ],
        ];

        foreach ($kolomFormulir as $kolom) {
            KolomFormulirAcara::create(array_merge(['id_acara' => $acara->id], $kolom));
        }

        // 7. Buat 99 akun peserta real dengan nama Indonesia
        $namaPeserta = [
            'Ahmad Rizki Maulana', 'Siti Aisyah Rahma', 'Budi Prasetyo Nugroho', 'Dewi Lestari Putri', 'Eko Setiawan Wijaya',
            'Fitri Handayani Sari', 'Gunawan Santoso Adi', 'Hana Pertiwi Indah', 'Indra Kusuma Jaya', 'Joko Widodo Susilo',
            'Kartika Sari Dewi', 'Lukman Hakim Rahman', 'Maya Angelina Putri', 'Nur Hidayat Ibrahim', 'Olivia Ramadhani',
            'Putra Mahardika Pratama', 'Qori Sumantri Hakim', 'Rina Wijaya Sari', 'Slamet Riyadi Santoso', 'Tika Ramadhan',
            'Umar Bakri Habibie', 'Vina Amalia Safitri', 'Wahyu Nugroho Adi', 'Xena Purnama Sari', 'Yusuf Ibrahim Ahmad',
            'Zahra Maulida Putri', 'Adi Nugraha Pratama', 'Bella Safitri Dewi', 'Candra Wijaya Kusuma', 'Diah Ayu Lestari',
            'Edi Susanto Wijaya', 'Farah Diba Maharani', 'Gilang Ramadhan Putra', 'Hasna Zahira Putri', 'Irfan Hakim Maulana',
            'Jasmine Putri Amelia', 'Kevin Pratama Wijaya', 'Laila Maharani Sari', 'Muhammad Iqbal Rahman', 'Nadia Kusuma Dewi',
            'Oscar Habibie Wijaya', 'Putri Amelia Safitri', 'Qonita Azzahra Maulida', 'Rudi Hartono Santoso', 'Salma Nurhaliza',
            'Taufik Hidayat Putra', 'Ulfa Rahmawati Sari', 'Vino Bastian Adi', 'Winda Hamidah Putri', 'Yoga Prasetya Kusuma',
            'Zaki Anwar Rahman', 'Amir Mahmud Hakim', 'Bunga Citra Lestari', 'Dani Setiawan Putra', 'Elsa Maharani Dewi',
            'Farhan Ahmad Maulana', 'Gita Savitri Dewi', 'Hadi Purnomo Adi', 'Intan Permata Sari', 'Jihan Aulia Putri',
            'Kurnia Sari Dewi', 'Leo Saputra Wijaya', 'Melati Putri Indah', 'Naufal Rahman Hakim', 'Okta Wijaya Putra',
            'Pandu Wijaya Kusuma', 'Rahma Wati Safitri', 'Surya Pratama Adi', 'Tiara Andini Putri', 'Udin Setiawan',
            'Vera Anggraini Dewi', 'Wawan Kurniawan Adi', 'Yanto Basuki Santoso', 'Zakiyah Nurjanah', 'Ari Wibowo Kusuma',
            'Bayu Aji Pratama', 'Cindy Paramita Sari', 'Doni Saputra Wijaya', 'Erna Safitri Dewi', 'Faisal Ahmad Rahman',
            'Galih Prasetyo Adi', 'Hesti Purwaningsih', 'Ilham Maulana Putra', 'Jesica Wulandari', 'Kiki Amalia Putri',
            'Lina Marlina Sari', 'Mira Lestari Dewi', 'Nina Zatulini', 'Oki Setiana Dewi', 'Prima Hapsari',
            'Rizal Ramli Hakim', 'Sinta Nuriyah Safitri', 'Tono Suratman Adi', 'Umi Kalsum Dewi', 'Vita Anggraeni',
            'Willy Dozan Putra', 'Yanti Nurfadilah', 'Zainal Abidin Rahman', 'Andika Pratama Wijaya', 'Butet Kartaredjasa',
        ];

        $menuMasakan = [
            'Rendang Daging Sapi', 'Soto Ayam Lamongan', 'Gudeg Jogja', 'Rawon Surabaya',
            'Nasi Liwet Solo', 'Pempek Palembang', 'Sate Padang', 'Bakso Malang',
            'Ayam Betutu Bali', 'Ikan Bakar Manado', 'Papeda Papua', 'Soto Banjar',
            'Nasi Kuning Manado', 'Ayam Taliwang', 'Pecel Madiun', 'Lontong Sayur Padang',
            'Soto Betawi', 'Gulai Kambing', 'Ayam Geprek', 'Nasi Goreng Spesial',
        ];

        $pengalamanMemasak = [
            'Memiliki pengalaman memasak sejak kecil, sering membantu orang tua di dapur. Sudah mengikuti beberapa kursus memasak dan workshop kuliner.',
            'Lulusan Sekolah Menengah Kejuruan Tata Boga dan aktif di komunitas pecinta kuliner. Sering membuat konten resep masakan di media sosial.',
            'Memiliki usaha katering rumahan sejak 3 tahun lalu. Spesialisasi masakan tradisional dengan cita rasa autentik.',
            'Chef profesional dengan pengalaman 5 tahun di berbagai restoran. Passion dalam mengangkat kuliner tradisional Indonesia.',
            'Ibu rumah tangga yang hobi memasak dan sering menjual hasil masakan untuk acara keluarga dan kantor.',
            'Mahasiswa Tata Boga yang aktif mengikuti lomba memasak tingkat regional. Pernah juara 2 di lomba masak tradisional.',
            'Pemilik warung makan dengan menu khas daerah. Resep turun temurun dari nenek yang sudah puluhan tahun.',
            'Penggemar kuliner yang belajar secara otodidak dari internet dan buku resep. Sering eksperimen dengan berbagai resep tradisional.',
        ];

        $kota = ['Jakarta', 'Surabaya', 'Bandung', 'Semarang', 'Yogyakarta', 'Malang', 'Solo', 'Medan', 'Makassar', 'Denpasar'];
        $jalan = ['Merdeka', 'Sudirman', 'Gatot Subroto', 'Ahmad Yani', 'Diponegoro', 'Thamrin', 'Hayam Wuruk', 'Pahlawan', 'Veteran', 'Pemuda'];

        for ($i = 0; $i < 99; $i++) {
            // Generate email unik dengan nomor
            $nama = $namaPeserta[$i];
            $emailName = strtolower(str_replace(' ', '.', $nama));
            $email = $emailName . ($i + 1) . '@gmail.com';

            // Buat akun peserta
            $peserta = User::create([
                'name' => $nama,
                'email' => $email,
                'password' => Hash::make('password123'),
                'peran' => 'peserta',
                'email_verified_at' => now(),
            ]);

            // Semua peserta statusnya pending (menunggu seleksi)
            $status = 'pending';

            // Buat pendaftaran
            $pendaftaran = Pendaftaran::create([
                'id_acara' => $acara->id,
                'id_pengguna' => $peserta->id,
                'status' => $status,
            ]);

            // Isi data pendaftaran sesuai kolom formulir
            $jenisKelamin = ['Laki-laki', 'Perempuan'][rand(0, 1)];
            $tanggalLahir = rand(1, 28) . '/' . rand(1, 12) . '/' . rand(1978, 2005);
            $nomorTelepon = '08' . str_pad(rand(1000000000, 9999999999), 10, '0', STR_PAD_LEFT);
            
            // Buat file dummy sertifikat PDF
            $namaFileSertifikat = 'sertifikat_' . strtolower(str_replace(' ', '_', $nama)) . '.pdf';
            $pathSertifikat = storage_path('app/public/' . $namaFileSertifikat);
            
            // Pastikan folder ada
            if (!file_exists(storage_path('app/public'))) {
                mkdir(storage_path('app/public'), 0755, true);
            }
            
            // Buat file PDF dummy sederhana
            $pdfContent = "%PDF-1.4\n1 0 obj<</Type/Catalog/Pages 2 0 R>>endobj\n2 0 obj<</Type/Pages/Kids[3 0 R]/Count 1>>endobj\n3 0 obj<</Type/Page/MediaBox[0 0 612 792]/Parent 2 0 R/Resources<<>>>>endobj\nxref\n0 4\n0000000000 65535 f\n0000000009 00000 n\n0000000052 00000 n\n0000000101 00000 n\ntrailer<</Size 4/Root 1 0 R>>\nstartxref\n178\n%%EOF";
            file_put_contents($pathSertifikat, $pdfContent);
            
            $dataFormulir = [
                'nama_lengkap' => $nama,
                'nomor_telepon' => $nomorTelepon,
                'alamat_lengkap' => 'Jl. ' . $jalan[rand(0, count($jalan) - 1)] . ' No. ' . rand(1, 200) . ', Kota ' . $kota[rand(0, count($kota) - 1)],
                'tanggal_lahir' => $tanggalLahir,
                'jenis_kelamin' => $jenisKelamin,
                'sertifikat_memasak' => $namaFileSertifikat,
                'pengalaman_memasak' => $pengalamanMemasak[rand(0, count($pengalamanMemasak) - 1)],
                'menu_masakan' => $menuMasakan[rand(0, count($menuMasakan) - 1)],
            ];

            foreach ($dataFormulir as $namaKolom => $nilai) {
                DataPendaftaran::create([
                    'id_pendaftaran' => $pendaftaran->id,
                    'nama_kolom' => $namaKolom,
                    'nilai_kolom' => $nilai,
                ]);
            }
        }

        $this->command->info('✅ Data Lomba Memasak berhasil dibuat!');
        $this->command->info('📋 Acara: ' . $acara->judul);
        $this->command->info('🏢 Dinas: ' . $dinas->name);
        $this->command->info('👥 Peserta: 99 orang (bisa login dengan password: password123)');
        $this->command->info('👨‍💼 Panitia: ' . $panitiaUser->name . ' (' . $panitiaUser->email . ')');
        $this->command->info('📝 Form: 8 kolom termasuk upload Sertifikat Memasak');
        $this->command->info('📊 Kuota: 100 peserta');
        $this->command->info('');
        $this->command->info('🔐 Semua akun peserta bisa login dengan email dan password: password123');
    }
}
