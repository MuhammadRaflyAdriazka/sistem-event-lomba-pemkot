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
    <h1 class="h3 mb-0 text-gray-800">Kelola Daftar Peserta</h1>
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

{{-- Tabel Daftar Peserta --}}
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Peserta Menunggu Seleksi</h6>
        <div>
            @if($peserta->count() > 0)
            <button type="button" class="btn btn-warning btn-sm mr-2" onclick="tolakMassalKuotaPenuh()">
                <i class="fas fa-users-slash"></i> Tolak Semua Sisa Peserta
            </button>
            @endif
            <a href="{{ route('panitia.peserta.diterima') }}" class="btn btn-success btn-sm mr-2">
                <i class="fas fa-check-circle"></i> Lihat Peserta Diterima
            </a>
            <a href="{{ route('panitia.peserta.ditolak') }}" class="btn btn-danger btn-sm">
                <i class="fas fa-times-circle"></i> Lihat Peserta Ditolak
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-2">
                <p><strong>Total Pendaftar:</strong> {{ $totalPendaftar }}</p>
            </div>
            <div class="col-md-2">
                <p><strong>Menunggu Seleksi:</strong> {{ $peserta->count() }}</p>
            </div>
            <div class="col-md-2">
                <p><strong>Diterima:</strong> <span class="text-success">{{ $jumlahDiterima }}</span></p>
            </div>
            <div class="col-md-2">
                <p><strong>Ditolak:</strong> <span class="text-danger">{{ $jumlahDitolak }}</span></p>
            </div>
            <div class="col-md-2">
                <p><strong>Mengundurkan Diri:</strong> <span class="text-secondary">{{ $jumlahMengundurkanDiri }}</span></p>
            </div>
            <div class="col-md-2">
                <p><strong>Kuota Tersisa:</strong> {{ $acara->kuota - $jumlahDiterima }}</p>
            </div>
        </div>

        {{-- Tombol Tolak Semua Sisa jika kuota penuh --}}
        @if($jumlahDiterima >= $acara->kuota && $peserta->count() > 0)
        <div class="alert alert-warning d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <strong>Kuota sudah penuh!</strong> Masih ada {{ $peserta->count() }} peserta yang menunggu seleksi.
            </div>
            <button type="button" class="btn btn-danger" onclick="tolakSemuaSisa()">
                <i class="fas fa-times-circle mr-1"></i> Tolak Semua Sisa
            </button>
        </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="dataTablePeserta" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>No.HP</th>
                        <th>Alamat</th>
                        <th width="120">Aksi</th>
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

{{-- Modal Tolak Individual --}}
<div class="modal fade" id="modalTolakIndividual" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tolak Peserta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formTolakIndividual" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Anda akan menolak peserta: <strong id="namaPesertaTolak"></strong></p>
                    <div class="form-group">
                        <label for="alasan_penolakan">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="alasan_penolakan" name="alasan_penolakan" rows="3" required
                                  placeholder="Tuliskan alasan penolakan (minimal 5 karakter)"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times"></i> Tolak Peserta
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Form tersembunyi untuk terima peserta --}}
<form id="formTerimaPeserta" method="POST" style="display: none;">
    @csrf
</form>

@endsection

@push('scripts')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Terima peserta
function terimaPeserta(pendaftaranId, namaPeserta) {
    if (confirm(`Apakah Anda yakin ingin menerima peserta "${namaPeserta}"?`)) {
        const form = document.getElementById('formTerimaPeserta');
        form.action = `/panitia/peserta/${pendaftaranId}/terima`;
        form.submit();
    }
}

// Show modal tolak individual
function showTolakForm(pendaftaranId, namaPeserta) {
    document.getElementById('namaPesertaTolak').textContent = namaPeserta;
    document.getElementById('formTolakIndividual').action = `/panitia/peserta/${pendaftaranId}/tolak`;
    document.getElementById('alasan_penolakan').value = '';
    $('#modalTolakIndividual').modal('show');
}

// Tolak massal dengan alasan "Kuota sudah terpenuhi"
function tolakMassalKuotaPenuh() {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Tolak Semua Peserta?',
            text: 'Semua peserta yang tersisa akan ditolak dengan alasan "Maaf, kuota sudah terpenuhi"',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Tolak Semua!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                prosesTolakMassal();
            }
        });
    } else {
        if (confirm('Apakah Anda yakin ingin menolak semua peserta yang tersisa dengan alasan "Maaf, kuota sudah terpenuhi"?')) {
            prosesTolakMassal();
        }
    }
}

function prosesTolakMassal() {
    // Buat form tersembunyi untuk submit
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("panitia.peserta.tolakMassalKuotaPenuh") }}';
    
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    
    form.appendChild(csrfToken);
    document.body.appendChild(form);
    form.submit();
}

// Tolak semua sisa (fungsi lama untuk kuota penuh)
function tolakSemuaSisa() {
    Swal.fire({
        title: 'Tolak Semua Peserta Sisa?',
        html: `
            <p>Anda akan menolak <strong>{{ $peserta->count() }} peserta</strong> yang masih menunggu seleksi.</p>
            <p class="text-muted">Alasan: Kuota sudah penuh</p>
            <p class="text-danger"><strong>Tindakan ini tidak dapat dibatalkan!</strong></p>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-times-circle"></i> Ya, Tolak Semua',
        cancelButtonText: '<i class="fas fa-arrow-left"></i> Batal',
        reverseButtons: true,
        customClass: {
            confirmButton: 'btn btn-danger btn-lg mx-2',
            cancelButton: 'btn btn-secondary btn-lg mx-2'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            // Tampilkan loading
            Swal.fire({
                title: 'Memproses...',
                text: 'Sedang menolak peserta',
                icon: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading()
                }
            });
            
            // Buat form tersembunyi untuk submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("panitia.peserta.tolakMassalKuotaPenuh") }}';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            
            form.appendChild(csrfToken);
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>
@endpush