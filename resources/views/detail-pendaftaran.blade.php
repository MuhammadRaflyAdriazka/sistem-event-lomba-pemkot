@extends('layouts.peserta.app')

@section('content')
        <!-- Back Button Section -->
        <div class="container-fluid py-3 ">
            <div class="container-fluid px-4">
                <a href="{{ route('acara') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar Acara
                </a>
            </div>
        </div>

        <div class="container-fluid py-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <!-- Status Pendaftaran Card -->
                        <div class="card shadow border-0 rounded-lg">
                            <div class="card-header bg-primary text-white">
                                <h4 class="mb-0 text-white"><i class="fas fa-clipboard-check mr-2 text-white"></i>Status Pendaftaran</h4>
                            </div>
                            <div class="card-body p-4">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="text-uppercase font-weight-bold text-muted mb-3">STATUS SAAT INI</h6>
                                        @if($pendaftaran->status == 'pending')
                                            <div class="alert alert-warning mb-0">
                                                <i class="fas fa-clock mr-2"></i>
                                                <strong>PENDING</strong> - Sedang Diproses
                                                <p class="mt-2 mb-0 small">
                                                    Pendaftaran Anda sedang dalam proses review oleh panitia. Mohon tunggu pengumuman selanjutnya.
                                                </p>
                                            </div>
                                        @elseif($pendaftaran->status == 'diterima')
                                            <div class="alert alert-success mb-0">
                                                <i class="fas fa-check-circle mr-2"></i>
                                                <strong>DITERIMA</strong> - Selamat!
                                                <p class="mt-2 mb-0 small">
                                                    Pendaftaran Anda telah diterima. Silakan persiapkan diri untuk mengikuti acara ini.
                                                </p>
                                            </div>
                                        @elseif($pendaftaran->status == 'ditolak')
                                            <div class="alert alert-danger mb-0">
                                                <i class="fas fa-times-circle mr-2"></i>
                                                <strong>DITOLAK</strong>
                                                <p class="mt-2 mb-0 small">
                                                    Maaf, pendaftaran Anda tidak dapat diterima.
                                                </p>
                                            </div>
                                        @elseif($pendaftaran->status == 'mengundurkan_diri')
                                            <div class="alert alert-secondary mb-0">
                                                <i class="fas fa-sign-out-alt mr-2"></i>
                                                <strong>MENGUNDURKAN DIRI</strong>
                                                <p class="mt-2 mb-0 small">
                                                    Anda telah mengundurkan diri dari acara ini.
                                                </p>
                                            </div>
                                        @else
                                            <div class="alert alert-info mb-0">
                                                <i class="fas fa-info-circle mr-2"></i>
                                                <strong>{{ strtoupper($pendaftaran->status) }}</strong>
                                                <p class="mt-2 mb-0 small">
                                                    Status: {{ ucfirst($pendaftaran->status) }}
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <h6 class="text-uppercase font-weight-bold text-muted mb-3">INFORMASI PENDAFTARAN</h6>
                                        <div class="mb-3">
                                            @if($pendaftaran->status == 'mengundurkan_diri')
                                                <small class="text-muted">Tanggal Mengundurkan Diri:</small><br>
                                                <strong>{{ \Carbon\Carbon::parse($pendaftaran->updated_at)->locale('id')->isoFormat('dddd, D MMMM Y HH:mm') }}</strong>
                                            @else
                                                <small class="text-muted">Tanggal Daftar:</small><br>
                                                <strong>{{ \Carbon\Carbon::parse($pendaftaran->created_at)->locale('id')->isoFormat('dddd, D MMMM Y HH:mm') }}</strong>
                                            @endif
                                        </div>
                                        <div class="mb-0">
                                            <small class="text-muted">Sistem Pendaftaran:</small><br>
                                            <span class="badge badge-info">{{ $acara->sistem_pendaftaran }}</span>
                                        </div>
                                    </div>
                                </div>

                                @if($pendaftaran->status == 'ditolak' && $pendaftaran->alasan_penolakan)
                                <div class="mt-4">
                                    <h6 class="text-uppercase font-weight-bold text-muted mb-2">Alasan Penolakan</h6>
                                    <div class="alert alert-light border-left-danger">
                                        {{ $pendaftaran->alasan_penolakan }}
                                    </div>
                                </div>
                                @endif

                                {{-- Tombol Mengundurkan Diri --}}
                                @php
                                    $sekarang = now();
                                    $periodePendaftaranMasihBuka = $sekarang->between($acara->tanggal_mulai_daftar, $acara->tanggal_akhir_daftar);
                                    $bisaMengundurkanDiri = $acara->status == 'active' && 
                                                           $periodePendaftaranMasihBuka && 
                                                           ($pendaftaran->status == 'pending' || $pendaftaran->status == 'diterima');
                                @endphp

                                @if($bisaMengundurkanDiri)
                                <div class="mt-4 text-center">
                                    <button class="btn btn-outline-danger" onclick="confirmWithdraw()">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Mengundurkan Diri
                                    </button>
                                    <br>
                                    <small class="text-muted mt-2 d-block">
                                        Anda dapat mengundurkan diri selama periode pendaftaran masih dibuka
                                    </small>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

@endsection

@push('scripts')
    <!-- SweetAlert2 for confirmation -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Hidden form untuk mengundurkan diri --}}
    @if(isset($bisaMengundurkanDiri) && $bisaMengundurkanDiri)
    <form id="withdrawForm" action="{{ route('acara.mengundurkanDiri', $pendaftaran->id) }}" method="POST" style="display: none;">
        @csrf
    </form>

    <script>
        function confirmWithdraw() {
            Swal.fire({
                title: 'Yakin Mengundurkan Diri?',
                text: 'Anda akan kehilangan slot dan tidak bisa mendaftar lagi untuk acara ini!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Mengundurkan Diri',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Memproses...',
                        text: 'Mohon tunggu sebentar',
                        icon: 'info',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        willOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Submit form via AJAX
                    const form = document.getElementById('withdrawForm');
                    const formData = new FormData(form);

                    fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: data.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                // Refresh halaman untuk update status
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Gagal!',
                                text: data.message,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Error!',
                            text: 'Terjadi kesalahan saat memproses permintaan',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    });
                }
            });
        }
    </script>
    @endif
@endpush