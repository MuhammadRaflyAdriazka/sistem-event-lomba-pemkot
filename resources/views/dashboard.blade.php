@extends('layouts.peserta.app')

@push('styles')
<style>
    html { scroll-behavior: smooth; }
    .event-card { position: relative; transition: all 0.3s ease; }
    .event-card.hidden { display: none !important; }
    .event-image-wrapper { width: 100%; height: 250px; overflow: hidden; position: relative; }
    .event-image { width: 100%; height: 100%; object-fit: cover; object-position: center; }
    .badge-lg { font-size: 0.8rem; padding: 0.5rem 0.75rem; }
    .border-left-primary { border-left: 4px solid #007bff !important; }
    .text-sm { font-size: 0.875rem; }
</style>
@endpush

@section('content')

<div class="jumbotron jumbotron-fluid position-relative overlay-bottom" 
     style="margin-bottom: 90px; background: url('{{ asset('templatepeserta/img/foto-walikota.jpg') }}') center top 16% / cover no-repeat; height: 700px;">
</div>
<div id="event-section" class="container-fluid py-5"> 
    <div class="container py-5">
        <div class="text-center mb-5">
            <h5 class="text-primary text-uppercase mb-3" style="letter-spacing: 5px;">Event & Lomba Tersedia</h5>
            <h1>Daftar Event & Lomba</h1>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-3"><i class="fas fa-filter mr-2"></i>Filter Event & Lomba</h5>
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <label class="font-weight-bold">Jenis:</label>
                                <select class="form-control" id="categoryFilter">
                                    <option value="">Semua</option>
                                    <option value="Event">Event</option>
                                    <option value="Lomba">Lomba</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="font-weight-bold">Kategori:</label>
                                <select class="form-control" id="eventCategoryFilter">
                                    <option value="">Semua Kategori</option>
                                    <option value="Olahraga">Olahraga</option>
                                    <option value="Kesenian & Budaya">Kesenian & Budaya</option>
                                    <option value="Pendidikan">Pendidikan</option>
                                    <option value="Kuliner">Kuliner</option>
                                    <option value="Teknologi">Teknologi</option>
                                    <option value="Kesehatan">Kesehatan</option>
                                    <option value="Lingkungan">Lingkungan</option>
                                    <option value="Sosial">Sosial</option>
                                    <option value="Ekonomi">Ekonomi</option>
                                    <option value="Pariwisata">Pariwisata</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="font-weight-bold">Sistem Pendaftaran:</label>
                                <select class="form-control" id="registrationSystemFilter">
                                    <option value="">Semua Sistem</option>
                                    <option value="Seleksi">Seleksi</option>
                                    <option value="Tanpa Seleksi">Tanpa Seleksi</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" id="event-container">            
            @if($acaraTersedia && $acaraTersedia->count() > 0)
                @foreach($acaraTersedia as $acara)
                <div class="col-lg-6 mb-4 event-item event-card" 
                     data-category="{{ $acara->kategori }}" 
                     data-event-category="{{ $acara->kategori_acara }}"
                     data-registration-system="{{ $acara->sistem_pendaftaran }}">
                    <div class="card shadow-lg border-0 rounded-lg overflow-hidden h-100">
                        <div class="event-image-wrapper">
                            <img src="{{ asset('images/events/' . $acara->gambar) }}" 
                                 alt="{{ $acara->judul }}" 
                                 class="event-image"
                                 onerror="this.src='{{ asset('templatepeserta/img/eror.jpeg') }}'">
                            <div class="position-absolute" style="top: 10px; left: 10px;">
                                <span class="badge {{ $acara->kategori == 'Event' ? 'badge-primary' : 'badge-success' }} badge-lg">{{ $acara->kategori }}</span>
                            </div>
                            <div class="position-absolute" style="top: 10px; right: 10px;">
                                <span class="badge badge-info badge-lg">{{ $acara->kategori_acara }}</span>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <h1 class="card-title mb-3">{{ $acara->judul }}</h1>
                            <p><i class="fa fa-calendar"></i> Tanggal Pelaksanaan: {{ \Carbon\Carbon::parse($acara->tanggal_acara)->locale('id')->isoFormat('D MMMM Y') }}</p>
                            <p><i class="fa fa-map-marker-alt"></i> Lokasi: {{ $acara->lokasi }}</p>
                            
                            <h5 class="mt-4">Biaya Pendaftaran</h5>
                            <p><i class=""></i> {{ $acara->biaya }}</p>
                            
                            <h5 class="mt-4">Syarat Pendaftaran</h5>
                            <p>{{ Str::limit($acara->persyaratan, 120, '...') }}</p>

                            <h5 class="mt-4">Kuota</h5>
                            <p class="text-muted">{{ number_format($acara->kuota) }} peserta</p>

                            <h5 class="mt-4">Sistem Pendaftaran</h5>
                            <p class="text-muted">{{ $acara->sistem_pendaftaran }}</p>
                            
                            <h5 class="mt-4">Periode Registrasi</h5>
                            <p>{{ \Carbon\Carbon::parse($acara->tanggal_mulai_daftar)->locale('id')->isoFormat('D MMMM') }} – {{ \Carbon\Carbon::parse($acara->tanggal_akhir_daftar)->locale('id')->isoFormat('D MMMM Y') }}</p>
                            
                            <h5 class="mt-4">Hadiah</h5>
                            <p>{{ Str::limit($acara->hadiah, 100, '...') }}</p>
                            
                            <div class="mt-3">
                                @php
                                    $pendaftaranAcara = $pendaftaranUser->where('id_acara', $acara->id)->first();
                                @endphp
                                
                                @if($pendaftaranAcara)
                                    {{-- Jika sudah mendaftar, hanya tampil status saja (tanpa tombol detail) --}}
                                    @if($pendaftaranAcara->status == 'pending')
                                        <span class="btn btn-warning disabled"><i class="fas fa-clock"></i> Menunggu Persetujuan</span>
                                    @elseif($pendaftaranAcara->status == 'disetujui')
                                        <span class="btn btn-success disabled"><i class="fas fa-check"></i> Sudah Terdaftar</span>
                                    @elseif($pendaftaranAcara->status == 'ditolak')
                                        <span class="btn btn-danger disabled"><i class="fas fa-times"></i> Ditolak</span>
                                    @elseif($pendaftaranAcara->status == 'mengundurkan_diri')
                                        <span class="btn btn-secondary disabled"><i class="fas fa-sign-out-alt"></i> Mengundurkan Diri</span>
                                    @else
                                        <span class="btn btn-info disabled"><i class="fas fa-info"></i> {{ ucfirst($pendaftaranAcara->status) }}</span>
                                    @endif
                                @else
                                    {{-- Jika belum mendaftar, tampil tombol detail dan daftar --}}
                                    <a href="{{ route('acara.show', $acara->id) }}" class="btn btn-outline-primary">Lihat Detail</a>
                                    @if(now()->between($acara->tanggal_mulai_daftar, $acara->tanggal_akhir_daftar))
                                        <a href="/pendaftaran/{{ $acara->id }}" class="btn btn-primary ml-2"><i class="fas fa-plus"></i> Daftar Sekarang</a>
                                    @else
                                        <span class="btn btn-secondary disabled ml-2"><i class="fas fa-times"></i> Pendaftaran Tutup</span>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
            <div class="col-12">
                <div class="text-center">
                    <div class="card shadow-lg border-0 rounded-lg">
                        <div class="card-body p-5">
                            <i class="fas fa-calendar-times fa-5x text-muted mb-4"></i>
                            <h3 class="text-muted">Belum Ada Event Tersedia</h3>
                            <p class="text-muted">Event dan lomba akan segera hadir. Pantau terus halaman ini untuk update terbaru!</p>
                            <a href="/" class="btn btn-primary mt-3">Kembali ke Beranda</a>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="row" id="noResultsMessage" style="display: none;">
            <div class="col-12">
                <div class="alert alert-warning text-center">
                    <h4><i class="fas fa-search mr-2"></i>Tidak Ada Hasil</h4>
                    <p>Tidak ada event yang sesuai dengan filter yang dipilih.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            function filterEvents() {
                const categoryFilter = $('#categoryFilter').val();
                const eventCategoryFilter = $('#eventCategoryFilter').val();
                const registrationSystemFilter = $('#registrationSystemFilter').val();
                
                let visibleCount = 0;
                
                $('.event-card').each(function() {
                    const card = $(this);
                    const showCard = 
                        (categoryFilter === "" || card.data('category') === categoryFilter) &&
                        (eventCategoryFilter === "" || card.data('event-category') === eventCategoryFilter) &&
                        (registrationSystemFilter === "" || card.data('registration-system') === registrationSystemFilter);

                    if (showCard) {
                        card.removeClass('hidden').show();
                        visibleCount++;
                    } else {
                        card.addClass('hidden').hide();
                    }
                });
                
                $('#noResultsMessage').toggle(visibleCount === 0);
            }
            
            $('#categoryFilter, #eventCategoryFilter, #registrationSystemFilter').on('change', filterEvents);
        });


    </script>
@endpush