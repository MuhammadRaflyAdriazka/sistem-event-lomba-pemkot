@extends('layouts.admin.app')

@section('title', 'Dashboard Admin ' . (auth()->user()->dinas->name ?? 'Dinas'))

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Selamat Datang</h6>
            </div>
            <div class="card-body">
                <p>Ini adalah dashboard utama untuk mengelola event dan kompetisi lomba.</p>
                <p>Gunakan sidebar untuk navigasi ke berbagai bagian sistem.</p>
                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <h5><i class="fas fa-calendar-alt"></i> Total Event</h5>
                                <h2>{{ $totalEvent ?? 0 }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h5><i class="fas fa-users"></i> Peserta Aktif</h5>
                                <h2>{{ $pesertaAktif ?? 0 }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <h5><i class="fas fa-trophy"></i> Event Selesai</h5>
                                <h2>{{ $eventSelesai ?? 0 }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection