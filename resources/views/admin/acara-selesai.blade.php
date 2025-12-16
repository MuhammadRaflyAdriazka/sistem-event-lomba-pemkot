@extends('layouts.admin.app')

@section('title', 'Acara yang Sudah Selesai')

@push('styles')
<style>
    .event-card {
        background: white;
        border-radius: .35rem;
        box-shadow: 0 .15rem 1.75rem 0 rgba(58,59,69,.15)!important;
        margin-bottom: 1.5rem;
        border: 1px solid #e3e6f0;
    }

    .event-content {
        padding: 1.25rem;
    }

    .event-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: #5a5c69;
        margin-bottom: 1rem;
    }

    .event-meta {
        font-size: 0.875rem;
        color: #6c757d;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
    }

    .event-meta i {
        width: 20px;
        margin-right: 10px;
        color: #858796;
    }

    .event-actions {
        padding: 1.25rem;
        border-left: 1px solid #e3e6f0;
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 10px;
    }

    @media (max-width: 767.98px) {
        .event-actions {
            border-left: none;
            border-top: 1px solid #e3e6f0;
            padding-top: 1.25rem;
        }
    }

    .badge-selesai {
        background-color: #28a745;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 0.25rem;
        font-size: 0.875rem;
        font-weight: 600;
    }
</style>
@endpush

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Acara yang Sudah Selesai</h1>
    <a href="{{ route('admin.kelola') }}" class="btn btn-secondary shadow-sm">
        <i class="fas fa-arrow-left fa-sm text-white-50 mr-1"></i> Kembali ke Kelola
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<div class="card shadow">
    <div class="card-body">
        @if(isset($events) && $events->count() > 0)
            @foreach($events as $event)
                <div class="event-card">
                    <div class="row no-gutters align-items-center">
                        {{-- Kolom Info Event --}}
                        <div class="col-md-6">
                            <div class="event-content">
                                <h5 class="event-title">{{ strtoupper($event->judul) }}</h5>
                                <div class="event-meta"><i class="fas fa-calendar"></i>Tanggal Pelaksanaan: {{ \Carbon\Carbon::parse($event->tanggal_acara)->locale('id')->isoFormat('D MMMM Y') }}</div>
                                <div class="event-meta"><i class="fas fa-map-marker-alt"></i>Lokasi: {{ $event->lokasi }}</div>
                                <div class="event-meta"><i class="fas fa-users"></i>Kuota: {{ $event->kuota }} peserta</div>
                                <div class="event-meta"><i class="fas fa-tag"></i>Sistem Pendaftaran: {{ $event->sistem_pendaftaran }}</div>
                                <div class="event-meta"><i class="fas fa-clock"></i>Tenggat Pendaftaran: {{ \Carbon\Carbon::parse($event->tanggal_mulai_daftar)->locale('id')->isoFormat('D/M') }} - {{ \Carbon\Carbon::parse($event->tanggal_akhir_daftar)->locale('id')->isoFormat('D/M/Y') }}</div>
                            </div>
                        </div>

                        {{-- Kolom Statistik --}}
                        <div class="col-md-3 d-flex align-items-center justify-content-center">
                            <div class="p-3 text-center">
                                <h6 class="font-weight-bold text-primary mb-3">
                                    <i class="fas fa-chart-bar mr-2"></i>Statistik Peserta
                                </h6>
                                @php
                                    // Hitung statistik peserta untuk event ini
                                    $totalPendaftar = $event->pendaftaran->count();
                                    $menungguSeleksi = $event->pendaftaran->where('status', 'pending')->count();
                                    $diterima = $event->pendaftaran->where('status', 'diterima')->count();
                                    $ditolak = $event->pendaftaran->where('status', 'ditolak')->count();
                                    $mengundurkanDiri = $event->pendaftaran->where('status', 'mengundurkan_diri')->count();
                                    $kuotaTersisa = max(0, $event->kuota - $diterima);
                                @endphp
                                
                                {{-- Statistik dalam satu baris horizontal - tengah --}}
                                <div class="d-flex justify-content-center text-center" style="font-size: 12px; gap: 15px;">
                                    @if($event->sistem_pendaftaran === 'Seleksi')
                                        {{-- Statistik untuk Sistem Seleksi --}}
                                        <div class="text-center">
                                            <div class="text-muted">Total Pendaftar:</div>
                                            <div class="font-weight-bold text-primary">{{ $totalPendaftar }}</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="text-muted">Menunggu Seleksi:</div>
                                            <div class="font-weight-bold text-warning">{{ $menungguSeleksi }}</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="text-muted">Diterima:</div>
                                            <div class="font-weight-bold text-success">{{ $diterima }}</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="text-muted">Ditolak:</div>
                                            <div class="font-weight-bold text-danger">{{ $ditolak }}</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="text-muted">Mengundurkan Diri:</div>
                                            <div class="font-weight-bold text-secondary">{{ $mengundurkanDiri }}</div>
                                        </div>
                                    @else
                                        {{-- Statistik untuk Sistem Tanpa Seleksi - Sama seperti Panitia --}}
                                        <div class="text-center">
                                            <div class="text-muted">Total Pendaftar:</div>
                                            <div class="font-weight-bold text-primary">{{ $totalPendaftar }}</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="text-muted">Diterima:</div>
                                            <div class="font-weight-bold text-success">{{ $diterima }}</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="text-muted">Ditolak/Dibatalkan:</div>
                                            <div class="font-weight-bold text-danger">{{ $ditolak }}</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="text-muted">Mengundurkan Diri:</div>
                                            <div class="font-weight-bold text-secondary">{{ $mengundurkanDiri }}</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="text-muted">Kuota Tersisa:</div>
                                            <div class="font-weight-bold text-info">{{ $kuotaTersisa }}</div>
                                        </div>
                                    @endif
                                </div>
                                
                                @if($totalPendaftar == 0)
                                    <div class="alert alert-info alert-sm mt-3 py-2" style="font-size: 11px;">
                                        <i class="fas fa-info-circle mr-1"></i>Belum ada peserta terdaftar
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Kolom Status Selesai --}}
                        <div class="col-md-3 event-actions">
                            <div class="text-center">
                                <span class="badge-selesai">
                                    <i class="fas fa-check-circle mr-2"></i>Acara Selesai
                                </span>
                                <div class="mt-3 text-muted" style="font-size: 0.8rem;">
                                    Diselesaikan pada:<br>
                                    <strong>{{ \Carbon\Carbon::parse($event->updated_at)->locale('id')->isoFormat('D MMMM Y, HH:mm') }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="text-center py-5">
                <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">Belum Ada acara yang selesai</h5>
                <p class="text-muted">Acara yang sudah ditandai selesai akan muncul di sini.</p>
                <a href="{{ route('admin.kelola') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali ke Kelola Acara
                </a>
            </div>
        @endif
    </div>
</div>

@endsection



