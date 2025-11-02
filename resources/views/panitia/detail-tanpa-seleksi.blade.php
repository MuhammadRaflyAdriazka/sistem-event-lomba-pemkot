@extends('layouts.panitia.app')

{{-- Mengatur judul halaman --}}
@section('title', 'Detail Peserta (Tanpa Seleksi)')

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
    <h1 class="h3 mb-0 text-gray-800">Detail Peserta (Tanpa Seleksi)</h1>
    <a href="{{ route('panitia.peserta.tanpaSeleksi') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali ke Daftar Peserta
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

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Detail Peserta</h6>
    </div>
    <div class="card-body">

        {{-- Semua data dari formulir pendaftaran --}}
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

        {{-- Status dan Aksi Peserta --}}
        @if($pendaftaran->status == 'disetujui')
        <div class="mt-4">
            <div class="alert alert-success">
                <i class="fas fa-check-circle mr-2"></i>
                <strong>Peserta ini DITERIMA OTOMATIS (Sistem Tanpa Seleksi)</strong>
                <br><small>Diterima pada: {{ $pendaftaran->created_at->format('d M Y, H:i') }}</small>
            </div>
            
            {{-- Tombol Batalkan Penerimaan --}}
            <div class="text-center">
                <form id="cancelForm" action="{{ route('panitia.peserta.batalkanTanpaSeleksi', $pendaftaran->id) }}" method="POST" style="display: inline-block;">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="alasan_pembatalan" id="cancelReasonInput">
                    <button type="button" class="btn btn-warning btn-lg" onclick="confirmCancel()">
                        <i class="fas fa-undo mr-1"></i> Batalkan Penerimaan
                    </button>
                </form>
            </div>
        </div>
        @elseif($pendaftaran->status == 'ditolak')
        <div class="mt-4">
            <div class="alert alert-danger">
                <i class="fas fa-times-circle mr-2"></i>
                <strong>Penerimaan peserta ini sudah dibatalkan</strong>
                @if($pendaftaran->alasan_penolakan)
                <br><small>Alasan: {{ $pendaftaran->alasan_penolakan }}</small>
                @endif
            </div>
        </div>
        @endif

    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function confirmCancel() {
    Swal.fire({
        title: 'Batalkan Penerimaan Peserta?',
        input: 'textarea',
        inputLabel: 'Alasan Pembatalan (Wajib Diisi)',
        inputPlaceholder: 'Masukkan alasan mengapa penerimaan peserta ini dibatalkan...',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Batalkan!',
        cancelButtonText: 'Tidak',
        inputValidator: (value) => {
            if (!value) {
                return 'Anda harus memasukkan alasan pembatalan!'
            }
            if (value.length < 10) {
                return 'Alasan pembatalan minimal 10 karakter!'
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
            document.getElementById('cancelReasonInput').value = result.value;
            
            // Tampilkan loading
            Swal.fire({ 
                title: 'Memproses...', 
                allowOutsideClick: false, 
                didOpen: () => { 
                    Swal.showLoading() 
                } 
            });
            
            // Submit form
            document.getElementById('cancelForm').submit();
        }
    });
}
</script>
@endpush