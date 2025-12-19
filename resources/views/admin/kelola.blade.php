@extends('layouts.admin.app')

{{-- Mengatur judul halaman yang akan tampil di top bar --}}
@section('title', 'Kelola Acara')

{{-- Menyisipkan CSS khusus untuk halaman ini --}}
@push('styles')
<style>
    .event-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 20px;
        overflow: hidden;
    }
    .event-content {
        padding: 20px;
    }
    .event-title {
        font-size: 18px;
        font-weight: bold;
        color: #333;
        margin-bottom: 10px;
    }
    .event-meta {
        font-size: 14px;
        color: #666;
        margin-bottom: 5px;
    }
    .event-meta i {
        width: 15px;
        margin-right: 8px;
        color: #858796;
    }
    .btn-edit {
        background: linear-gradient(45deg, #ffd700, #ffed4e);
        border: none;
        color: #333;
        padding: 8px 20px;
        border-radius: 20px;
        font-weight: 600;
        margin-right: 10px;
    }
    .btn-edit:hover {
        background: linear-gradient(45deg, #ffed4e, #ffd700);
        color: #333;
    }
    .btn-delete {
        background: linear-gradient(45deg, #ff4757, #ff6b7a);
        border: none;
        color: white;
        padding: 8px 20px;
        border-radius: 20px;
        font-weight: 600;
    }
    .btn-delete:hover {
        background: linear-gradient(45deg, #ff6b7a, #ff4757);
        color: white;
    }
    .btn-selesai {
        background: linear-gradient(45deg, #28a745, #34ce57);
        border: none;
        color: white;
        padding: 8px 20px;
        border-radius: 20px;
        font-weight: 600;
        margin-bottom: 10px;
    }
    .btn-selesai:hover {
        background: linear-gradient(45deg, #34ce57, #28a745);
        color: white;
    }
</style>
@endpush

{{-- Memulai bagian konten utama --}}
@section('content')

<h1 class="h3 mb-4 text-gray-800">Kelola Acara</h1>

{{-- Tombol Aksi --}}
<div class="row mb-4">
    <div class="col-md-6">
        <a href="{{ route('admin.create') }}" class="btn btn-success btn-lg">
            <i class="fas fa-plus mr-2"></i>Buat Acara Baru
        </a>
    </div>
    <div class="col-md-6 text-right">
        <a href="{{ route('admin.acara.selesai') }}" class="btn btn-info btn-lg">
            <i class="fas fa-history mr-2"></i>Lihat Acara yang Sudah Selesai
        </a>
    </div>
</div>

{{-- Alert Messages --}}
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
    <div class="card-header py-0 d-flex justify-content-between align-items-center">
    </div>
    <div class="card-body">
        
        @if(isset($events) && $events->count() > 0)
            @foreach($events as $event)
                <div class="event-card">
                    <div class="row no-gutters align-items-center">
                        {{-- Kolom konten diperlebar menjadi col-md-9 --}}
                        <div class="col-md-6">
                            <div class="event-content">
                                <h5 class="event-title">{{ $event->judul }}</h5>
                                <div class="event-meta"><i class="fas fa-calendar"></i>Tanggal Pelaksanaan: {{ \Carbon\Carbon::parse($event->tanggal_acara)->locale('id')->isoFormat('D MMMM Y') }}</div>
                                <div class="event-meta"><i class="fas fa-map-marker-alt"></i>Lokasi: {{ $event->lokasi }}</div>
                                <div class="event-meta"><i class="fas fa-users"></i>Kuota: {{ $event->kuota }} peserta</div>
                                <div class="event-meta"><i class="fas fa-tag"></i>Sistem Pendaftaran: {{ $event->sistem_pendaftaran }}</div>
                                <div class="event-meta"><i class="fas fa-clock"></i>Tenggat Pendaftaran: {{ \Carbon\Carbon::parse($event->tanggal_mulai_daftar)->locale('id')->isoFormat('D/M') }} - {{ \Carbon\Carbon::parse($event->tanggal_akhir_daftar)->locale('id')->isoFormat('D/M/Y') }}</div>
                            </div>
                        </div>
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
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 text-center">
                                @if($event->status == 'active')
                                    <button type="button" class="btn btn-selesai btn-sm d-block w-100" onclick="confirmSelesai({{ $event->id }})">
                                        <i class="fas fa-check mr-1"></i>Selesai
                                    </button>
                                    <a href="{{ route('admin.event.edit', $event->id) }}" class="btn btn-edit btn-sm mb-2 d-block"><i class="fas fa-edit mr-1"></i>Edit</a>
                                    <button type="button" class="btn btn-delete btn-sm d-block w-100" onclick="confirmDelete({{ $event->id }})">
                                        <i class="fas fa-trash mr-1"></i>Hapus
                                    </button>
                                @elseif($event->status == 'inactive')
                                    <span class="badge badge-success p-2 d-block">Acara Selesai</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="text-center py-5">
                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Belum Ada Acara yang Dibuat</h5>
                <p class="text-muted">Silakan Buat Acara Baru melalui tombol di atas</p>
                <a href="{{ route('admin.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus mr-1"></i>Buat Acara Baru
                </a>
            </div>
        @endif

    </div>
</div>

{{-- Form untuk delete (hidden) --}}
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

{{-- Form untuk selesai (hidden) --}}
<form id="selesaiForm" method="POST" style="display: none;">
    @csrf
    @method('PATCH')
</form>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    console.log('Admin kelola scripts loaded');
    console.log('SweetAlert2 available:', typeof Swal !== 'undefined');
});

function confirmDelete(eventId) {
    console.log('Delete clicked for event:', eventId);
    
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Yakin ingin mengHapus Acara ini?',
            text: 'Tindakan ini tidak dapat dibatalkan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('deleteForm');
                if (form) {
                    form.action = '/admin/event/' + eventId;
                    form.submit();
                } else {
                    console.error('Delete form not found');
                }
            }
        });
    } else {
        if(confirm('Yakin ingin mengHapus Acara ini? Tindakan ini tidak dapat dibatalkan!')) {
            const form = document.getElementById('deleteForm');
            if (form) {
                form.action = '/admin/event/' + eventId;
                form.submit();
            }
        }
    }
}

function confirmSelesai(eventId) {
    console.log('Selesai clicked for event:', eventId);
    
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Tandai acara sebagai selesai?',
            text: 'Acara akan hilang dari halaman peserta!',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Selesai!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('selesaiForm');
                if (form) {
                    form.action = '/admin/event/' + eventId + '/selesai';
                    form.submit();
                } else {
                    console.error('Selesai form not found');
                }
            }
        });
    } else {
        if(confirm('Yakin ingin menandai acara ini sebagai selesai? Acara akan hilang dari halaman peserta!')) {
            const form = document.getElementById('selesaiForm');
            if (form) {
                form.action = '/admin/event/' + eventId + '/selesai';
                form.submit();
            }
        }
    }
}
</script>
@endpush



