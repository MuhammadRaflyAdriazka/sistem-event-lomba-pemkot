@extends('layouts.panitia.app')

{{-- Mengatur judul halaman --}}
@section('title', 'Kelola Peserta')

@push('styles')
<style>
    /* Style tambahan untuk gambar event */
    .event-info-card img {
        max-height: 80px; /* Sesuaikan tinggi gambar jika perlu */
        width: auto;
        object-fit: contain;
    }
    .table th, .table td {
        vertical-align: middle; /* Agar teks di tengah tombol */
    }
</style>
@endpush

@section('content')

{{-- Header Halaman --}}
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Kelola Peserta</h1>
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

{{-- Informasi Event --}}
<div class="card shadow mb-4 event-info-card">
    <div class="card-body d-flex align-items-center">
        <div>
            <h5 class="m-0 font-weight-bold text-primary">Acara: {{ $acara->judul }}</h5>
            <p class="m-0 text-muted">Kuota: {{ $acara->kuota }} peserta | Sistem: {{ $acara->sistem_pendaftaran }}</p>
        </div>
    </div>
</div>

{{-- Tabel Daftar Peserta --}}
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Peserta Menunggu Seleksi</h6>
        <a href="{{ route('panitia.peserta.diterima') }}" class="btn btn-success btn-sm">
            <i class="fas fa-check-circle"></i> Lihat Peserta Diterima
        </a>
    </div>
    <div class="card-body">
        <p><strong>Sistem Pendaftaran:</strong>
            {{-- Badge dinamis berdasarkan sistem pendaftaran --}}
            @if($acara->sistem_pendaftaran == 'seleksi')
                <span class="badge badge-warning">Seleksi</span>
            @else
                <span class="badge badge-success">Seleksi</span>
            @endif
        </p>
        <div class="row">
            <div class="col-md-3">
                <p><strong>Total Pendaftar:</strong> {{ $totalPendaftar }}</p>
            </div>
            <div class="col-md-3">
                <p><strong>Menunggu Seleksi:</strong> {{ $peserta->count() }}</p>
            </div>
            <div class="col-md-3">
                <p><strong>Diterima:</strong> <span class="text-success">{{ $jumlahDiterima }}</span></p>
            </div>
            <div class="col-md-3">
                <p><strong>Ditolak:</strong> <span class="text-danger">{{ $jumlahDitolak }}</span></p>
            </div>
        </div>
        <p><strong>Kuota Tersisa:</strong> {{ $acara->kuota - $jumlahDiterima }} dari {{ $acara->kuota }}</p>

        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="dataTablePeserta" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>No.HP</th>
                        <th>Alamat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peserta as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->pengguna->name }}</td>
                        <td>{{ $item->pengguna->email }}</td>
                        <td>
                            {{-- Coba beberapa kemungkinan nama kolom untuk No.HP --}}
                            @php
                                $nomorTelepon = $item->dataPendaftaran->whereIn('nama_kolom', [
                                    'nomor_telepon', 'no_hp', 'nomor_hp', 'telepon', 'no_telp', 'phone'
                                ])->first();
                            @endphp
                            {{ $nomorTelepon ? $nomorTelepon->nilai_kolom : '-' }}
                        </td>
                        <td>
                            {{-- Coba beberapa kemungkinan nama kolom untuk Alamat --}}
                            @php
                                $alamat = $item->dataPendaftaran->whereIn('nama_kolom', [
                                    'alamat', 'alamat_lengkap', 'address', 'tempat_tinggal'
                                ])->first();
                            @endphp
                            {{ $alamat ? $alamat->nilai_kolom : '-' }}
                        </td>
                        <td>
                            <a href="{{ route('panitia.peserta.detail', $item->id) }}" class="btn btn-primary btn-sm">
                                Lihat Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Belum ada peserta yang mendaftar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('scripts')
{{-- Script opsional jika diperlukan --}}
@endpush