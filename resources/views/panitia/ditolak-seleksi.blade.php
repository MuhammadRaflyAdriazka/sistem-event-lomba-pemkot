@extends('layouts.panitia.app')

{{-- Mengatur judul halaman --}}
@section('title', 'Peserta Ditolak')

@push('styles')
<style>
    /* Style tambahan untuk gambar acara */
    .event-info-card img {
        max-height: 80px;
        width: auto;
        object-fit: contain;
    }
    .table th, .table td {
        vertical-align: middle;
    }
</style>
@endpush

@section('content')

{{-- Header Halaman --}}
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Peserta Ditolak/Cadangan</h1>
    <a href="{{ route('panitia.dashboard') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali ke Dashboard
    </a>
</div>

{{-- Alert untuk notifikasi --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

{{-- Informasi Acara --}}
<div class="card shadow mb-4 event-info-card">
    <div class="card-body d-flex align-items-center">
        <div>
            <h5 class="m-0 font-weight-bold text-primary">Acara: {{ $acara->judul }}</h5>
            <p class="m-0 text-muted">Kuota: {{ $acara->kuota }} peserta | Sistem: {{ $acara->sistem_pendaftaran }}</p>
        </div>
    </div>
</div>

{{-- Tabel Daftar Peserta Ditolak --}}
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Peserta yang Ditolak</h6>
        <a href="{{ route('panitia.peserta') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-list"></i> Lihat Menunggu Seleksi
        </a>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-2">
                <p><strong>Peserta Ditolak:</strong> {{ $pesertaDitolak->count() }}</p>
            </div>
            <div class="col-md-2">
                <p><strong>Menunggu Seleksi:</strong> {{ $jumlahPending }}</p>
            </div>
            <div class="col-md-2">
                <p><strong>Diterima:</strong> <span class="text-success">{{ $jumlahDiterima }}</span></p>
            </div>
            <div class="col-md-2">
                <p><strong>Mengundurkan Diri:</strong> <span class="text-secondary">{{ $jumlahMengundurkanDiri }}</span></p>
            </div>
            <div class="col-md-2">
                <p><strong>Kuota Tersisa:</strong> {{ $acara->kuota - $jumlahDiterima }}</p>
            </div>
        </div>

        @if($pesertaDitolak->count() > 0)
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="dataTablePeserta" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>No.HP</th>
                        <th>Alamat</th>
                        <th>Tgl Daftar</th>
                        <th>Alasan Penolakan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pesertaDitolak as $index => $pendaftaran)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $pendaftaran->pengguna->name }}</td>
                        <td>{{ $pendaftaran->pengguna->email }}</td>
                        <td>
                            {{-- Coba beberapa kemungkinan nama kolom untuk No.HP --}}
                            @php
                                $nomorTelepon = $pendaftaran->dataPendaftaran->whereIn('nama_kolom', [
                                    'nomor_telepon', 'no_hp', 'nomor_hp', 'telepon', 'no_telp', 'phone'
                                ])->first();
                            @endphp
                            {{ $nomorTelepon ? $nomorTelepon->nilai_kolom : '-' }}
                        </td>
                        <td>
                            {{-- Coba beberapa kemungkinan nama kolom untuk Alamat --}}
                            @php
                                $alamat = $pendaftaran->dataPendaftaran->whereIn('nama_kolom', [
                                    'alamat', 'alamat_lengkap', 'address'
                                ])->first();
                            @endphp
                            {{ $alamat ? $alamat->nilai_kolom : '-' }}
                        </td>
                        <td>{{ \Carbon\Carbon::parse($pendaftaran->created_at)->locale('id')->format('d M Y') }}</td>
                        <td>
                            <span class="badge badge-danger">{{ $pendaftaran->alasan_penolakan }}</span>
                        </td>
                        <td>
                            <a href="{{ route('panitia.peserta.detail', $pendaftaran->id) }}" class="btn btn-info btn-sm" title="Lihat Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button type="button" class="btn btn-warning btn-sm" title="Review Ulang" onclick="confirmReviewUlang({{ $pendaftaran->id }}, '{{ $pendaftaran->pengguna->name }}')">
                                <i class="fas fa-redo"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @else
        <div class="text-center">
            <p class="text-muted">Belum ada peserta yang ditolak.</p>
        </div>
        @endif
    </div>
</div>

{{-- Form untuk batalkan penolakan --}}
<form id="formBatalkanPenolakan" method="POST" style="display: none;">
    @csrf
</form>

@endsection

@push('scripts')
<script>
function confirmReviewUlang(pendaftaranId, namaPeserta) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Review Ulang Peserta?',
            text: `Peserta "${namaPeserta}" akan dikembalikan ke status menunggu seleksi.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Review Ulang!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('formBatalkanPenolakan');
                form.action = `/panitia/peserta/${pendaftaranId}/batalkan-penolakan`;
                form.submit();
            }
        });
    } else {
        if (confirm(`Apakah Anda yakin ingin melakukan review ulang untuk peserta "${namaPeserta}"?\n\nPeserta akan dikembalikan ke status menunggu seleksi.`)) {
            const form = document.getElementById('formBatalkanPenolakan');
            form.action = `/panitia/peserta/${pendaftaranId}/batalkan-penolakan`;
            form.submit();
        }
    }
}

// DataTable initialization
$(document).ready(function() {
    if ($.fn.DataTable) {
        $('#dataTablePeserta').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
            },
            "pageLength": 25,
            "order": [[ 5, "desc" ]]
        });
    }
});
</script>
@endpush
