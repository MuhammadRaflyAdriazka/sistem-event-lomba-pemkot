@extends('layouts.panitia.app')

@section('title', 'Buat Pengumuman')

@section('content')

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
        <i class="fas fa-bullhorn text-warning mr-2"></i>Buat Pengumuman
    </h1>
    <a href="{{ route('panitia.dashboard') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali ke Dashboard
    </a>
</div>

<!-- Form Buat Pengumuman -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-success">
            <i class="fas fa-plus mr-2"></i>@if($pengumumanList->count() > 0) Edit @else Buat @endif Pengumuman
        </h6>
    </div>
    <div class="card-body">
        @if($pengumumanList->count() > 0)
            @php $pengumuman = $pengumumanList->first(); @endphp
            <form action="{{ route('panitia.pengumuman.store') }}" method="POST" id="updateForm">
                @csrf
                <input type="hidden" name="update_mode" value="1">
                
                <div class="form-group">
                    <label for="isi" class="font-weight-bold">Isi Pengumuman</label>
                    <textarea class="form-control @error('isi') is-invalid @enderror" 
                              id="isi" name="isi" rows="8" 
                              placeholder="Tulis isi pengumuman di sini..." required>{{ old('isi', $pengumuman->isi) }}</textarea>
                    @error('isi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">
                        Pengumuman ini akan dikirim ke semua peserta yang diterima pada event ini.
                    </small>
                </div>

                <div class="form-group mb-0">
                    <button type="button" onclick="confirmUpdate()" class="btn btn-warning mr-2" id="updateBtn">
                        <i class="fas fa-edit mr-1"></i>Update Pengumuman
                    </button>
                    <small class="text-muted d-block mt-2">Klik tombol di atas untuk memperbarui pengumuman</small>
                </div>
            </form>
        @else
            <form action="{{ route('panitia.pengumuman.store') }}" method="POST" id="createForm">
                @csrf
                
                <div class="form-group">
                    <label for="isi" class="font-weight-bold">Isi Pengumuman</label>
                    <textarea class="form-control @error('isi') is-invalid @enderror" 
                              id="isi" name="isi" rows="8" 
                              placeholder="Tulis isi pengumuman di sini..." required>{{ old('isi') }}</textarea>
                    @error('isi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">
                        Pengumuman ini akan dikirim ke semua peserta yang diterima pada event ini.
                    </small>
                </div>

                <div class="form-group mb-0">
                    <button type="button" onclick="confirmCreate()" class="btn btn-success mr-2" id="createBtn">
                        <i class="fas fa-paper-plane mr-1"></i>Publikasikan Pengumuman
                    </button>
                    <small class="text-muted d-block mt-2">Klik tombol di atas untuk mempublikasikan pengumuman</small>
                </div>
            </form>
        @endif
    </div>
</div>

@endsection

@push('scripts')
<script>
// Debug: Pastikan SweetAlert dimuat
console.log('SweetAlert2 loaded:', typeof Swal !== 'undefined');

function confirmUpdate() {
    console.log('confirmUpdate() called');
    
    if (typeof Swal === 'undefined') {
        alert('SweetAlert2 tidak dimuat. Menggunakan konfirmasi standar.');
        if (confirm('Apakah Anda yakin ingin memperbarui pengumuman ini?')) {
            document.getElementById('updateForm').submit();
        }
        return;
    }
    
    Swal.fire({
        title: 'Update Pengumuman?',
        text: 'Apakah Anda yakin ingin memperbarui pengumuman ini?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#ffc107',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Update!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            console.log('Submitting update form');
            document.getElementById('updateForm').submit();
        }
    });
}

function confirmCreate() {
    console.log('confirmCreate() called');
    
    if (typeof Swal === 'undefined') {
        alert('SweetAlert2 tidak dimuat. Menggunakan konfirmasi standar.');
        if (confirm('Apakah Anda yakin ingin mempublikasikan pengumuman ini?')) {
            document.getElementById('createForm').submit();
        }
        return;
    }
    
    Swal.fire({
        title: 'Publikasikan Pengumuman?',
        text: 'Apakah Anda yakin ingin mempublikasikan pengumuman ini?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Publikasikan!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            console.log('Submitting create form');
            document.getElementById('createForm').submit();
        }
    });
}

// Tambahan event listeners sebagai backup
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded');
    
    const updateBtn = document.getElementById('updateBtn');
    const createBtn = document.getElementById('createBtn');
    
    if (updateBtn) {
        console.log('Update button found');
        updateBtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Update button clicked via event listener');
            confirmUpdate();
        });
    }
    
    if (createBtn) {
        console.log('Create button found');
        createBtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Create button clicked via event listener');
            confirmCreate();
        });
    }
});

@if(session('success'))
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonText: 'OK',
                timer: 3000
            });
        } else {
            alert('Berhasil: {{ session('success') }}');
        }
    });
@endif

@if(session('error'))
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('error') }}',
                confirmButtonText: 'OK'
            });
        } else {
            alert('Error: {{ session('error') }}');
        }
    });
@endif
</script>
@endpush

@push('styles')
<style>
.border-left-info {
    border-left: 4px solid #36b9cc !important;
}

.form-control:focus {
    border-color: #36b9cc;
    box-shadow: 0 0 0 0.2rem rgba(54, 185, 204, 0.25);
}

.btn:focus {
    box-shadow: none;
}

.card {
    border-radius: 10px;
}

.form-control {
    border-radius: 8px;
}

.btn {
    border-radius: 8px;
    cursor: pointer !important;
    pointer-events: auto !important;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.btn:active {
    transform: translateY(0);
}

/* Pastikan tombol bisa diklik */
button[type="button"] {
    pointer-events: auto !important;
    cursor: pointer !important;
}

#updateBtn, #createBtn {
    pointer-events: auto !important;
    cursor: pointer !important;
    position: relative;
    z-index: 10;
}
</style>
@endpush
