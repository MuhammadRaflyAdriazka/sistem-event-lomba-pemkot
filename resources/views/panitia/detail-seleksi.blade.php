@extends('layouts.panitia.app') {{-- Pastikan ini layout panitia Anda --}}

{{-- Mengatur judul halaman --}}
@section('title', 'Detail Peserta')

@push('styles')
<style>
    /* Styling tambahan jika diperlukan */
    .detail-item {
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #e3e6f0; /* Garis pemisah */
    }
    .detail-item:last-child {
        border-bottom: none; /* Hilangkan garis di item terakhir */
        margin-bottom: 0;
        padding-bottom: 0;
    }
    .detail-label {
        font-weight: bold;
        color: #5a5c69; /* Warna abu-abu gelap */
        min-width: 100px; /* Lebar minimum untuk label */
        display: inline-block; /* Agar bisa diberi lebar */
    }
    .btn-lihat {
        padding: 0.25rem 0.75rem; /* Ukuran tombol lebih kecil */
        font-size: 0.875rem;
    }
</style>
@endpush

@section('content')

{{-- Header Halaman --}}
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Detail Peserta</h1>
    <a href="{{ route('panitia.peserta') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali ke Daftar Peserta
    </a>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Detail Peserta</h6>
    </div>
    <div class="card-body">

        {{-- Semua data dari formulir pendaftaran (termasuk nama dan email) --}}
        @foreach($kolomFormulir as $kolom)
            @php
                $dataKolom = $pendaftaran->dataPendaftaran->where('nama_kolom', $kolom->nama_kolom)->first();
            @endphp
            <div class="detail-item">
                <span class="detail-label">{{ $kolom->label_kolom }}:</span>
                @if($dataKolom)
                    @if($kolom->tipe_kolom == 'file')
                        @php
                            // Coba beberapa kemungkinan lokasi file
                            $fileName = $dataKolom->nilai_kolom;
                            $possiblePaths = [
                                storage_path('app/public/' . $fileName),
                                storage_path('app/private/public/pendaftaran/' . $fileName),
                                storage_path('app/' . $fileName)
                            ];
                            
                            $fileFound = false;
                            $fileUrl = '';
                            
                            foreach($possiblePaths as $path) {
                                if(file_exists($path)) {
                                    $fileFound = true;
                                    // Jika file ada di private, buat route khusus untuk akses file
                                    if(strpos($path, 'private') !== false) {
                                        $fileUrl = route('panitia.file', ['filename' => $fileName]);
                                    } else {
                                        $fileUrl = asset('storage/' . $fileName);
                                    }
                                    break;
                                }
                            }
                        @endphp
                        @if($fileFound)
                            <a href="{{ $fileUrl }}" target="_blank" class="btn btn-primary btn-lihat">
                                <i class="fas fa-eye fa-sm mr-1"></i> Lihat File
                            </a>
                        @else
                            <span class="text-danger">File tidak ditemukan</span>
                        @endif
                    @else
                        <span>{{ $dataKolom->nilai_kolom }}</span>
                    @endif
                @else
                    <span class="text-muted">Tidak diisi</span>
                @endif
            </div>
        @endforeach

        {{-- Tombol Aksi (Tolak & Terima) - Hanya tampil jika status pending --}}
        @if($pendaftaran->status == 'pending')
        <div class="mt-4 text-center">
            {{-- Form untuk tombol Tolak --}}
            <form id="tolakForm" action="{{ route('panitia.peserta.tolak', $pendaftaran->id) }}" method="POST" style="display: inline-block;">
                @csrf
                <input type="hidden" name="alasan_penolakan" id="tolakReasonInput">
                <button type="button" class="btn btn-danger btn-lg mr-2" onclick="confirmTolak()">
                    <i class="fas fa-times mr-1"></i> Tolak
                </button>
            </form>

            {{-- Form untuk tombol Terima --}}
            <form id="terimaForm" action="{{ route('panitia.peserta.terima', $pendaftaran->id) }}" method="POST" style="display: inline-block;">
                @csrf
                <button type="button" class="btn btn-success btn-lg" onclick="confirmTerima()">
                    <i class="fas fa-check mr-1"></i> Terima
                </button>
            </form>
        </div>
        @elseif($pendaftaran->status == 'disetujui')
        <div class="mt-4 text-center">
            <div class="alert alert-success">
                <i class="fas fa-check-circle mr-2"></i>
                <strong>Peserta ini sudah diterima dan masuk kuota acara</strong>
            </div>
        </div>
        @elseif($pendaftaran->status == 'ditolak')
        <div class="mt-4 text-center">
            <div class="alert alert-danger">
                <i class="fas fa-times-circle mr-2"></i>
                <strong>Peserta ini sudah ditolak</strong>
                @if($pendaftaran->alasan_penolakan)
                <br><small>Alasan: {{ $pendaftaran->alasan_penolakan }}</small>
                @endif
            </div>
        </div>
        @endif

    </div> {{-- End Card Body --}}
</div> {{-- End Card --}}

@endsection

@push('scripts')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function confirmTolak() {
    Swal.fire({
        title: 'Tolak Peserta?',
        input: 'textarea',
        inputLabel: 'Alasan Penolakan (Wajib Diisi)',
        inputPlaceholder: 'Masukkan alasan mengapa peserta ini ditolak...',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Tolak!',
        cancelButtonText: 'Tidak',
        inputValidator: (value) => {
            if (!value) {
                return 'Anda harus memasukkan alasan penolakan!'
            }
            if (value.length < 10) {
                return 'Alasan penolakan minimal 10 karakter!'
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
            document.getElementById('tolakReasonInput').value = result.value;
            
            // Tampilkan loading
            Swal.fire({ 
                title: 'Memproses...', 
                allowOutsideClick: false, 
                didOpen: () => { 
                    Swal.showLoading() 
                } 
            });
            
            // Submit form
            document.getElementById('tolakForm').submit();
        }
    });
}

// Show modal tolak peserta
function showTolakForm() {
    document.getElementById('alasan_penolakan').value = '';
    $('#modalTolakPeserta').modal('show');
}

function confirmTerima() {
    Swal.fire({
        title: 'Konfirmasi Penerimaan',
        text: 'Apakah Anda yakin ingin menerima peserta ini? Peserta akan masuk ke kuota acara.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-check"></i> Ya, Terima',
        cancelButtonText: '<i class="fas fa-arrow-left"></i> Batal',
        reverseButtons: true,
        customClass: {
            confirmButton: 'btn btn-success btn-lg mx-2',
            cancelButton: 'btn btn-secondary btn-lg mx-2'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            // Tampilkan loading
            Swal.fire({
                title: 'Memproses...',
                text: 'Sedang menerima peserta',
                icon: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading()
                }
            });
            
            // Submit form
            document.getElementById('terimaForm').submit();
        }
    });
}
</script>
@endpush