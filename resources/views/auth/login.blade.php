<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Sistem Penerimaan Magang Pemkot Banjarmasin</title>
    
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
                <h2 class="fw-bold text-dark mb-2">Login</h2>
                <p class="text-muted mb-0">Masuk ke Akun Anda </p>
            </div>
            
            <!-- Session Status -->
            @if (session('status'))
                <div class="alert alert-success mb-3">
                    {{ session('status') }}
                </div>
            @endif
            
            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label text-dark fw-semibold">Email</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                           name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label text-dark fw-semibold">Password</label>
                    <div class="position-relative">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                               name="password" required autocomplete="current-password" style="padding-right: 50px;">
                        <button type="button" class="btn position-absolute end-0 top-50 translate-middle-y border-0 bg-transparent" 
                                style="z-index: 10; right: 10px;" onclick="togglePassword('password')">
                            <i id="password-icon" class="fas fa-eye text-muted"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="remember_me" name="remember">
                        <label class="form-check-label text-dark" for="remember_me">
                            Ingat saya
                        </label>
                    </div>
                </div>

                <!-- Submit Button and Register Link -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <a href="{{ route('register') }}" class="login-link">
                        Belum punya akun?
                    </a>
                    <button type="submit" class="btn btn-primary" style="padding: 8px 20px; border-radius: 5px;">
                        Login
                    </button>
                </div>

                <!-- Forgot Password Link -->
                @if (Route::has('password.request'))
                    <div class="text-center">
                        <a href="{{ route('password.request') }}" class="login-link">
                            Lupa password?
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
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
</body>
</html>
