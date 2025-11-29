<div class="container-fluid p-0">
            <nav class="navbar navbar-expand-lg bg-white navbar-light py-3 py-lg-0 px-lg-5">
                <a href="{{ route('dashboard') }}" class="navbar-brand ml-lg-3">
                    <img src="{{ asset('templatepeserta/img/logo-pemkot.png') }}" alt="Logo Pemkot" style="height: 100px;">
                    <img src="{{ asset('templatepeserta/img/river-logo.png') }}" alt="Logo Pemkot" style="height: 100px;">
                </a>
                <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-center px-lg-3" id="navbarCollapse">
                    <div class="navbar-nav py-0">
                        <a href="{{ route('dashboard') }}" class="nav-item nav-link {{ Request::routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
                        <a href="{{ route('acara') }}" class="nav-item nav-link {{ Request::routeIs('acara*') ? 'active' : '' }}">Acara Saya</a>
                    </div>
                </div>
            </nav>
        </div>