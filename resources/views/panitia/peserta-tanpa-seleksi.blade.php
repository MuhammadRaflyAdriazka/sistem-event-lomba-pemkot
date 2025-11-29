@extends('layouts.panitia.app')

@section('title', 'Kelola Pendaftaran')

@push('styles')
<style>
    /* Style tambahan (sama seperti view seleksi) */
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
    <h1 class="h3 mb-0 text-gray-800">Kelola Daftar Peserta</h1>
    {{-- Tombol Kembali --}}
    <a href="{{ route('panitia.dashboard') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali ke Dashboard
    </a>
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
    <i class="fas fa-exclamation-triangle mr-2"></i>{{ session('error') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

@if(session('info'))
<div class="alert alert-info alert-dismissible fade show" role="alert">
    <i class="fas fa-info-circle mr-2"></i>{{ session('info') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-triangle mr-2"></i>
    <strong>Terjadi kesalahan:</strong>
    <ul class="mb-0 mt-2">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
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
            <p class="m-0 text-muted">Kuota: {{ $acara->kuota }} peserta |
                Sistem: <span class="">{{ $acara->sistem_pendaftaran }}</span>
            </p>
        </div>
    </div>
</div>

{{-- Tabel Daftar Peserta --}}
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Peserta Diterima</h6>
        {{-- Tombol untuk lihat peserta ditolak --}}
        <a href="{{ route('panitia.peserta.ditolakTanpaSeleksi') }}" class="btn btn-danger btn-sm">
            <i class="fas fa-times-circle"></i> Lihat Peserta Ditolak
        </a>
    </div>
    <div class="card-body">
        {{-- Statistik --}}
        <div class="row">
            <div class="col-md-2 col-6 mb-2">
                <p><strong>Total Pendaftar:</strong> {{ $totalPendaftar }}</p>
            </div>
             <div class="col-md-2 col-6 mb-2">
                <p><strong>Diterima:</strong> <span class="text-success">{{ $jumlahDiterima }}</span></p>
            </div>
             <div class="col-md-2 col-6 mb-2">
                <p><strong>Ditolak/Dibatalkan:</strong> <span class="text-danger">{{ $jumlahDitolak }}</span></p>
            </div>
             <div class="col-md-2 col-6 mb-2">
                <p><strong>Mengundurkan Diri:</strong> <span class="text-secondary">{{ $jumlahMengundurkanDiri }}</span></p>
            </div>
             <div class="col-md-2 col-6 mb-2">
                <p><strong>Kuota Tersisa:</strong> {{ $kuotaTersisa }}</p>
            </div>
        </div>
        <p><strong>Kuota Acara:</strong> {{ $acara->kuota }} peserta</p>

        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="dataTablePeserta" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>No.HP</th>
                        <th>Alamat</th>
                        <th>Tanggal Diterima</th> {{-- Kolom Tanggal --}}
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pesertaDiterima as $index => $pendaftaran)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $pendaftaran->pengguna->name }}</td>
                        <td>{{ $pendaftaran->pengguna->email }}</td>
                        <td>
                            @php
                                $noHp = $pendaftaran->dataPendaftaran->where('nama_kolom', 'no_hp')->first();
                            @endphp
                            {{ $noHp ? $noHp->nilai_kolom : '-' }}
                        </td>
                        <td>
                            @php
                                $alamat = $pendaftaran->dataPendaftaran->where('nama_kolom', 'alamat')->first();
                            @endphp
                            {{ $alamat ? $alamat->nilai_kolom : '-' }}
                        </td>
                        <td>{{ $pendaftaran->updated_at->format('d M Y, H:i') }}</td>
                        <td>
                            {{-- Link ke halaman detail --}}
                            <a href="{{ route('panitia.peserta.detailTanpaSeleksi', $pendaftaran->id) }}" class="btn btn-primary btn-sm mb-1">
                                Lihat Detail
                            </a>
                             {{-- Form Batalkan Penerimaan --}}
                            <form id="cancelForm_{{ $pendaftaran->id }}" action="{{ route('panitia.peserta.batalkanTanpaSeleksi', $pendaftaran->id) }}" method="POST" style="display: inline-block;">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="alasan_pembatalan" id="cancelReasonInput_{{ $pendaftaran->id }}">
                                <button type="button" class="btn btn-warning btn-sm" onclick="confirmCancel({{ $pendaftaran->id }})">
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
// Fungsi konfirmasi untuk Batalkan Penerimaan
function confirmCancel(pendaftaranId) {
    Swal.fire({
        title: 'Batalkan Penerimaan Peserta?',
        input: 'textarea',
        inputLabel: 'Alasan Pembatalan (Wajib Diisi)',
        inputPlaceholder: 'Masukkan alasan mengapa penerimaan peserta ini dibatalkan...',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545', // Merah
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Batalkan!',
        cancelButtonText: 'Tidak',
        inputValidator: (value) => {
            if (!value) {
                return 'Anda harus memasukkan alasan pembatalan!'
            }
        },
         customClass: {
             confirmButton: 'btn btn-danger btn-lg mx-2',
             cancelButton: 'btn btn-secondary btn-lg mx-2'
         },
         buttonsStyling: false,
         reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed && result.value) {
            // Set nilai alasan ke input hidden dan submit form
            document.getElementById(`cancelReasonInput_${pendaftaranId}`).value = result.value;
            
            // Tampilkan loading
            Swal.fire({ 
                title: 'Memproses...', 
                allowOutsideClick: false, 
                didOpen: () => { 
                    Swal.showLoading() 
                } 
            });
            
            // Submit form
            document.getElementById(`cancelForm_${pendaftaranId}`).submit();
        }
    });
}
</script>
@endpush