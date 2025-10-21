@extends('layouts.admin.app')

{{-- Mengatur judul halaman yang akan tampil di top bar --}}
@section('title', 'Kelola Event')

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
        color: #007bff;
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

<h1 class="h3 mb-4 text-gray-800">Event yang Sudah Dibuat</h1>

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
        
        @if($events->count() > 0)
            @foreach($events as $event)
                <div class="event-card">
                    <div class="row no-gutters align-items-center">
                        {{-- Kolom gambar sudah dihapus --}}
                        
                        {{-- Kolom konten diperlebar menjadi col-md-9 --}}
                        <div class="col-md-9">
                            <div class="event-content">
                                <h5 class="event-title">{{ strtoupper($event->judul) }}</h5>
                                <div class="event-meta"><i class="fas fa-calendar"></i>Tanggal Pelaksanaan: {{ \Carbon\Carbon::parse($event->tanggal_acara)->format('d F Y') }}</div>
                                <div class="event-meta"><i class="fas fa-map-marker-alt"></i>Lokasi: {{ $event->lokasi }}</div>
                                <div class="event-meta"><i class="fas fa-users"></i>Kuota: {{ $event->kuota }} peserta</div>
                                <div class="event-meta"><i class="fas fa-tag"></i>Sistem Pendaftaran: {{ $event->sistem_pendaftaran }}</div>
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
                                    <span class="badge badge-success p-2 d-block">Event Selesai</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="text-center py-5">
                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Belum Ada Event yang Dibuat</h5>
                <p class="text-muted">Silakan buat event baru melalui menu "Buat Event"</p>
                <a href="{{ route('admin.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus mr-1"></i>Buat Event Baru
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
    function confirmDelete(eventId) {
        if(confirm('Yakin ingin menghapus event ini? Tindakan ini tidak dapat dibatalkan!')) {
            // Set action URL dengan ID event
            document.getElementById('deleteForm').action = '/admin/event/' + eventId;
            // Submit form
            document.getElementById('deleteForm').submit();
        }
    }

    function confirmSelesai(eventId) {
        if(confirm('Yakin ingin menandai event ini sebagai selesai? Event akan hilang dari halaman peserta!')) {
            // Set action URL dengan ID event
            document.getElementById('selesaiForm').action = '/admin/event/' + eventId + '/selesai';
            // Submit form
            document.getElementById('selesaiForm').submit();
        }
    }
</script>
@endpush