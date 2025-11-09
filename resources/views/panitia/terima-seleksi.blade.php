@extends('layouts.panitia.app')

{{-- Mengatur judul halaman --}}
@section('title', 'Kelola Pendaftaran')

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
    <h1 class="h3 mb-0 text-gray-800">Peserta Diterima</h1>
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

{{-- Tabel Daftar Peserta Diterima --}}
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Peserta yang Diterima</h6>
        <a href="{{ route('panitia.peserta') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-list"></i> Lihat Menunggu Seleksi
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
            <div class="col-md-2">
                <p><strong>Peserta Diterima:</strong> {{ $pesertaDiterima->count() }}</p>
            </div>
            <div class="col-md-2">
                <p><strong>Menunggu Seleksi:</strong> {{ $jumlahPending }}</p>
            </div>
            <div class="col-md-2">
                <p><strong>Ditolak:</strong> <span class="text-danger">{{ $jumlahDitolak }}</span></p>
            </div>
            <div class="col-md-2">
                <p><strong>Mengundurkan Diri:</strong> <span class="text-secondary">{{ $jumlahMengundurkanDiri }}</span></p>
            </div>
            <div class="col-md-2">
                <p><strong>Kuota Tersisa:</strong> {{ $acara->kuota - $pesertaDiterima->count() }}</p>
            </div>
        </div>

        @if($pesertaDiterima->count() > 0)
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="dataTablePeserta" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>No.HP</th>
                        <th>Alamat</th>
                        <th>Tanggal Diterima</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pesertaDiterima as $index => $item)
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
                        <td>{{ \Carbon\Carbon::parse($item->updated_at)->timezone('Asia/Makassar')->format('d/m/Y H:i') }} WITA</td>
                        <td>
                            <a href="{{ route('panitia.peserta.detail', $item->id) }}" class="btn btn-primary btn-sm">
                                Lihat Detail
                            </a>
                            <form action="{{ route('panitia.peserta.batalkan', $item->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Yakin ingin membatalkan penerimaan peserta ini?')">
                                @csrf
                                <button type="submit" class="btn btn-warning btn-sm">
                                    <i class="fas fa-undo"></i> Batalkan
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-4">
            <p class="text-muted">Belum ada peserta yang diterima.</p>
            <a href="{{ route('panitia.peserta') }}" class="btn btn-primary">
                <i class="fas fa-search"></i> Mulai Seleksi
            </a>
        </div>
        @endif
    </div>
</div>

@endsection

@push('scripts')
{{-- Script opsional jika diperlukan --}}
@endpush