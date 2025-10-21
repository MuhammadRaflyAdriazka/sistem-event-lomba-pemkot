@extends('layouts.admin.app')

{{-- Mengatur judul halaman yang akan tampil di top bar --}}
@section('title', 'Edit Event')

{{-- Menyisipkan CSS khusus untuk halaman ini ke dalam <head> --}}
@push('styles')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* CSS khusus untuk halaman ini */
        .form-builder-field {
            border: 2px solid #e3e6f0;
            background: #fff;
        }
        .form-builder-field:hover {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.1);
        }
        .default-field {
            border-color: #28a745;
            background-color: #f8fff9;
        }
        .default-field .card-header {
            background-color: #28a745 !important;
            color: white;
        }
        .field-preview {
            background-color: #f8f9fc;
            border-left: 4px solid #4e73df;
            padding: 15px;
            margin-top: 15px;
            border-radius: 5px;
        }
        .drag-handle {
            cursor: move;
            color: #6c757d;
        }
        .drag-handle:hover {
            color: #495057;
        }
        .step-content {
            display: none;
        }
        .step-content.active {
            display: block;
        }
        .step-progress {
            margin-bottom: 30px;
        }
        .step-progress .progress {
            height: 8px;
            border-radius: 10px;
        }
        .step-progress .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .step-progress .step-item {
            text-align: center;
            flex: 1;
            position: relative;
        }
        .step-progress .step-item .step-number {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: #e3e6f0;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 8px;
            font-weight: bold;
            font-size: 14px;
        }
        .step-progress .step-item.active .step-number {
            background: #4e73df;
            color: white;
        }
        .step-progress .step-item.completed .step-number {
            background: #28a745;
            color: white;
        }
        .step-progress .step-item .step-title {
            font-size: 13px;
            font-weight: 600;
            color: #6c757d;
        }
        .step-progress .step-item.active .step-title {
            color: #4e73df;
        }
        .step-progress .step-item.completed .step-title {
            color: #28a745;
        }
    </style>
@endpush


{{-- Memulai bagian konten utama --}}
@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Edit Event: {{ $acara->judul }}</h1>
    <p class="text-muted">Perbarui informasi event sesuai kebutuhan</p>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong><i class="fas fa-check-circle mr-1"></i>Berhasil!</strong> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong><i class="fas fa-exclamation-triangle mr-1"></i>Oops!</strong> Ada beberapa kesalahan:
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="close" data-dismiss="alert">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<div class="step-progress">
    <div class="step-indicator">
        <div class="step-item active" data-step="1">
            <div class="step-number">1</div>
            <div class="step-title">Informasi Event</div>
        </div>
        <div class="step-item" data-step="2">
            <div class="step-number">2</div>
            <div class="step-title">Form Pendaftaran</div>
        </div>
    </div>
    <div class="progress">
        <div class="progress-bar bg-primary" role="progressbar" style="width: 50%" id="progressBar"></div>
    </div>
</div>

