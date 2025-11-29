@php
    $user = auth()->user();
    $layout = match($user->peran) {
        'admin_dinas' => 'layouts.admin.app',
        'kepala_dinas' => 'layouts.kepala.app', 
        'panitia' => 'layouts.panitia.app',
        'peserta' => 'layouts.peserta.app',
        default => 'layouts.admin.app'
    };
    
    $profileUpdateRoute = match($user->peran) {
        'admin_dinas' => 'admin.profile.update',
        'kepala_dinas' => 'kepala.profile.update', 
        'panitia' => 'panitia.profile.update',
        'peserta' => 'profile.update',
        default => 'profile.update'
    };
    
    $formMethod = match($user->peran) {
        'kepala_dinas' => 'put',
        default => 'patch'
    };
@endphp

@extends($layout)

@section('title', 'Profil')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Profil</h1>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Profile Information Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Profil</h6>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Error:</strong>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="post" action="{{ route($profileUpdateRoute) }}" class="mt-6 space-y-6">
                        @csrf
                        @method($formMethod)

                        <div class="form-group">
                            <label for="name" class="font-weight-bold">Nama</label>
                            @if($user->peran === 'peserta')
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            @else
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="{{ $user->name }}" disabled readonly style="background-color: #f8f9fa;">
                                <small class="text-muted">Nama tidak dapat diubah untuk {{ ucfirst(str_replace('_', ' ', $user->peran)) }}</small>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="email" class="font-weight-bold">Email</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="{{ $user->email }}" disabled readonly style="background-color: #f8f9fa;">
                            <small class="text-muted">Email tidak dapat diubah</small>
                        </div>

                        @if($user->peran === 'peserta')
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> 
                                Nama dan email tidak dapat diubah untuk {{ ucfirst(str_replace('_', ' ', $user->peran)) }}. 
                                Untuk mengubah password, gunakan form di bawah.
                            </div>
                        @endif
                        
                        @if (session('status') === 'profile-updated' || session('success'))
                            <span class="text-success ml-2">
                                <i class="fas fa-check"></i> 
                                {{ session('success') ? session('success') : 'Profil berhasil diperbarui!' }}
                            </span>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Update Password Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Perbarui Password</h6>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
                        @csrf
                        @method('put')

                        <div class="form-group">
                            <label for="update_password_current_password" class="font-weight-bold">Password Saat Ini</label>
                            <div class="position-relative">
                                <input type="password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" 
                                       id="update_password_current_password" name="current_password" style="padding-right: 50px;" autocomplete="current-password">
                                <button type="button" class="btn position-absolute border-0 bg-transparent" 
                                        style="z-index: 10; right: 10px; top: 50%; transform: translateY(-50%);" onclick="togglePassword('update_password_current_password')">
                                    <i id="update_password_current_password-icon" class="fas fa-eye text-muted"></i>
                                </button>
                            </div>
                            @error('current_password', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="update_password_password" class="font-weight-bold">Password Baru</label>
                            <div class="position-relative">
                                <input type="password" class="form-control @error('password', 'updatePassword') is-invalid @enderror" 
                                       id="update_password_password" name="password" style="padding-right: 50px;" autocomplete="new-password">
                                <button type="button" class="btn position-absolute border-0 bg-transparent" 
                                        style="z-index: 10; right: 10px; top: 50%; transform: translateY(-50%);" onclick="togglePassword('update_password_password')">
                                    <i id="update_password_password-icon" class="fas fa-eye text-muted"></i>
                                </button>
                            </div>
                            @error('password', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="update_password_password_confirmation" class="font-weight-bold">Konfirmasi Password</label>
                            <div class="position-relative">
                                <input type="password" class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror" 
                                       id="update_password_password_confirmation" name="password_confirmation" style="padding-right: 50px;" autocomplete="new-password">
                                <button type="button" class="btn position-absolute border-0 bg-transparent" 
                                        style="z-index: 10; right: 10px; top: 50%; transform: translateY(-50%);" onclick="togglePassword('update_password_password_confirmation')">
                                    <i id="update_password_password_confirmation-icon" class="fas fa-eye text-muted"></i>
                                </button>
                            </div>
                            @error('password_confirmation', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-success">Perbarui Password</button>
                        
                        @if (session('status') === 'password-updated')
                            <span class="text-success ml-2">
                                <i class="fas fa-check"></i> Password berhasil diperbarui!
                            </span>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePassword(fieldId) {
        const passwordField = document.getElementById(fieldId);
        const passwordIcon = document.getElementById(fieldId + '-icon');
        
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            passwordIcon.className = 'fas fa-eye-slash text-muted';
        } else {
            passwordField.type = 'password';
            passwordIcon.className = 'fas fa-eye text-muted';
        }
    }
</script>

@endsection
