@extends('layouts.panitia.app')

@section('title', 'Dashboard Panitia')

@section('content')

<!-- Acara Info Card -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-calendar-alt mr-2"></i>Informasi Acara yang Anda Kelola
        </h6>
    </div>
    <div class="card-body">
        @if($acara)
        <div class="row">
            <div class="col-md-6">
                <h5 class="font-weight-bold text-dark">{{ $acara->judul }}</h5>
                <p class="text-muted mb-2">
                    <i class="fas fa-calendar mr-2"></i>
                    Tanggal Pelaksanaan: {{ \Carbon\Carbon::parse($acara->tanggal_acara)->locale('id')->isoFormat('D MMMM Y') }}
                </p>
                <p class="text-muted mb-2">
                    <i class="fas fa-map-marker-alt mr-2"></i>
                    Lokasi: {{ $acara->lokasi }}
                </p>
                <p class="text-muted mb-2">
                    <i class="fas fa-users mr-2"></i>
                    Kuota: {{ $acara->kuota }} peserta
                </p>
                <p class="text-muted mb-2">
                    <i class="fas fa-cog mr-2"></i>
                    Sistem Pendaftaran: {{ $acara->sistem_pendaftaran }}
                </p>
                <p class="text-muted mb-2">
                    <i class="fas fa-calendar-check mr-2"></i>
                    Tenggat Pendaftaran: {{ \Carbon\Carbon::parse($acara->tanggal_mulai_daftar)->locale('id')->isoFormat('D/M') }} - {{ \Carbon\Carbon::parse($acara->tanggal_akhir_daftar)->locale('id')->isoFormat('D/M/Y') }}
                </p>
            </div>
            <div class="col-md-5 text-center text-md-right">
                @if($acara->sistem_pendaftaran === 'Seleksi')
                    <a href="{{ route('panitia.peserta') }}" class="btn btn-lg btn-primary shadow-sm mt-3 mt-5">
                        <i class="fas fa-list-ul fa-sm mr-1"></i> Kelola Peserta
                    </a>
                @else
                    <a href="{{ route('panitia.peserta.tanpaSeleksi') }}" class="btn btn-lg btn-success shadow-sm mt-3 mt-md-0">
                        <i class="fas fa-users fa-sm mr-1"></i> Kelola Peserta
                    </a>
                @endif
                <br>
                <a href="{{ route('panitia.pengumuman') }}" class="btn btn-lg btn-warning shadow-sm mt-3">
                    <i class="fas fa-bullhorn fa-sm mr-1"></i> Buat Pengumuman
                </a>
            </div>
        </div>
        @else
        <!-- Tampilan ketika belum ada acara -->
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="fas fa-clipboard-list fa-4x text-muted"></i>
            </div>
            <h4 class="text-muted mb-3">Belum Ada Acara yang Ditugaskan</h4>
            <p class="text-muted mb-4">
                Saat ini belum ada acara yang Anda kelola.<br>
                Admin akan menambahkan acara dan menugaskan Anda sebagai panitia.
            </p>
            <div class="alert alert-info">
                <i class="fas fa-info-circle mr-2"></i>
                <strong>Info:</strong> Dashboard akan menampilkan informasi acara dan menu pengelolaan setelah Anda ditugaskan ke acara tertentu.
            </div>
        </div>
        @endif
    </div>
</div>

@endsection