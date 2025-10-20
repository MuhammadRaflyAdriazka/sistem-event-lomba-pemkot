<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - Sistem Penerimaan Magang Pemkot Banjarmasin</title>
    
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
        .register-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            padding: 40px;
            max-width: 450px;
            width: 100%;
        }
        .logo-img {
            height: 120px;
            width: 120px;
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
        .btn-register {
            background-color: #007bff;
            border: none;
            padding: 12px 30px;
            border-radius: 5px;
            font-weight: 600;
            width: 100%;
        }
        .btn-register:hover {
            background-color: #0056b3;
        }
        .register-link {
            color: #6c757d;
            text-decoration: none;
            font-size: 14px;
        }
        .register-link:hover {
            color: #007bff;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="min-vh-100 d-flex align-items-center justify-content-center p-3">
        <div class="register-card">
            <!-- Logo -->
            <div class="text-center mb-4">
                <img src="{{ asset('image/LOGO-PEMKOT-BARU.png') }}" alt="Logo" class="logo-img mb-3">
                <h2 class="fw-bold text-dark mb-2">Register</h2>
                <p class="text-muted mb-0">Buat akun baru untuk mendaftar event & lomba</p>
            </div>
            
            <!-- Register Form -->
            <form method="POST" action="{{ route('register') }}">
                @csrf
                
                <!-- Name -->
                <div class="mb-3">
                    <label for="name" class="form-label text-dark fw-semibold">Nama</label>
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" 
                           name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label text-dark fw-semibold">Email</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                           name="email" value="{{ old('email') }}" required autocomplete="username">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label text-dark fw-semibold">Password</label>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                           name="password" required autocomplete="new-password">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="mb-4">
                    <label for="password_confirmation" class="form-label text-dark fw-semibold">Konfirmasi Password</label>
                    <input id="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                           name="password_confirmation" required autocomplete="new-password">
                    @error('password_confirmation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Submit Button and Login Link -->
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('login') }}" class="register-link">
                        Sudah terdaftar?
                    </a>
                    <button type="submit" class="btn btn-primary" style="padding: 8px 20px; border-radius: 5px;">
                        Register
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
