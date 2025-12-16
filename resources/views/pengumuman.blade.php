@extends('layouts.peserta.app')

@push('styles')
    <style>
        .pengumuman-card {
            border-radius: 10px;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
    </style>
@endpush

@section('content')
    <!-- Back Button Section -->
    <div class="container-fluid py-3">
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
                        @if($pengumumanList->count() > 0)
                            @foreach($pengumumanList as $pengumuman)
                                <div class="card shadow border-0 rounded-lg mb-4">
                                    <div class="card-header bg-primary text-white">
                                        <h4 class="mb-0 text-white"><i class="fas fa-bullhorn mr-2 text-white"></i>Pengumuman</h4>
                                    </div>
                                    
                                    <div class="card-body p-4">
                                        <div style="line-height: 1.8; font-size: 16px; color: #333; white-space: pre-wrap;">{{ $pengumuman->isi }}</div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="card shadow border-0 rounded-lg">
                                <div class="card-header bg-primary text-white">
                                    <h4 class="mb-0 text-white"><i class="fas fa-bullhorn mr-2 text-white"></i>Pengumuman</h4>
                                </div>
                                
                                <div class="card-body text-center py-5">
                                    <div class="mb-4">
                                        <i class="fas fa-inbox text-muted" style="font-size: 4rem;"></i>
                                    </div>
                                    <h5 class="text-muted mb-3">Belum Ada Pengumuman</h5>
                                    <p class="text-muted">Pengumuman akan muncul di sini setelah panitia mempublikasikannya.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops!',
                text: '{{ session('error') }}',
                confirmButtonText: 'OK'
            });
        </script>
    @endif
@endpush