<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Status Pendaftaran</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Status Pendaftaran" name="keywords">
    <meta content="Status Pendaftaran" name="description">
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
        <!-- Back Button Section -->
        <div class="container-fluid py-3 ">
            <div class="container-fluid px-4">
                <a href="{{ route('acara') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar Acara
                </a>
            </div>
        </div>

        <div class="container-fluid py-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <!-- Status Pendaftaran Card -->
                        <div class="card shadow border-0 rounded-lg">
                            <div class="card-header bg-primary text-white">
                                <h4 class="mb-0 text-white"><i class="fas fa-clipboard-check mr-2 text-white"></i>Status Pendaftaran</h4>
                            </div>
                            <div class="card-body p-4">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="text-uppercase font-weight-bold text-muted mb-3">STATUS SAAT INI</h6>
                                        @if($pendaftaran->status == 'pending')
                                            <div class="alert alert-warning mb-0">
                                                <i class="fas fa-clock mr-2"></i>
                                                <strong>PENDING</strong> - Sedang Diproses
                                                <p class="mt-2 mb-0 small">
                                                    Pendaftaran Anda sedang dalam proses review oleh panitia. Mohon tunggu pengumuman selanjutnya.
                                                </p>
                                            </div>
                                        @elseif($pendaftaran->status == 'disetujui')
                                            <div class="alert alert-success mb-0">
                                                <i class="fas fa-check-circle mr-2"></i>
                                                <strong>DITERIMA</strong> - Selamat!
                                                <p class="mt-2 mb-0 small">
                                                    Pendaftaran Anda telah diterima. Silakan persiapkan diri untuk mengikuti acara ini.
                                                </p>
                                            </div>
                                        @elseif($pendaftaran->status == 'ditolak')
                                            <div class="alert alert-danger mb-0">
                                                <i class="fas fa-times-circle mr-2"></i>
                                                <strong>DITOLAK</strong>
                                                <p class="mt-2 mb-0 small">
                                                    Maaf, pendaftaran Anda tidak dapat diterima.
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <h6 class="text-uppercase font-weight-bold text-muted mb-3">INFORMASI PENDAFTARAN</h6>
                                        <div class="mb-3">
                                            <small class="text-muted">Tanggal Daftar:</small><br>
                                            <strong>{{ \Carbon\Carbon::parse($pendaftaran->created_at)->locale('id')->isoFormat('dddd, D MMMM Y HH:mm') }}</strong>
                                        </div>
                                        <div class="mb-0">
                                            <small class="text-muted">Sistem Pendaftaran:</small><br>
                                            <span class="badge badge-info">{{ $acara->sistem_pendaftaran }}</span>
                                        </div>
                                    </div>
                                </div>

                                @if($pendaftaran->status == 'ditolak' && $pendaftaran->alasan_penolakan)
                                <div class="mt-4">
                                    <h6 class="text-uppercase font-weight-bold text-muted mb-2">Alasan Penolakan</h6>
                                    <div class="alert alert-light border-left-danger">
                                        {{ $pendaftaran->alasan_penolakan }}
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