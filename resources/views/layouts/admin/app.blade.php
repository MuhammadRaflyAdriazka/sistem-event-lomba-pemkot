<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Admin Dashboard for Event Management System">
    <meta name="author" content="Muhammad Rafly Adriazka">

    <title>Admin Dinas - Event Management</title>

    <link href="{{ asset('templateadmin/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <link href="{{ asset('templateadmin/css/sb-admin-2.min.css')}}" rel="stylesheet">

    {{-- Slot untuk CSS tambahan dari halaman lain --}}
    @stack('styles')
</head>

<body id="page-top">
    <div id="wrapper">

        @include('layouts.admin.sidebar')
        <div id="content-wrapper" class="d-flex flex-column">

            <d id="content">

                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <div class="d-flex align-items-center">
                        <h1 class="h4 mb-0 text-gray-800 mr-4">@yield('title', 'Dashboard')</h1>
                    </div>

                    <ul class="navbar-nav ml-auto">
                        @include('layouts.admin.topbar')
                    </ul>
                </nav>
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
        </div>
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-primary">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('templateadmin/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('templateadmin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <script src="{{ asset('templateadmin/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <script src="{{ asset('templateadmin/js/sb-admin-2.min.js') }}"></script>

    <script src="{{ asset('templateadmin/vendor/chart.js/Chart.min.js') }}"></script>

    <script src="{{ asset('templateadmin/js/demo/chart-area-demo.js') }}"></script>
    <script src="{{ asset('templateadmin/js/demo/chart-pie-demo.js') }}"></script>

    {{-- Slot untuk JavaScript tambahan dari halaman lain --}}
    @stack('scripts')
</body>

</html>