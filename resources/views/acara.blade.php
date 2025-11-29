@extends('layouts.peserta.app')

@push('styles')
<style>
    .event-image-wrapper {
        width: 100%;
        height: 250px;
        overflow: hidden;
        position: relative;
    }
    .event-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
    }
</style>
@endpush

@section('content')
        <div class="container-fluid py-5">
            <div class="container">
                <div class="text-center mb-5">
                    <h1>Daftar Acara yang Anda Ikuti</h1>
                </div>

                <div class="row">
                    @if(isset($pendaftaranUser) && $pendaftaranUser->count() > 0)
                        @foreach($pendaftaranUser as $pendaftaran)
                        <!-- Acara {{ $loop->iteration }} -->
                        <div class="col-lg-12 mb-4">
                            <div class="card shadow-lg border-0 rounded-lg overflow-hidden">
                                <div class="row no-gutters">
                                    <div class="col-md-4">
                                        <div class="event-image-wrapper">
                                            <img src="{{ asset('images/events/' . $pendaftaran->acara->gambar) }}" 
                                                 alt="{{ $pendaftaran->acara->judul }}" 
                                                 class="event-image"
                                                 onerror="this.src='{{ asset('templatepeserta/img/eror.jpeg') }}'">
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="card-body">
                                            <h5 class="card-title mb-3">{{ $pendaftaran->acara->judul }}</h5>
                                            <p><i class="fa fa-calendar"></i> Tanggal Pelaksanaan: {{ \Carbon\Carbon::parse($pendaftaran->acara->tanggal_acara)->locale('id')->isoFormat('D MMMM Y') }}</p>
                                            <p><i class="fa fa-map-marker-alt"></i> Lokasi: {{ $pendaftaran->acara->lokasi }}</p>
                                            <p><i class="fa fa-money-bill-wave"></i> Biaya: {{ $pendaftaran->acara->biaya }}</p>
                                            <p><i class="fa fa-clock"></i> Status Pendaftaran: 
                                                @if($pendaftaran->status == 'pending')
                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                @elseif($pendaftaran->status == 'disetujui')
                                                    <span class="badge bg-success text-white">Disetujui</span>
                                                @elseif($pendaftaran->status == 'ditolak')
                                                    <span class="badge bg-danger text-white">Ditolak</span>
                                                @elseif($pendaftaran->status == 'mengundurkan_diri')
                                                    <span class="badge bg-secondary text-white">Mengundurkan Diri</span>
                                                @else
                                                    <span class="badge bg-info text-white">{{ ucfirst($pendaftaran->status) }}</span>
                                                @endif
                                            </p>
                                            <p><i class="fa fa-calendar-plus"></i> Tanggal Daftar: {{ \Carbon\Carbon::parse($pendaftaran->created_at)->locale('id')->isoFormat('D MMMM Y, HH:mm') }}</p>
                                            <div class="mt-3">
                                                <a href="{{ route('acara.detailPendaftaran', $pendaftaran->id) }}" class="btn btn-primary">
                                                    <i class="fas fa-eye me-1"></i>Lihat Detail
                                                </a>
                                                @if($pendaftaran->status == 'diterima' || $pendaftaran->status == 'disetujui')
                                                    <a href="{{ route('acara.pengumuman', $pendaftaran->id) }}" class="btn btn-outline-success">
                                                        <i class="fas fa-bullhorn me-1"></i>Lihat Pengumuman
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <!-- Jika belum ada pendaftaran -->
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="fas fa-calendar-times fa-5x text-muted mb-4"></i>
                                <h3 class="text-muted">Belum Ada Acara yang Diikuti</h3>
                                <p class="text-muted mb-4">Anda belum mendaftar pada acara apapun. Mulai jelajahi dan daftar pada acara yang menarik!</p>
                                <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg">
                                    <i class="fas fa-search mr-2"></i>Jelajahi Acara
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            confirmButtonText: 'OK'
        });
    </script>
@endif
@endpush