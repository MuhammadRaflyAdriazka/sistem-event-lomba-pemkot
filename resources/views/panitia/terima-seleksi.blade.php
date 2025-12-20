@extends('layouts.panitia.app')

{{-- Mengatur judul halaman --}}
@section('title', 'Kelola Pendaftaran')

@push('styles')
<style>
    /* Style tambahan untuk gambar acara */
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

{{-- Informasi Acara --}}
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
                        <th width="200">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pesertaDiterima as $index => $item)
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
                        <td style="white-space: nowrap;">
                            <a href="{{ route('panitia.peserta.detail', $item->id) }}" class="btn btn-primary btn-sm" style="display: inline-block;">
                                Lihat Detail
                            </a>
                            <form id="form-batalkan-{{ $item->id }}" action="{{ route('panitia.peserta.batalkan', $item->id) }}" method="POST" style="display: inline-block;">
                                @csrf
                                <button type="button" class="btn btn-warning btn-sm" onclick="confirmBatalkan({{ $item->id }})">
                                    <i class="fas fa-undo"></i> Batalkan
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">Belum ada peserta yang diterima.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmBatalkan(pesertaId) {
    Swal.fire({
        title: 'Batalkan Penerimaan Peserta?',
        html: '<p>Apakah Anda yakin ingin membatalkan penerimaan peserta ini?</p><p class="text-muted">Peserta akan kembali ke daftar menunggu seleksi.</p>',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#ffc107',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Batalkan!',
        cancelButtonText: 'Batal',
        customClass: {
            confirmButton: 'btn btn-warning btn-lg mx-2',
            cancelButton: 'btn btn-secondary btn-lg mx-2'
        },
        buttonsStyling: false,
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Memproses...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading()
                }
            });
            document.getElementById('form-batalkan-' + pesertaId).submit();
        }
    });
}
</script>
@endpush