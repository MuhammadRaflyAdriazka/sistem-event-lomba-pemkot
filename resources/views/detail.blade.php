<!-- filepath: c:\laragon\www\web-event-lomba\resources\views\detail.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Detail Acara</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Acara Banjarmasin" name="keywords">
    <link rel="icon" href="{{ asset('image/LOGO-PEMKOT-BARU.png') }}" type="image/png">


    <!-- Favicon -->
    <link href="{{ asset('templatepeserta/img/favicon.ico') }}" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet"> 

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('templatepeserta/lib/owlcarousel/assets/owl.carousel.min.css')}}" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('templatepeserta/css/style.css') }}" rel="stylesheet">
</head>

<body class="d-flex flex-column min-vh-100">
    <!-- Content wrapper -->
    <div class="flex-grow-1">
        <!-- Hero Image Section -->
        @if(isset($acara))
        <div class="position-relative" style="margin-bottom: 90px;">
            <a href="{{ auth()->check() ? route('dashboard') : url('/') }}" class="btn btn-secondary px-3 py-2" style="position: absolute; top: 20px; left: 20px; font-size: 14px; z-index: 10;">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
            <img src="{{ asset('images/events/' . $acara->gambar) }}" 
                 alt="{{ $acara->judul }}" 
                 class="w-100" 
                 style="height: auto; display: block;"
                 onerror="this.src='{{ asset('templatepeserta/img/eror.jpeg') }}'">
        </div>
        @else
        <div class="position-relative" style="margin-bottom: 90px;">
            <a href="{{ auth()->check() ? route('dashboard') : url('/') }}" class="btn btn-secondary px-3 py-2" style="position: absolute; top: 20px; left: 20px; font-size: 14px; z-index: 10;">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
            <img src="{{ asset('templatepeserta/img/eror.jpeg') }}" 
                 alt="Event Image" 
                 class="w-100" 
                 style="height: auto; display: block;">
        </div>
        @endif

        <!-- Button Section -->
        <div class="container-fluid" style="margin-top: -50px;">
            <div class="container">
                <div class="text-center mb-4">
                    <button class="btn btn-primary px-4 py-2" style="font-size: 16px; font-weight: bold; text-transform: uppercase;">
                        DESKRIPSI
                    </button>
                </div>
            </div>
        </div>

        <!-- Content Section -->
        <div class="container-fluid py-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="card shadow-lg border-0 rounded-lg">
                            <div class="card-body p-5">
                                
                                @if(isset($acara))
                                <!-- DATA DARI DATABASE -->
                                <h1 class="text-primary text-uppercase mb-4 text-center" style="font-size: 18px; font-weight: bold; letter-spacing: 0.5px;">
                                    {{ strtoupper($acara->judul) }}
                                </h1>

                                <!-- Field sesuai dengan migration -->
                                <h5 class="mt-4 text-uppercase" style="color: #333; font-size: 14px; font-weight: bold;">KATEGORI</h5>
                                <p style="font-size: 14px; color: #333; padding-left: 15px;">• {{ $acara->kategori_acara }}</p>

                                <h5 class="mt-4 text-uppercase" style="color: #333; font-size: 14px; font-weight: bold;">SISTEM PENDAFTARAN</h5>
                                <p style="font-size: 14px; color: #333; padding-left: 15px;">• {{ $acara->sistem_pendaftaran }}</p>

                                <h5 class="mt-4 text-uppercase" style="color: #333; font-size: 14px; font-weight: bold;">KUOTA PESERTA</h5>
                                <p style="font-size: 14px; color: #333; padding-left: 15px;">• {{ number_format($acara->kuota) }} Orang</p>                                <h5 class="mt-4 text-uppercase" style="color: #333; font-size: 14px; font-weight: bold;">BIAYA PENDAFTARAN</h5>
                                <p style="font-size: 14px; color: #333; padding-left: 15px;">• {{ $acara->biaya }}</p>
                                
                                <h5 class="mt-4 text-uppercase" style="color: #333; font-size: 14px; font-weight: bold;">SYARAT PENDAFTARAN</h5>
                                <div style="font-size: 14px; color: #333; white-space: pre-line; padding-left: 15px;">{{ $acara->persyaratan }}</div>
                                
                                <h5 class="mt-4 text-uppercase" style="color: #333; font-size: 14px; font-weight: bold;">Tanggal Pendaftaran</h5>
                                <p style="font-size: 14px; color: #333; padding-left: 15px;">• {{ \Carbon\Carbon::parse($acara->tanggal_mulai_daftar)->locale('id')->isoFormat('D MMMM') }} - {{ \Carbon\Carbon::parse($acara->tanggal_akhir_daftar)->locale('id')->isoFormat('D MMMM Y') }}</p>
                                
                                <h5 class="mt-4 text-uppercase" style="color: #333; font-size: 14px; font-weight: bold;">Tanggal Pelaksanaan</h5>
                                <p style="font-size: 14px; color: #333; padding-left: 15px;">• {{ \Carbon\Carbon::parse($acara->tanggal_acara)->locale('id')->isoFormat('D MMMM Y') }}</p>
                                
                                <h5 class="mt-4 text-uppercase" style="color: #333; font-size: 14px; font-weight: bold;">Lokasi</h5>
                                <p style="font-size: 14px; color: #333; padding-left: 15px;">• {{ $acara->lokasi }}</p>
                                
                                <h5 class="mt-4 text-uppercase" style="color: #333; font-size: 14px; font-weight: bold;">Hadiah</h5>
                                <div style="font-size: 14px; color: #333; white-space: pre-line; padding-left: 15px;">{{ $acara->hadiah }}</div>
                                
                                <h5 class="mt-4 text-uppercase" style="color: #333; font-size: 14px; font-weight: bold;">Tentang Acara</h5>
                                <div style="font-size: 14px; color: #333; white-space: pre-line; padding-left: 15px;">{{ $acara->tentang }}</div>

                                <div class="text-center mt-4">
                                    @php
                                        $registrationOpen = now()->between($acara->tanggal_mulai_daftar, $acara->tanggal_akhir_daftar);
                                        $pendaftaranUser = auth()->check() ? \App\Models\Pendaftaran::where('id_acara', $acara->id)->where('id_pengguna', auth()->id())->first() : null;
                                        $hasRegistered = $pendaftaranUser !== null;
                                    @endphp
                                    
                                    @if(!$registrationOpen)
                                        <!-- Periode registrasi sudah tutup -->
                                        <div class="alert alert-danger mb-4">
                                            <h5><i class="fas fa-times-circle mr-2"></i>Pendaftaran Ditutup!</h5>
                                            <p class="mb-0">
                                                Periode pendaftaran sudah berakhir pada {{ \Carbon\Carbon::parse($acara->tanggal_akhir_daftar)->locale('id')->isoFormat('D MMMM Y') }}
                                            </p>
                                        </div>
                                        <button class="btn btn-secondary btn-lg" disabled>
                                            <i class="fas fa-lock mr-2"></i>Pendaftaran Ditutup
                                        </button>
                                    @elseif($acara->sistem_pendaftaran === 'Tanpa Seleksi' && $acara->is_kuota_penuh && !$hasRegistered)
                                        <!-- Kuota penuh untuk sistem tanpa seleksi - hanya tampil jika user belum terdaftar -->
                                        <div class="alert alert-warning mb-4">
                                            <h5><i class="fas fa-users mr-2"></i>Kuota Sudah Penuh!</h5>
                                            <p class="mb-0">
                                                Maaf, semua slot peserta sudah terisi penuh.
                                                <br>Jika ada peserta yang mengundurkan diri, slot akan terbuka kembali secara otomatis.
                                            </p>
                                        </div>
                                    @else
                                        @auth
                                            <!-- User sudah login -->
                                            @if($hasRegistered)
                                                <!-- User sudah terdaftar -->
                                                @if($pendaftaranUser->status == 'mengundurkan_diri')
                                                    <!-- Status mengundurkan diri -->
                                                    <div class="alert alert-secondary mb-4">
                                                        <h5><i class="fas fa-sign-out-alt mr-2"></i>Anda Telah Mengundurkan Diri</h5>
                                                        <p class="mb-0">Anda telah mengundurkan diri dari acara ini dan tidak dapat mendaftar lagi.</p>
                                                    </div>
                                                @elseif($pendaftaranUser->status == 'ditolak')
                                                    <!-- Status ditolak -->
                                                    <div class="alert alert-danger mb-4">
                                                        <h5><i class="fas fa-times-circle mr-2"></i>Pendaftaran Anda Ditolak</h5>
                                                        <p class="mb-0">
                                                            Maaf, pendaftaran Anda untuk acara ini tidak dapat diterima.
                                                            @if($pendaftaranUser->alasan_penolakan)
                                                                <br><strong>Alasan:</strong> {{ $pendaftaranUser->alasan_penolakan }}
                                                            @endif
                                                        </p>
                                                    </div>
                                                @elseif($pendaftaranUser->status == 'pending')
                                                    <!-- Status pending -->
                                                    <div class="alert alert-warning mb-4">
                                                        <h5><i class="fas fa-clock mr-2"></i>Menunggu Persetujuan</h5>
                                                        <p class="mb-0">Pendaftaran Anda sedang dalam proses review. Mohon tunggu pengumuman hasil seleksi.</p>
                                                    </div>
                                                @else
                                                    <!-- Status disetujui/diterima -->
                                                    <div class="alert alert-success mb-4">
                                                        <h5><i class="fas fa-check-circle mr-2"></i>Anda Sudah Terdaftar!</h5>
                                                        <p class="mb-0">Anda sudah terdaftar pada acara ini. Terima kasih atas partisipasinya!</p>
                                                    </div>
                                                @endif
                                            @else
                                                <!-- User belum terdaftar - show slot info for tanpa seleksi -->
                                                @if($acara->sistem_pendaftaran === 'Tanpa Seleksi')
                                                    <div class="alert alert-info mb-4">
                                                        <h5><i class="fas fa-info-circle mr-2"></i>Sistem Tanpa Seleksi</h5>
                                                        <p class="mb-0">
                                                            Pendaftaran akan <strong>langsung diterima</strong> selama kuota masih tersedia.
                                                            <br>
                                                            <strong>Tersisa {{ $acara->sisa_kuota }} slot tersedia.</strong>
                                                        </p>
                                                    </div>
                                                @endif
                                                
                                                <a href="/pendaftaran/{{ $acara->id }}" class="btn btn-primary btn-lg">
                                                    <i class="fas fa-user-plus mr-2"></i>
                                                    @if($acara->sistem_pendaftaran === 'Tanpa Seleksi')
                                                        Daftar & Langsung Diterima
                                                    @else
                                                        Daftar Sekarang
                                                    @endif
                                                </a>
                                            @endif
                                        @else
                                            <!-- User belum punya akun -->
                                            <div class="alert alert-info mb-4">
                                                <h5><i class="fas fa-info-circle mr-2"></i>Ingin Ikut Acara Ini?</h5>
                                                <p class="mb-0">
                                                    Buat akun terlebih dahulu untuk bisa mendaftar pada acara ini
                                                    @if($acara->sistem_pendaftaran === 'Tanpa Seleksi')
                                                        <br><strong>Tersisa {{ $acara->sisa_kuota }} slot tersedia</strong> (Sistem Tanpa Seleksi)
                                                    @endif
                                                </p>
                                            </div>
                                            <a href="{{ route('register') }}" class="btn btn-success btn-lg mr-3">
                                                <i class="fas fa-user-plus mr-2"></i>Buat Akun Sekarang
                                            </a>
                                            <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg">
                                                <i class="fas fa-sign-in-alt mr-2"></i>Sudah Punya Akun? Masuk
                                            </a>
                                        @endauth
                                    @endif
                                </div>
                                
                                @else
                                <!-- Pesan jika acara tidak ditemukan -->
                                <div class="text-center">
                                    <div class="alert alert-warning">
                                        <h4><i class="fas fa-exclamation-triangle mr-2"></i>Acara Tidak Ditemukan</h4>
                                        <p>Acara yang Anda cari tidak tersedia atau sudah tidak aktif.</p>
                                        <a href="/" class="btn btn-primary">Kembali ke Beranda</a>
                                    </div>
                                </div>
                                @endif
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Start -->
    @include('layouts.peserta.footer')
    <!-- Footer End -->

    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary rounded-0 btn-lg-square back-to-top"><i class="fa fa-angle-double-up"></i></a>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('templatepeserta/lib/easing/easing.min.js')}}"></script>
    <script src="{{ asset('templatepeserta/lib/waypoints/waypoints.min.js')}}"></script>
    <script src="{{ asset('templatepeserta/lib/counterup/counterup.min.js')}}"></script>
    <script src="{{ asset('templatepeserta/lib/owlcarousel/owl.carousel.min.js')}}"></script>

    <!-- Template Javascript -->
    <script src="{{ asset('templatepeserta/js/main.js')}}"></script>
</body>

</html>