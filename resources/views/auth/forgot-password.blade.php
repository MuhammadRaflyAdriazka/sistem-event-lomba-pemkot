<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgot Password - Sistem Penerimaan Magang Pemkot Banjarmasin</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, rgba(26,41,86,0.85) 0%, rgba(34,51,102,0.85) 100%), url('{{ asset("image/Balai-Kota-Banjarmasin-001.jpg") }}') center/cover no-repeat;
            min-height: 100vh;
            font-family: 'Segoe UI', sans-serif;
        }
        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            padding: 40px;
            max-width: 450px;
            width: 100%;
        }
        .logo-img {
            height: 80px;
            width: 80px;
            object-fit: contain;
        }
        .form-control {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 12px 15px;
            font-size: 14px;
        }
        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
        }
        .btn-login {
            background-color: #007bff;
            border: none;
            padding: 8px 20px;
            border-radius: 5px;
            font-weight: 600;
        }
        .btn-login:hover {
            background-color: #0056b3;
        }
        .login-link {
            color: #6c757d;
            text-decoration: none;
            font-size: 14px;
        }
        .login-link:hover {
            color: #007bff;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="min-vh-100 d-flex align-items-center justify-content-center p-3">
        <div class="login-card">
            <!-- Logo -->
            <div class="text-center mb-4">
                <img src="{{ asset('image/LOGO-PEMKOT-BARU.png') }}" alt="Logo" class="logo-img mb-3">
                <h2 class="fw-bold text-dark mb-2">Lupa Password</h2>
                <p class="text-muted mb-0">Lupa password Anda? Tidak masalah. Masukkan alamat email Anda dan kami akan mengirimkan link reset password yang memungkinkan Anda membuat password baru.</p>
            </div>
            
            <!-- Session Status -->
            @if (session('status'))
                <div class="alert alert-success mb-3">
                    {{ session('status') }}
                </div>
            @endif
            
            <!-- Forgot Password Form -->
            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                
                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="form-label text-dark fw-semibold">Email</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                           name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Submit Button and Back Link -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <a href="{{ route('login') }}" class="login-link">
                        <i class="fas fa-arrow-left me-1"></i> Kembali ke Login
                    </a>
                    <button type="submit" class="btn btn-primary" style="padding: 8px 20px; border-radius: 5px;">
                        Kirim Link Reset Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
