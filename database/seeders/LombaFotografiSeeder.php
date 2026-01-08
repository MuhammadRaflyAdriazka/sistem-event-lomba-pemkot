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

class LombaFotografiSeeder extends Seeder
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

        // 2. Buat akun panitia lomba fotografi
        $panitiaUser = User::firstOrCreate(
            ['email' => 'budi.panitia.fotografi@pemkot.id'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password123'),
                'peran' => 'panitia',
                'id_dinas' => $dinas->id,
            ]
        );

        // 3. Buat Acara Lomba Fotografi
        $acara = Acara::create([
            'judul' => 'Lomba Fotografi Budaya Banjarmasin 2026',
            'tentang' => 'Lomba fotografi untuk mengabadikan keindahan budaya dan tradisi Kota Banjarmasin. Peserta bebas mengekspresikan kreativitas melalui foto-foto bertema budaya lokal, wisata, kuliner khas, pasar terapung, dan kehidupan masyarakat Banjarmasin. Lomba terbuka untuk umum tanpa seleksi.',
            'id_dinas' => $dinas->id,
            'tanggal_acara' => Carbon::now()->addDays(45)->format('Y-m-d'),
            'lokasi' => 'Balai Kota Banjarmasin, Jl. Sultan Adam No. 18',
            'biaya' => 'Gratis',
            'kuota' => 120,
            'tanggal_mulai_daftar' => Carbon::now()->subDays(5)->format('Y-m-d'),
            'tanggal_akhir_daftar' => Carbon::now()->addDays(20)->format('Y-m-d'),
            'sistem_pendaftaran' => 'Tanpa Seleksi',
            'kategori_acara' => 'Pariwisata',
            'persyaratan' => 'Warga Kota Banjarmasin atau berdomisili di Banjarmasin, memiliki kamera DSLR/Mirrorless/Smartphone, foto harus original karya sendiri, foto diambil di wilayah Banjarmasin, maksimal 3 foto per peserta',
            'hadiah' => 'Juara 1: Rp 15.000.000 + Kamera DSLR + Piala + Sertifikat, Juara 2: Rp 10.000.000 + Lensa Kamera + Piala + Sertifikat, Juara 3: Rp 7.500.000 + Tripod Pro + Piala + Sertifikat, 10 Juara Harapan: Tas Kamera + Sertifikat',
            'status' => 'active',
            'gambar' => 'lomba-fotografi-banner.jpg',
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
                'nama_kolom' => 'email',
                'label_kolom' => 'Alamat Email',
                'tipe_kolom' => 'text',
                'wajib_diisi' => true,
                'placeholder' => 'contoh@email.com',
                'urutan_kolom' => 3,
            ],
            [
                'nama_kolom' => 'alamat',
                'label_kolom' => 'Alamat Lengkap',
                'tipe_kolom' => 'textarea',
                'wajib_diisi' => true,
                'placeholder' => 'Masukkan alamat lengkap di Banjarmasin',
                'urutan_kolom' => 4,
            ],
            [
                'nama_kolom' => 'jenis_kamera',
                'label_kolom' => 'Jenis Kamera yang Digunakan',
                'tipe_kolom' => 'text',
                'wajib_diisi' => true,
                'placeholder' => 'Contoh: Canon EOS 80D / iPhone 13 Pro',
                'urutan_kolom' => 5,
            ],
            [
                'nama_kolom' => 'tema_foto',
                'label_kolom' => 'Tema Foto yang Dipilih',
                'tipe_kolom' => 'text',
                'wajib_diisi' => true,
                'placeholder' => 'Pilih: Budaya/Wisata/Kuliner/Pasar Terapung/Kehidupan Sehari-hari',
                'urutan_kolom' => 6,
            ],
            [
                'nama_kolom' => 'portofolio',
                'label_kolom' => 'Link Portofolio Foto (Opsional)',
                'tipe_kolom' => 'text',
                'wajib_diisi' => false,
                'placeholder' => 'Instagram/Website/Google Drive',
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

        // 6. Buat 119 akun peserta dengan nama Indonesia
        $namaPeserta = [
            'Rizki Maulana Akbar', 'Siti Nurhaliza Rahman', 'Agus Prasetyo Wijaya', 'Nur Hidayah Putri', 'Fahmi Ahmad Fauzi',
            'Lina Marlina Dewi', 'Dedi Kurniawan Adi', 'Anggun Pramudita Sari', 'Bayu Setiawan Kusuma', 'Ratna Sari Maharani',
            'Hendro Wibowo Santoso', 'Dwi Lestari Amelia', 'Irfan Hakim Putra', 'Maya Sari Indah', 'Rudi Hartono Wijaya',
            'Eka Putri Rahmawati', 'Toni Gunawan Adi', 'Fitri Handayani Dewi', 'Arif Budiman Putra', 'Sri Wahyuni Lestari',
            'Dimas Prasetya Kusuma', 'Yuni Kartika Sari', 'Hendra Setiawan Adi', 'Nina Safitri Putri', 'Bambang Supriyanto',
            'Ani Widiastuti Dewi', 'Joko Purnomo Santoso', 'Rina Susanti Sari', 'Andi Wijaya Putra', 'Wulan Dari Maharani',
            'Faisal Rahman Hakim', 'Dina Mariana Putri', 'Yoga Aditya Pratama', 'Sinta Dewi Anggraini', 'Hadi Susilo Adi',
            'Endang Sulistyawati', 'Ricky Saputra Wijaya', 'Nurul Fadilah Sari', 'Danu Pratama Kusuma', 'Lilis Suryani Dewi',
            'Fikri Akbar Rahman', 'Dewi Kusuma Wardani', 'Haris Setiawan Putra', 'Indah Permata Sari', 'Ridwan Kamil Adi',
            'Putri Amalia Safitri', 'Galih Prasetyo Wijaya', 'Laila Sari Maharani', 'Hari Wibowo Santoso', 'Sari Rahayu Dewi',
            'Teguh Santoso Adi', 'Nia Ramadhani Putri', 'Ade Kurniawan Putra', 'Mega Wati Lestari', 'Ardi Wijaya Kusuma',
            'Lina Wati Safitri', 'Reza Pahlevi Rahman', 'Yanti Suryani Dewi', 'Vino Bastian Adi', 'Winda Hamidah Sari',
            'Zaki Maulana Hakim', 'Diah Ayu Lestari', 'Imam Santoso Putra', 'Riska Amelia Dewi', 'Nanda Prasetya Adi',
            'Tiara Andini Putri', 'Gilang Ramadhan Wijaya', 'Bella Safitri Maharani', 'Candra Kusuma Adi', 'Elsa Purnama Sari',
            'Danang Wijaya Putra', 'Farah Diba Lestari', 'Guntur Pratama Santoso', 'Hasna Zahira Dewi', 'Ilham Maulana Adi',
            'Jasmine Putri Safitri', 'Kevin Prasetyo Wijaya', 'Latifa Maharani Sari', 'Muhammad Iqbal Hakim', 'Nabila Kusuma Putri',
            'Oscar Ramadhan Adi', 'Pradita Amelia Dewi', 'Qonita Azzahra Sari', 'Rahmat Hidayat Putra', 'Salma Nurjanah Lestari',
            'Taufik Hidayat Wijaya', 'Ulfa Rahmawati Dewi', 'Vicky Prasetya Adi', 'Wahyu Nugroho Santoso', 'Yudha Pratama Kusuma',
            'Zahra Maulida Putri', 'Adrian Wijaya Putra', 'Bunga Citra Dewi', 'Cahya Ramadhan Adi', 'Diana Puspita Sari',
            'Eko Prasetyo Wijaya', 'Febri Lestari Putri', 'Gita Savitri Maharani', 'Heri Setiawan Adi', 'Intan Permata Dewi',
            'Jihan Aulia Safitri', 'Kurnia Sari Lestari', 'Leo Saputra Adi', 'Melati Putri Maharani', 'Naufal Rahman Wijaya',
            'Okta Prasetya Putra', 'Pandu Wijaya Santoso', 'Rahma Wati Dewi', 'Surya Pratama Kusuma', 'Tika Andini Sari',
            'Udin Setiawan Adi', 'Vera Anggraini Putri', 'Wawan Kurniawan Wijaya', 'Yanto Basuki Adi', 'Zakiyah Nurjanah Dewi',
            'Ari Wibowo Putra', 'Bayu Aji Santoso', 'Cindy Paramita Lestari', 'Doni Saputra Adi',
        ];

        $temaFoto = [
            'Budaya Tradisional Banjar', 'Pasar Terapung Lok Baintan', 'Kuliner Khas Banjarmasin',
            'Wisata Sungai Martapura', 'Kehidupan Sehari-hari Masyarakat', 'Arsitektur Rumah Banjar',
            'Festival Budaya Lokal', 'Masjid Sultan Suriansyah', 'Kerajinan Tangan Sasirangan',
            'Sunset di Sungai Barito', 'Aktivitas Nelayan', 'Jembatan Barito', 'Taman Siring',
            'Warung Kopi Tradisional', 'Pedagang Keliling', 'Upacara Adat Banjar',
        ];

        $jenisKamera = [
            'Canon EOS 80D', 'Nikon D5600', 'Sony A7 III', 'Fujifilm X-T30', 'Canon EOS M50',
            'iPhone 14 Pro Max', 'Samsung Galaxy S23 Ultra', 'Xiaomi 13 Pro', 'Canon EOS R6',
            'Nikon Z6 II', 'Sony A6400', 'iPhone 13 Pro', 'OPPO Find X5 Pro', 'Vivo X90 Pro',
        ];

        $kota = ['Banjarmasin Utara', 'Banjarmasin Selatan', 'Banjarmasin Tengah', 'Banjarmasin Timur', 'Banjarmasin Barat'];
        $jalan = ['Lambung Mangkurat', 'A. Yani', 'Veteran', 'Sultan Adam', 'S. Parman', 'Gatot Soebroto', 'Pramuka', 'Pangeran Samudera', 'Bhayangkara', 'Belitung Darat'];

        for ($i = 0; $i < 119; $i++) {
            // Generate email unik
            $nama = $namaPeserta[$i];
            $emailName = strtolower(str_replace(' ', '.', $nama));
            $email = $emailName . '.foto' . ($i + 1) . '@gmail.com';

            // Buat akun peserta
            $peserta = User::create([
                'name' => $nama,
                'email' => $email,
                'password' => Hash::make('password123'),
                'peran' => 'peserta',
                'email_verified_at' => now(),
            ]);

            // Sistem tanpa seleksi = otomatis diterima
            $status = 'diterima';

            // Buat pendaftaran
            $pendaftaran = Pendaftaran::create([
                'id_acara' => $acara->id,
                'id_pengguna' => $peserta->id,
                'status' => $status,
            ]);

            // Isi data pendaftaran
            $nomorTelepon = '08' . str_pad(rand(1000000000, 9999999999), 10, '0', STR_PAD_LEFT);
            
            // Buat file dummy KTP
            $namaFileKTP = 'ktp_' . strtolower(str_replace(' ', '_', $nama)) . '.jpg';
            $pathKTP = storage_path('app/public/' . $namaFileKTP);
            
            // Pastikan folder ada
            if (!file_exists(storage_path('app/public'))) {
                mkdir(storage_path('app/public'), 0755, true);
            }
            
            // Buat file JPG dummy dengan format valid (minimal JPEG structure)
            // JPEG signature + minimal structure
            $jpegHeader = "\xFF\xD8\xFF\xE0\x00\x10JFIF\x00\x01\x01\x00\x00\x01\x00\x01\x00\x00";
            $jpegData = "\xFF\xDB\x00\x43\x00\x08\x06\x06\x07\x06\x05\x08\x07\x07\x07\x09\x09\x08\x0A\x0C\x14\x0D\x0C\x0B\x0B\x0C\x19\x12\x13\x0F\x14\x1D\x1A\x1F\x1E\x1D\x1A\x1C\x1C\x20\x24\x2E\x27\x20\x22\x2C\x23\x1C\x1C\x28\x37\x29\x2C\x30\x31\x34\x34\x34\x1F\x27\x39\x3D\x38\x32\x3C\x2E\x33\x34\x32";
            $jpegSOF = "\xFF\xC0\x00\x0B\x08\x00\x64\x00\x64\x01\x01\x11\x00";
            $jpegSOS = "\xFF\xDA\x00\x08\x01\x01\x00\x00\x3F\x00";
            $jpegEnd = "\xFF\xD9";
            
            $imgContent = $jpegHeader . $jpegData . $jpegSOF . $jpegSOS . str_repeat("\x00", 100) . $jpegEnd;
            file_put_contents($pathKTP, $imgContent);
            
            $portofolio = ($i % 3 == 0) ? 'https://instagram.com/' . strtolower(str_replace(' ', '', $nama)) : '';
            
            $dataFormulir = [
                'nama_lengkap' => $nama,
                'nomor_telepon' => $nomorTelepon,
                'email' => $email,
                'alamat' => 'Jl. ' . $jalan[rand(0, count($jalan) - 1)] . ' No. ' . rand(1, 150) . ', Kec. ' . $kota[rand(0, count($kota) - 1)] . ', Banjarmasin',
                'jenis_kamera' => $jenisKamera[rand(0, count($jenisKamera) - 1)],
                'tema_foto' => $temaFoto[rand(0, count($temaFoto) - 1)],
                'portofolio' => $portofolio,
                'ktp' => $namaFileKTP,
            ];

            foreach ($dataFormulir as $namaKolom => $nilai) {
                DataPendaftaran::create([
                    'id_pendaftaran' => $pendaftaran->id,
                    'nama_kolom' => $namaKolom,
                    'nilai_kolom' => $nilai,
                ]);
            }
        }

        $this->command->info('✅ Data Lomba Fotografi berhasil dibuat!');
        $this->command->info('📋 Acara: ' . $acara->judul);
        $this->command->info('🏢 Dinas: ' . $dinas->name);
        $this->command->info('👥 Peserta: 119 orang (SEMUA DITERIMA - Tanpa Seleksi)');
        $this->command->info('👨‍💼 Panitia: ' . $panitiaUser->name . ' (' . $panitiaUser->email . ')');
        $this->command->info('📝 Form: 8 kolom termasuk upload KTP');
        $this->command->info('📊 Kuota: 120 peserta (sisa 1 slot)');
        $this->command->info('📸 Sistem: Tanpa Seleksi (Auto Diterima)');
        $this->command->info('');
        $this->command->info('🔐 Semua akun peserta bisa login dengan password: password123');
    }
}
