<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Acara;
use App\Models\Dinas;

class AcaraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dinas = Dinas::first();
        
        if (!$dinas) {
            $this->command->error('Tidak ada data dinas. Jalankan DinasSeeder terlebih dahulu.');
            return;
        }

        $events = [
            [
                'id_dinas' => $dinas->id,
                'judul' => 'Lomba Lari Marathon Banjarmasin 2025',
                'tanggal_acara' => '2025-11-15',
                'lokasi' => 'Lapangan Murjani, Banjarmasin',
                'biaya' => 'Rp 50.000',
                'kategori' => 'Lomba',
                'sistem_pendaftaran' => 'Tanpa Seleksi',
                'kuota' => 500,
                'kategori_acara' => 'Olahraga',
                'persyaratan' => "1. Warga Negara Indonesia\n2. Usia 17-50 tahun\n3. Sehat jasmani dan rohani\n4. Melampirkan surat keterangan sehat dari dokter\n5. Mengisi formulir pendaftaran lengkap",
                'tanggal_mulai_daftar' => '2025-10-15',
                'tanggal_akhir_daftar' => '2025-11-10',
                'hadiah' => "Juara 1: Rp 5.000.000 + Medali Emas + Sertifikat\nJuara 2: Rp 3.000.000 + Medali Perak + Sertifikat\nJuara 3: Rp 2.000.000 + Medali Perunggu + Sertifikat\nSemua peserta: Medali Finisher + Kaos",
                'tentang' => 'Lomba lari marathon tahunan yang diselenggarakan Pemkot Banjarmasin untuk memperingati HUT Kota dan meningkatkan minat masyarakat terhadap olahraga lari. Rute lomba melalui jalan-jalan utama kota Banjarmasin sepanjang 42.195 km.',
                'gambar' => 'marathon.jpg',
                'status' => 'active',
            ],
            [
                'id_dinas' => $dinas->id,
                'judul' => 'Festival Budaya Banjar 2025',
                'tanggal_acara' => '2025-12-01',
                'lokasi' => 'Taman Siring, Banjarmasin',
                'biaya' => 'Gratis',
                'kategori' => 'Event',
                'sistem_pendaftaran' => 'Tanpa Seleksi',
                'kuota' => 1000,
                'kategori_acara' => 'Kesenian & Budaya',
                'persyaratan' => "1. Terbuka untuk umum\n2. Pendaftaran untuk vendor/peserta pameran\n3. Menyertakan proposal kegiatan (untuk peserta pameran)\n4. Mengikuti protokol kesehatan",
                'tanggal_mulai_daftar' => '2025-10-20',
                'tanggal_akhir_daftar' => '2025-11-25',
                'hadiah' => "Sertifikat Partisipasi\nSouvenir Khas Banjar\nDokumentasi Kegiatan\nNetworking dengan Seniman Lokal",
                'tentang' => 'Festival budaya untuk melestarikan dan memperkenalkan kekayaan budaya Banjar kepada masyarakat luas. Acara ini menampilkan berbagai pertunjukan seni tradisional, pameran kerajinan, dan kuliner khas Banjar.',
                'gambar' => 'festival-budaya.jpg',
                'status' => 'active',
            ],
            [
                'id_dinas' => $dinas->id,
                'judul' => 'Lomba Inovasi Teknologi Smart City',
                'tanggal_acara' => '2025-11-30',
                'lokasi' => 'Gedung Dewan, Banjarmasin',
                'biaya' => 'Gratis',
                'kategori' => 'Lomba',
                'sistem_pendaftaran' => 'Seleksi',
                'kuota' => 50,
                'kategori_acara' => 'Teknologi',
                'persyaratan' => "1. Mahasiswa atau fresh graduate (maksimal 2 tahun lulus)\n2. Tim maksimal 4 orang\n3. Proposal inovasi teknologi untuk smart city\n4. Prototipe atau mock-up solusi\n5. Presentasi dalam bahasa Indonesia",
                'tanggal_mulai_daftar' => '2025-10-01',
                'tanggal_akhir_daftar' => '2025-11-15',
                'hadiah' => "Juara 1: Rp 15.000.000 + Inkubasi Bisnis\nJuara 2: Rp 10.000.000 + Mentoring\nJuara 3: Rp 7.500.000 + Sertifikat\nJuara Harapan 1: Rp 5.000.000\nJuara Harapan 2: Rp 3.000.000",
                'tentang' => 'Kompetisi inovasi teknologi untuk mengembangkan solusi smart city yang dapat diterapkan di Kota Banjarmasin. Peserta diharapkan mengajukan ide-ide kreatif untuk meningkatkan kualitas hidup masyarakat melalui teknologi.',
                'gambar' => 'tech-innovation.jpg',
                'status' => 'active',
            ],
            [
                'id_dinas' => $dinas->id,
                'judul' => 'Workshop Entrepreneurship untuk UMKM',
                'tanggal_acara' => '2025-11-20',
                'lokasi' => 'Hotel Aston Banjarmasin',
                'biaya' => 'Rp 100.000',
                'kategori' => 'Event',
                'sistem_pendaftaran' => 'Tanpa Seleksi',
                'kuota' => 200,
                'kategori_acara' => 'Ekonomi',
                'persyaratan' => "1. Pelaku UMKM atau calon entrepreneur\n2. Mengisi formulir pendaftaran\n3. Membayar biaya pendaftaran\n4. Membawa alat tulis dan laptop (jika ada)",
                'tanggal_mulai_daftar' => '2025-10-10',
                'tanggal_akhir_daftar' => '2025-11-15',
                'hadiah' => "Sertifikat Workshop\nMateri Digital\nNetworking Session\nKonsultasi Bisnis Gratis (3 bulan)\nDoorprize Menarik",
                'tentang' => 'Workshop pengembangan keterampilan entrepreneurship untuk UMKM di Banjarmasin. Materi meliputi business planning, digital marketing, financial management, dan strategi pengembangan usaha.',
                'gambar' => 'workshop-umkm.jpg',
                'status' => 'active',
            ],
            [
                'id_dinas' => $dinas->id,
                'judul' => 'Lomba Fotografi Keindahan Banjarmasin',
                'tanggal_acara' => '2025-12-15',
                'lokasi' => 'Various Locations in Banjarmasin',
                'biaya' => 'Rp 25.000',
                'kategori' => 'Lomba',
                'sistem_pendaftaran' => 'Tanpa Seleksi',
                'kuota' => 300,
                'kategori_acara' => 'Kesenian & Budaya',
                'persyaratan' => "1. Foto diambil di wilayah Kota Banjarmasin\n2. Foto original (bukan hasil edit berlebihan)\n3. Resolusi minimal 300 DPI\n4. Format JPEG/JPG\n5. Maksimal 3 foto per peserta\n6. Belum pernah memenangkan kontes serupa",
                'tanggal_mulai_daftar' => '2025-10-01',
                'tanggal_akhir_daftar' => '2025-12-10',
                'hadiah' => "Juara 1: Kamera DSLR + Rp 2.000.000\nJuara 2: Kamera Mirrorless + Rp 1.500.000\nJuara 3: Action Camera + Rp 1.000.000\nJuara Favorit: Tripod + Rp 500.000\n10 Foto Terbaik: Piagam + Rp 200.000",
                'tentang' => 'Lomba fotografi untuk mengabadikan keindahan Kota Banjarmasin dari berbagai sudut pandang. Tema bebas dengan fokus pada keunikan budaya, arsitektur, alam, dan kehidupan masyarakat Banjarmasin.',
                'gambar' => 'photo-contest.jpg',
                'status' => 'active',
            ]
        ];

        foreach ($events as $event) {
            $acara = Acara::create($event);
            
            // Tambahkan form fields untuk setiap acara
            $this->createFormFields($acara);
        }

        $this->command->info('Data acara berhasil dibuat!');
    }

    private function createFormFields($acara)
    {
        $commonFields = [
            [
                'nama_kolom' => 'nama_lengkap',
                'label_kolom' => 'Nama Lengkap',
                'tipe_kolom' => 'text',
                'wajib_diisi' => true,
                'placeholder' => 'Masukkan nama lengkap Anda',
                'urutan_kolom' => 1
            ],
            [
                'nama_kolom' => 'nik',
                'label_kolom' => 'Nomor Induk Kependudukan (NIK)',
                'tipe_kolom' => 'number',
                'wajib_diisi' => true,
                'placeholder' => 'Masukkan 16 digit NIK Anda',
                'urutan_kolom' => 2
            ],
            [
                'nama_kolom' => 'no_hp',
                'label_kolom' => 'Nomor HP/WhatsApp',
                'tipe_kolom' => 'number',
                'wajib_diisi' => true,
                'placeholder' => 'Contoh: 081234567890',
                'urutan_kolom' => 3
            ],
            [
                'nama_kolom' => 'alamat',
                'label_kolom' => 'Alamat Lengkap',
                'tipe_kolom' => 'textarea',
                'wajib_diisi' => true,
                'placeholder' => 'Masukkan alamat lengkap Anda',
                'urutan_kolom' => 4
            ],
            [
                'nama_kolom' => 'pekerjaan',
                'label_kolom' => 'Pekerjaan',
                'tipe_kolom' => 'text',
                'wajib_diisi' => true,
                'placeholder' => 'Masukkan pekerjaan Anda',
                'urutan_kolom' => 5
            ],
            [
                'nama_kolom' => 'foto_ktp',
                'label_kolom' => 'Foto KTP',
                'tipe_kolom' => 'file',
                'wajib_diisi' => true,
                'placeholder' => 'Upload foto KTP (JPG/PNG, maksimal 2MB)',
                'urutan_kolom' => 6
            ]
        ];

        // Tambahan field khusus berdasarkan jenis acara
        if ($acara->kategori == 'Lomba') {
            $commonFields[] = [
                'nama_kolom' => 'pengalaman',
                'label_kolom' => 'Pengalaman yang Relevan',
                'tipe_kolom' => 'textarea',
                'wajib_diisi' => false,
                'placeholder' => 'Ceritakan pengalaman Anda yang relevan dengan lomba ini',
                'urutan_kolom' => 7
            ];

            if (strpos(strtolower($acara->judul), 'foto') !== false) {
                $commonFields[] = [
                    'nama_kolom' => 'karya_foto',
                    'label_kolom' => 'Upload Karya Foto',
                    'tipe_kolom' => 'file',
                    'wajib_diisi' => true,
                    'placeholder' => 'Upload karya foto Anda (JPG/PNG, maksimal 2MB)',
                    'urutan_kolom' => 8
                ];
            }
        }

        foreach ($commonFields as $field) {
            $acara->kolomFormulir()->create($field);
        }
    }
}
