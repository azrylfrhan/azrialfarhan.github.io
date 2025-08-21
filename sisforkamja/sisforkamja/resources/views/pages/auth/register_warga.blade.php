<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>SI KAMJA - Registrasi Warga</title>

    <link href="{{ asset('template/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('img/logotomohon.png') }}">

    <link href="{{ asset('template/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Menggunakan CSS Kustom yang sama dari halaman Login --}}
    <style>
        .login-bg { background-image: url('{{ asset('img/background.JPG') }}'); background-size: cover; background-position: center; }
        .login-container { min-height: 100vh; }
        .login-card { border: none; border-radius: 1rem; background-color: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); }
        .form-group-icon { position: relative; }
        .form-group-icon .form-icon { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #d1d3e2; z-index: 10; }
        .form-group-icon .form-control-user { padding-left: 2.5rem !important; }
        .btn-login { border-radius: 0.5rem; padding: 0.75rem 1rem; font-weight: bold; }
        .password-toggle-icon { position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); cursor: pointer; color: #858796;}
    </style>
</head>

<body class="login-bg">
    <div class="container login-container">
        <div class="row justify-content-center align-items-center h-100">
            <div class="col-xl-5 col-lg-6 col-md-8">
                <div class="card o-hidden shadow-lg my-5 login-card">
                    <div class="card-body p-0">
                        <div class="p-4 p-md-5">
                            <div class="text-center">
                                <img src="{{ asset('img/logotomohon.png') }}" alt="logo" style="width: 80px;" class="mb-3">
                                <h1 class="h4 text-gray-900 mb-1">Buat Akun Layanan</h1>
                                <p class="text-muted mb-4">Daftarkan NIK Anda untuk mengakses layanan.</p>
                            </div>

                            @if ($errors->any())
                                <div class="alert alert-danger text-center small py-2">
                                    @foreach ($errors->all() as $error)
                                        {{ $error }}<br>
                                    @endforeach
                                </div>
                            @endif

                            <form class="user" action="{{ route('warga.register.submit') }}" method="POST">
                                @csrf
                                <div class="form-group form-group-icon">
                                    <i class="fas fa-id-card form-icon"></i>
                                    <input type="text" class="form-control form-control-user" name="nik" placeholder="Masukkan Nomor Induk Kependudukan (NIK)" required value="{{ old('nik') }}">
                                </div>
                                <div class="form-group form-group-icon">
                                    <i class="fas fa-lock form-icon"></i>
                                    <input type="password" class="form-control form-control-user" name="password" id="password" placeholder="Buat Password (Min. 8 Karakter)" required>
                                    <i class="fas fa-eye password-toggle-icon" data-target="#password"></i>
                                </div>
                                <div class="form-group form-group-icon">
                                    <i class="fas fa-lock form-icon"></i>
                                    <input type="password" class="form-control form-control-user" name="password_confirmation" id="password_confirmation" placeholder="Ulangi Password" required>
                                    <i class="fas fa-eye password-toggle-icon" data-target="#password_confirmation"></i>
                                </div>
                                <button type="submit" class="btn btn-primary btn-user btn-block btn-login mt-4">
                                    Daftar Akun
                                </button>
                            </form>
                            <hr>
                            <div class="text-center">
                                <a class="small" href="{{ route('warga.login.form') }}">Sudah Punya Akun? Login di Sini</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('template/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('template/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    
    {{-- JavaScript untuk Toggle Password --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleIcons = document.querySelectorAll('.password-toggle-icon');
            toggleIcons.forEach(function(icon) {
                icon.addEventListener('click', function() {
                    const targetInputSelector = this.getAttribute('data-target');
                    const targetInput = document.querySelector(targetInputSelector);
                    if (targetInput) {
                        const type = targetInput.getAttribute('type') === 'password' ? 'text' : 'password';
                        targetInput.setAttribute('type', type);
                        this.classList.toggle('fa-eye');
                        this.classList.toggle('fa-eye-slash');
                    }
                });
            });
        });
    </script>
</body>
</html>