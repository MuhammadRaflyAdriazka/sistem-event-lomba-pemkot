<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Pemkot Banjarmasin</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Acara, Banjarmasin" name="keywords">
    <meta content="Sistem Informasi Acara Pemerintah Kota Banjarmasin" name="description">
    <link rel="icon" href="{{ asset('image/LOGO-PEMKOT-BARU.png') }}" type="image/png">


    <link href="{{ asset('templatepeserta/img/favicon.ico') }}" rel="icon">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet"> 

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <link href="{{ asset('templatepeserta/lib/owlcarousel/assets/owl.carousel.min.css')}}" rel="stylesheet">

    <link href="{{ asset('templatepeserta/css/style.css') }}" rel="stylesheet">
    
    @stack('styles')
</head>

<body class="d-flex flex-column min-vh-100">
    @include('layouts.peserta.topbar')
    <div class="flex-grow-1">
        @include('layouts.peserta.navbar')
        {{-- Konten dari halaman spesifik akan masuk di sini --}}
        <main>
            @yield('content')
        </main>
    </div>

    @include('layouts.peserta.footer')
    <a href="#" class="btn btn-lg btn-primary rounded-0 btn-lg-square back-to-top"><i class="fa fa-angle-double-up"></i></a>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('templatepeserta/lib/easing/easing.min.js')}}"></script>
    <script src="{{ asset('templatepeserta/lib/waypoints/waypoints.min.js')}}"></script>
    <script src="{{ asset('templatepeserta/lib/counterup/counterup.min.js')}}"></script>
    <script src="{{ asset('templatepeserta/lib/owlcarousel/owl.carousel.min.js')}}"></script>

    <script src="{{ asset('templatepeserta/js/main.js')}}"></script>

    @stack('scripts')
</body>
</html>