<div class="col-lg-12">
    <form id="editEventForm" action="{{ route('admin.event.update', $acara->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        {{-- STEP 1: INFORMASI EVENT --}}
        <div class="step-content active" id="step1">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-calendar-plus mr-2"></i>Informasi Dasar Event
                    </h6>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="font-weight-bold text-primary">Nama Event / Lomba</label>
                        <input type="text" name="judul" id="judul" class="form-control @error('judul') is-invalid @enderror" placeholder="Contoh: Event Pasar Wadai" value="{{ old('judul', $acara->judul) }}" required>
                        @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-primary">Tanggal Pelaksanaan</label>
                        <input type="date" name="tanggal_acara" id="tanggal_acara" class="form-control @error('tanggal_acara') is-invalid @enderror" value="{{ old('tanggal_acara', \Carbon\Carbon::parse($acara->tanggal_acara)->format('Y-m-d')) }}" required>
                        @error('tanggal_acara')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-primary">Lokasi</label>
                        <input type="text" name="lokasi" id="lokasi" class="form-control @error('lokasi') is-invalid @enderror" placeholder="Contoh: Lapangan Pemko Banjarmasin" value="{{ old('lokasi', $acara->lokasi) }}" required>
                        @error('lokasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-primary">Biaya Pendaftaran</label>
                        <input type="text" name="biaya" id="biaya" class="form-control" value="{{ old('biaya', $acara->biaya) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-primary">Kategori Event/Lomba</label>
                        <select name="kategori" id="kategori" class="form-control @error('kategori') is-invalid @enderror" required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="Event" {{ old('kategori', $acara->kategori) == 'Event' ? 'selected' : '' }}>Event</option>
                            <option value="Lomba" {{ old('kategori', $acara->kategori) == 'Lomba' ? 'selected' : '' }}>Lomba</option>
                        </select>
                        @error('kategori')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-primary">Sistem Pendaftaran</label>
                        <select name="sistem_pendaftaran" id="sistem_pendaftaran" class="form-control @error('sistem_pendaftaran') is-invalid @enderror" required>
                            <option value="">-- Pilih Sistem --</option>
                            <option value="Seleksi" {{ old('sistem_pendaftaran', $acara->sistem_pendaftaran) == 'Seleksi' ? 'selected' : '' }}>Seleksi (Ada Kuota)</option>
                            <option value="Tanpa Seleksi" {{ old('sistem_pendaftaran', $acara->sistem_pendaftaran) == 'Tanpa Seleksi' ? 'selected' : '' }}>Tanpa Seleksi (Ada Kuota)</option>
                        </select>
                        @error('sistem_pendaftaran')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-primary">Kuota Peserta</label>
                        <input type="number" name="kuota" id="kuota" class="form-control @error('kuota') is-invalid @enderror" placeholder="Contoh: 100" value="{{ old('kuota', $acara->kuota) }}" min="1" required>
                        @error('kuota')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-primary">Kategori Acara</label>
                        <select name="kategori_acara" id="kategori_acara" class="form-control @error('kategori_acara') is-invalid @enderror" required>
                            <option value="">-- Pilih Kategori Acara --</option>
                            <option value="Olahraga" {{ old('kategori_acara', $acara->kategori_acara) == 'Olahraga' ? 'selected' : '' }}>Olahraga</option>
                            <option value="Kesenian & Budaya" {{ old('kategori_acara', $acara->kategori_acara) == 'Kesenian & Budaya' ? 'selected' : '' }}>Kesenian & Budaya</option>
                            <option value="Pendidikan" {{ old('kategori_acara', $acara->kategori_acara) == 'Pendidikan' ? 'selected' : '' }}>Pendidikan</option>
                            <option value="Kuliner" {{ old('kategori_acara', $acara->kategori_acara) == 'Kuliner' ? 'selected' : '' }}>Kuliner</option>
                            <option value="Teknologi" {{ old('kategori_acara', $acara->kategori_acara) == 'Teknologi' ? 'selected' : '' }}>Teknologi</option>
                            <option value="Kesehatan" {{ old('kategori_acara', $acara->kategori_acara) == 'Kesehatan' ? 'selected' : '' }}>Kesehatan</option>
                            <option value="Lingkungan" {{ old('kategori_acara', $acara->kategori_acara) == 'Lingkungan' ? 'selected' : '' }}>Lingkungan</option>
                            <option value="Sosial" {{ old('kategori_acara', $acara->kategori_acara) == 'Sosial' ? 'selected' : '' }}>Sosial</option>
                            <option value="Ekonomi" {{ old('kategori_acara', $acara->kategori_acara) == 'Ekonomi' ? 'selected' : '' }}>Ekonomi</option>
                            <option value="Pariwisata" {{ old('kategori_acara', $acara->kategori_acara) == 'Pariwisata' ? 'selected' : '' }}>Pariwisata</option>
                        </select>
                        @error('kategori_acara')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-primary">Syarat Pendaftaran</label>
                        <textarea name="persyaratan" id="persyaratan" class="form-control @error('persyaratan') is-invalid @enderror" rows="4" placeholder="Contoh:&#10;- Peserta minimal usia 10 tahun&#10;- Membawa KTP/Kartu Pelajar" required>{{ old('persyaratan', $acara->persyaratan) }}</textarea>
                        @error('persyaratan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold text-primary">Mulai Registrasi</label>
                                <input type="date" name="tanggal_mulai_daftar" id="tanggal_mulai_daftar" class="form-control @error('tanggal_mulai_daftar') is-invalid @enderror" value="{{ old('tanggal_mulai_daftar', \Carbon\Carbon::parse($acara->tanggal_mulai_daftar)->format('Y-m-d')) }}" required>
                                @error('tanggal_mulai_daftar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold text-primary">Tutup Registrasi</label>
                                <input type="date" name="tanggal_akhir_daftar" id="tanggal_akhir_daftar" class="form-control @error('tanggal_akhir_daftar') is-invalid @enderror" value="{{ old('tanggal_akhir_daftar', \Carbon\Carbon::parse($acara->tanggal_akhir_daftar)->format('Y-m-d')) }}" required>
                                @error('tanggal_akhir_daftar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-primary">Hadiah</label>
                        <textarea name="hadiah" id="hadiah" class="form-control @error('hadiah') is-invalid @enderror" rows="3" placeholder="Contoh:&#10;• Juara 1 : Motor&#10;• Juara 2 : Sepeda Listrik" required>{{ old('hadiah', $acara->hadiah) }}</textarea>
                        @error('hadiah')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-primary">Tentang Acara</label>
                        <textarea name="tentang" id="tentang" class="form-control @error('tentang') is-invalid @enderror" rows="3" placeholder="Contoh: Acara ini diselenggarakan oleh dinas pariwisata..." required>{{ old('tentang', $acara->tentang) }}</textarea>
                        @error('tentang')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-primary">Gambar Event</label>
                        @if($acara->gambar)
                            <div class="mb-2">
                                <img src="{{ asset('images/events/' . $acara->gambar) }}" alt="Current Image" class="img-thumbnail" style="max-height: 200px;">
                                <p class="text-muted mt-1">Gambar saat ini. Upload gambar baru jika ingin mengubah.</p>
                            </div>
                        @endif
                        <input type="file" name="gambar" id="gambar" class="form-control-file @error('gambar') is-invalid @enderror" accept=".jpg,.jpeg,.png">
                        @error('gambar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <small class="form-text text-muted">
                            <strong>Panduan Upload Gambar:</strong><br>
                            • <strong>Format:</strong> JPG, JPEG, PNG<br>
                            • <strong>Ukuran File:</strong> Maksimal 2MB<br>
                            • <strong>Kosongkan jika tidak ingin mengubah gambar</strong>
                        </small>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('admin.event') }}" class="btn btn-secondary btn-lg mr-3">
                        <i class="fas fa-times mr-2"></i>Batal
                    </a>
                    <button type="button" class="btn btn-primary btn-lg" id="nextBtn">
                        Selanjutnya<i class="fas fa-chevron-right ml-2"></i>
                    </button>
                </div>
            </div>
        </div>
        
        {{-- STEP 2: FORM PENDAFTARAN --}}
        <div class="step-content" id="step2">
            <div class="card border-primary mb-4 shadow">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-edit mr-2"></i>Form Pendaftaran Peserta
                    </h6>
                    <small>Kelola field form yang tersedia untuk pendaftaran peserta</small>
                </div>
                <div class="card-body">
                    <div class="alert alert-success">
                        <i class="fas fa-check mr-2"></i>
                        <strong>Field Wajib:</strong> Nama, No HP, Email, dan Alamat tidak dapat dihapus.
                        <br><strong>Field Custom:</strong> Anda bisa menghapus atau menambah field custom sesuai kebutuhan.
                    </div>
                    <div id="formFieldsContainer">
                        {{-- Field akan ditampilkan di sini --}}
                    </div>
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="font-weight-bold text-dark"><i class="fas fa-plus mr-2"></i>Tambah Field Berkas/Dokumen:</h6>
                            <button type="button" class="btn btn-outline-success mr-2 mb-2 quick-add" data-type="file" data-name="foto_ktp" data-label="Foto KTP" data-placeholder="Format: JPG, PNG (Max: 2MB)" data-required="1">
                                <i class="fas fa-id-card mr-1"></i> Foto KTP
                            </button>
                            <button type="button" class="btn btn-outline-dark mr-2 mb-2" id="addCustomFieldBtn">
                                <i class="fas fa-plus mr-1"></i> Field Custom
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <button type="button" class="btn btn-outline-primary btn-lg mr-3" id="prevBtn">
                    <i class="fas fa-chevron-left mr-2"></i>Sebelumnya
                </button>
                <button type="button" class="btn btn-success btn-lg" id="submitBtn">
                    <i class="fas fa-save mr-2"></i>Update Event
                </button>
            </div>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentStep = 1;
    const totalSteps = 2;

    function showStep(step) {
        document.querySelectorAll('.step-content').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.step-item').forEach(el => el.classList.remove('active', 'completed'));
        
        document.getElementById('step' + step).classList.add('active');
        const currentStepItem = document.querySelector(`.step-item[data-step="${step}"]`);
        if(currentStepItem) currentStepItem.classList.add('active');
        
        for (let i = 1; i < step; i++) {
            const completedStepItem = document.querySelector(`.step-item[data-step="${i}"]`);
            if(completedStepItem) completedStepItem.classList.add('completed');
        }
        
        const progress = step === 1 ? 50 : 100;
        document.getElementById('progressBar').style.width = progress + '%';
    }

    function validateStep1() {
        let isValid = true;
        const requiredFields = ['judul', 'tanggal_acara', 'lokasi', 'kategori', 'sistem_pendaftaran', 'kuota', 'kategori_acara', 'persyaratan', 'tanggal_mulai_daftar', 'tanggal_akhir_daftar', 'hadiah', 'tentang'];
        
        requiredFields.forEach(fieldId => {
            const input = document.getElementById(fieldId);
            if (!input.value) {
                input.classList.add('is-invalid');
                isValid = false;
            } else {
                input.classList.remove('is-invalid');
            }
        });

        if (!isValid) {
            Swal.fire({
                icon: 'warning',
                title: 'Form Belum Lengkap',
                text: 'Mohon lengkapi semua field yang wajib diisi!',
                confirmButtonText: 'OK'
            });
        }
        return isValid;
    }

    document.getElementById('editEventForm').addEventListener('click', function(e) {
        if (e.target.matches('#nextBtn')) {
            if (currentStep === 1 && validateStep1()) {
                currentStep++;
                showStep(currentStep);
                displayExistingFormFields();
            }
        }

        if (e.target.matches('#prevBtn')) {
            if (currentStep > 1) {
                currentStep--;
                showStep(currentStep);
            }
        }
    });

    let fieldIndex = 0;

    function displayExistingFormFields() {
        const existingFields = @json($kolomFormulir);
        
        // Default fields (tidak bisa dihapus)
        const defaultFields = [
            {nama_kolom: 'nama_lengkap', label_kolom: 'Nama Lengkap', tipe_kolom: 'text'},
            {nama_kolom: 'no_hp', label_kolom: 'No HP Aktif', tipe_kolom: 'text'},
            {nama_kolom: 'email', label_kolom: 'Email Aktif', tipe_kolom: 'email'},
            {nama_kolom: 'alamat', label_kolom: 'Alamat Lengkap', tipe_kolom: 'textarea'}
        ];

        // Add default fields (locked)
        defaultFields.forEach(field => {
            addFormField(field.tipe_kolom, field.nama_kolom, field.label_kolom, field.placeholder || '', 1, true);
        });

        // Add existing custom fields (can be removed) - SKIP default fields
        existingFields.forEach(field => {
            // Skip jika field ini adalah default field
            if (!['nama_lengkap', 'no_hp', 'email', 'alamat'].includes(field.nama_kolom)) {
                addFormField(field.tipe_kolom, field.nama_kolom, field.label_kolom, field.placeholder || '', 1, false);
                // Check if it's a quick-add field and disable the button
                const quickButton = document.querySelector(`.quick-add[data-name="${field.nama_kolom}"]`);
                if (quickButton) {
                    quickButton.disabled = true;
                    quickButton.classList.remove('btn-outline-success');
                    quickButton.classList.add('btn-success');
                    quickButton.innerHTML = '<i class="fas fa-check mr-1"></i> Sudah Ditambah';
                }
            }
        });
    }

    function addFormField(type = 'text', name = '', label = '', placeholder = '', required = 1, isDefault = false) {
        const fieldHtml = `
            <div class="border rounded p-3 mb-3 form-builder-field ${isDefault ? 'default-field' : ''}" id="field_${fieldIndex}" data-field-name="${name}">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0 ${isDefault ? 'text-success' : 'text-primary'}"><i class="fas fa-${getFieldIcon(type)} mr-2"></i> ${label} ${isDefault ? '<span class="badge badge-success ml-2">WAJIB</span>' : '<span class="badge badge-danger ml-2">WAJIB</span>'}</h6>
                    ${!isDefault ? '<button type="button" class="btn btn-danger btn-sm remove-field"><i class="fas fa-trash"></i></button>' : '<i class="fas fa-lock text-success"></i>'}
                </div>
                <input type="hidden" name="form_fields[${fieldIndex}][field_name]" value="${name}"><input type="hidden" name="form_fields[${fieldIndex}][field_label]" value="${label}"><input type="hidden" name="form_fields[${fieldIndex}][field_type]" value="${type}"><input type="hidden" name="form_fields[${fieldIndex}][is_required]" value="${required}"><input type="hidden" name="form_fields[${fieldIndex}][placeholder]" value="${placeholder}"><input type="hidden" name="form_fields[${fieldIndex}][field_order]" value="${fieldIndex}">
                <div class="field-preview"><small class="text-muted"><strong>Preview:</strong></small><br><label class="font-weight-bold">${label} <span class="text-danger">*</span></label>${getFieldPreview(type, placeholder)}</div>
            </div>`;
        document.getElementById('formFieldsContainer').insertAdjacentHTML('beforeend', fieldHtml);
        fieldIndex++;
    }

    function getFieldPreview(type, placeholder) {
        switch(type) {
            case 'text': case 'email': case 'number':
                return `<input type="${type}" class="form-control form-control-sm" placeholder="${placeholder}" disabled>`;
            case 'textarea':
                return `<textarea class="form-control form-control-sm" rows="2" placeholder="${placeholder}" disabled></textarea>`;
            case 'file':
                return `<input type="file" class="form-control-file" disabled><br><small class="text-muted">${placeholder}</small>`;
            default:
                return `<input type="text" class="form-control form-control-sm" disabled>`;
        }
    }

    function getFieldIcon(type) {
        switch(type) {
            case 'text': return 'font';
            case 'email': return 'envelope';
            case 'number': return 'hashtag';
            case 'textarea': return 'align-left';
            case 'file': return 'file-upload';
            default: return 'edit';
        }
    }

    // Event listeners untuk quick add buttons
    document.querySelectorAll('.quick-add').forEach(button => {
        button.addEventListener('click', function() {
            const type = this.dataset.type;
            const name = this.dataset.name;
            const label = this.dataset.label;
            const placeholder = this.dataset.placeholder;
            const required = this.dataset.required !== undefined ? this.dataset.required : 1;
            
            this.setAttribute('data-field-name', name);
            addFormField(type, name, label, placeholder, required);
            
            this.disabled = true;
            this.classList.remove('btn-outline-success');
            this.classList.add('btn-success');
            this.innerHTML = '<i class="fas fa-check mr-1"></i> Sudah Ditambah';
        });
    });

    // Event listener untuk add custom field
    document.getElementById('addCustomFieldBtn').addEventListener('click', function() {
        Swal.fire({
            title: 'Tambah Field Custom',
            html: `
                <div class="text-left">
                    <div class="form-group"><label><strong>Label Field:</strong></label><input type="text" id="customFieldLabel" class="form-control" placeholder="contoh: Usia"></div>
                    <div class="form-group"><label><strong>Tipe Field:</strong></label><select id="customFieldType" class="form-control"><option value="text">Text</option><option value="number">Angka</option><option value="textarea">Text Panjang</option><option value="file">File Upload</option></select></div>
                </div>`,
            showCancelButton: true,
            confirmButtonText: 'Tambah',
            cancelButtonText: 'Batal',
            preConfirm: () => {
                const label = document.getElementById('customFieldLabel').value;
                const type = document.getElementById('customFieldType').value;
                if (!label) {
                    Swal.showValidationMessage('Label field harus diisi');
                    return false;
                }
                const name = label.toLowerCase().replace(/\s+/g, '_').replace(/[^a-z0-9_]/g, '');
                const placeholder = type === 'file' ? 'Format: PDF, JPG, PNG (Max: 2MB)' : `Masukkan ${label.toLowerCase()}`;
                return { name, label, type, required: 1, placeholder };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const { name, label, type, required, placeholder } = result.value;
                addFormField(type, name, label, placeholder, required);
            }
        });
    });

    // Event listener untuk remove field
    document.getElementById('formFieldsContainer').addEventListener('click', function(e) {
        if (e.target.closest('.remove-field')) {
            const removeButton = e.target.closest('.remove-field');
            const fieldContainer = removeButton.closest('.form-builder-field');
            const fieldLabel = fieldContainer.querySelector('input[name*="[field_label]"]').value;
            const fieldName = fieldContainer.dataset.fieldName;
            
            Swal.fire({
                title: 'Hapus Field?',
                text: `Field "${fieldLabel}" akan dihapus`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    fieldContainer.remove();
                    resetQuickAddButton(fieldName);
                }
            });
        }
    });

    function resetQuickAddButton(fieldName) {
        const button = document.querySelector(`.quick-add[data-name="${fieldName}"]`);
        if (button) {
            const originalLabel = button.dataset.label;
            const originalIcon = getQuickAddIcon(fieldName);
            button.disabled = false;
            button.classList.remove('btn-success');
            button.classList.add('btn-outline-success');
            button.innerHTML = `<i class="fas fa-${originalIcon} mr-1"></i> ${originalLabel}`;
        }
    }

    function getQuickAddIcon(fieldName) {
        switch(fieldName) {
            case 'foto_ktp': return 'id-card';
            default: return 'plus';
        }
    }

    document.getElementById('submitBtn').addEventListener('click', function(e) {
        e.preventDefault();
        
        const form = document.getElementById('editEventForm');
        Swal.fire({
            title: 'Konfirmasi Update Event',
            text: 'Apakah anda yakin ingin memperbarui event ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Update!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Sedang Memperbarui Event...',
                    html: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => { Swal.showLoading(); }
                });
                form.submit();
            }
        });
    });

    showStep(currentStep);
});
</script>
@endpush