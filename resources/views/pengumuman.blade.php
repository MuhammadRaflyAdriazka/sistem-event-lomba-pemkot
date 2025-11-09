<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Pengumuman - {{ $pendaftaran->acara->judul }}</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link rel="icon" href="{{ asset('image/LOGO-PEMKOT-BARU.png') }}" type="image/png">

    <link href="{{ asset('templatepeserta/img/favicon.ico') }}" rel="icon">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet"> 

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <link href="{{ asset('templatepeserta/lib/owlcarousel/assets/owl.carousel.min.css')}}" rel="stylesheet">

    <link href="{{ asset('templatepeserta/css/style.css') }}" rel="stylesheet">
    
    <style>
        .pengumuman-card {
            border-radius: 10px;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        /* [MODIFIKASI] CSS untuk tombol kembali di pojok kiri atas */
        .btn-back-corner {
            position: absolute; /* Menggunakan absolute agar relatif ke body/container */
            top: 20px;
            left: 20px;
            z-index: 10; /* Pastikan di atas konten lain */
        }
    </style>

    @include('layouts.peserta.topbar')
</head>

<body class="d-flex flex-column min-vh-100" style="position: relative;"> {{-- [MODIFIKASI] Tambahkan position: relative jika btn-back-corner menggunakan absolute --}}
    
    <a href="{{ route('acara') }}" class="btn btn-primary btn-back-corner">
        <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar Acara
    </a>

    <div class="flex-grow-1">
        <div class="container-fluid py-5">
            <div class="container">
                
                {{-- Tombol kembali yang lama dihapus dari sini --}}
                {{-- <div class="mb-4"> ... </div> --}}

                <div class="row justify-content-center" style="margin-top: 50px;"> {{-- [MODIFIKASI] Tambahkan margin-top agar tidak tertutup tombol kembali --}}
                    <div class="col-lg-8">
                        @if($pengumumanList->count() > 0)
                            @foreach($pengumumanList as $pengumuman)
                                <div class="card border-0" style="box-shadow: 0 0 30px rgba(0, 0, 0, 0.1); margin-bottom: 30px;">
                                    <div class="card-header text-white d-flex align-items-center" style="background: linear-gradient(135deg, #4e73df 0%, #224abe 100%); border-radius: 10px 10px 0 0;">
                                        <i class="fas fa-bullhorn mr-2"></i>
                                        <span class="fw-bold">Pengumuman</span>
                                    </div>
                                    
                                    <div class="card-body p-4" style="background-color: #ffffff;">
                                        <div style="line-height: 1.8; font-size: 16px; color: #333; white-space: pre-wrap;">{{ $pengumuman->isi }}</div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="card border-0" style="box-shadow: 0 0 30px rgba(0, 0, 0, 0.1);">
                                <div class="card-header text-white d-flex align-items-center" style="background: linear-gradient(135deg, #4e73df 0%, #224abe 100%); border-radius: 10px 10px 0 0;">
                                    <i class="fas fa-bullhorn me-2"></i>
                                    <span class="fw-bold">Pengumuman</span>
                                </div>
                                
                                <div class="card-body text-center py-5" style="background-color: #ffffff;">
                                    <div class="mb-4">
                                        <i class="fas fa-inbox text-muted" style="font-size: 4rem;"></i>
                                    </div>
                                    <h5 class="text-muted mb-3">Belum Ada Pengumuman</h5>
                                    <p class="text-muted">Pengumuman akan muncul di sini setelah panitia mempublikasikannya.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('templatepeserta/lib/easing/easing.min.js')}}"></script>
    <script src="{{ asset('templatepeserta/lib/waypoints/waypoints.min.js')}}"></script>
    <script src="{{ asset('templatepeserta/lib/counterup/counterup.min.js')}}"></script>
    <script src="{{ asset('templatepeserta/lib/owlcarousel/owl.carousel.min.js')}}"></script>

    <script src="{{ asset('templatepeserta/js/main.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops!',
                text: '{{ session('error') }}',
                confirmButtonText: 'OK'
            });
        </script>
    @endif
</body>

</html>