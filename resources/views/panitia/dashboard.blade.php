@extends('layouts.panitia.app')

{{-- Mengatur judul halaman yang akan tampil di top bar --}}
@section('title', 'Dashboard Panitia')

@section('content')

<!-- Event Info Card -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-calendar-alt mr-2"></i>Informasi Event yang Anda Kelola
        </h6>
    </div>
    <div class="card-body">
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
            <div class="col-md-4 text-center text-md-right"> {{-- text-center untuk mobile, text-md-right untuk layar medium ke atas --}}
                <a href="{{ route('panitia.peserta') }}" class="btn btn-lg btn-primary shadow-sm mt-3 mt-md-0"> {{-- btn-lg agar lebih terlihat --}}
                    <i class="fas fa-list-ul fa-sm mr-1"></i> Kelola Pendaftaran
                </a>
            </div>
        </div>
    </div>
</div>

@endsection