@extends('layouts.admin.app')

{{-- Mengatur judul halaman yang akan tampil di top bar --}}
@section('title', 'Kelola Event')

{{-- Memulai bagian konten utama --}}
@section('content')

<h1 class="h3 mb-4 text-gray-800">Kelola Event dan Lomba</h1>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Buat Event / Lomba Baru</h6>
    </div>
    <div class="card-body">
        <p>Untuk membuat event atau lomba baru, klik tombol di bawah ini:</p>
        <a href="{{ route('admin.create') }}" class="btn btn-primary">Buat</a>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Lihat Event / Lomba yang Sudah Dibuat</h6>
    </div>
    <div class="card-body">
        <p>Untuk melihat event atau lomba yang sudah dibuat, klik tombol di bawah ini:</p>
        <a href="{{ route('admin.event') }}" class="btn btn-primary">Lihat</a>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Lihat Event / Lomba yang Sudah Selesai</h6>
    </div>
    <div class="card-body">
        <p>Untuk melihat event atau lomba yang sudah selesai, klik tombol di bawah ini:</p>
        <a href="/acara-selesai" class="btn btn-primary">Lihat</a>
    </div>
</div>

@endsection