<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>SI KAMJA - Login</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('template/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('img/logotomohon.png') }}">

    <!-- Custom styles for this template-->
    <link href="{{ asset('template/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- CSS Kustom untuk Halaman Login --}}
    <style>
        .login-bg {
            background-image: url('{{ asset('img/background.JPG') }}');
            background-size: cover;
            background-position: center;
        }
        .login-container { min-height: 100vh; }
        .login-card {
            border: none;
            border-radius: 1rem;
            background-color: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }
        .form-group-icon { position: relative; }
        .form-group-icon .form-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #d1d3e2;
            z-index: 10;
        }
        .form-group-icon .form-control-user {
            padding-left: 2.5rem !important;
        }
        .btn-login {
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            font-weight: bold;
        }
        /* Style untuk ikon mata */
        .password-toggle-icon {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #858796;
        }
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
                                <h1 class="h4 text-gray-900 mb-1">Selamat Datang Kembali!</h1>
                                <p class="text-muted mb-4">Silakan login untuk melanjutkan.</p>
                            </div>

                            @if(session('error'))
                                <div class="alert alert-danger text-center small py-2" role="alert">
                                    {{ session('error') }}
                                </div>
                            @endif

                            <form class="user" action="/login" method="POST">
                                @csrf
                                <div class="form-group form-group-icon">
                                    <i class="fas fa-user form-icon"></i>
                                    <input type="text" class="form-control form-control-user"
                                        id="name" name="name"
                                        placeholder="Masukkan Username Anda..." required value="{{ old('name') }}">
                                </div>
                                <div class="form-group form-group-icon">
                                    <i class="fas fa-lock form-icon"></i>
                                    <input type="password" class="form-control form-control-user"
                                        id="password" name="password" placeholder="Password" required>
                                    {{-- Ikon Mata untuk Toggle Password --}}
                                    <i class="fas fa-eye password-toggle-icon" id="togglePassword"></i>
                                </div>
                                {{-- =============================================== --}}
                                
                                <button type="submit" class="btn btn-primary btn-user btn-block btn-login mt-4">
                                    Login
                                </button>
                            </form>
                            <hr>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Inti -->
    <script src="{{ asset('template/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('template/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('template/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('template/js/sb-admin-2.min.js') }}"></script>
    
    {{-- =============================================== --}}
    {{-- === JAVASCRIPT BARU UNTUK TOGGLE PASSWORD === --}}
    {{-- =============================================== --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const togglePassword = document.querySelector('#togglePassword');
            const password = document.querySelector('#password');

            togglePassword.addEventListener('click', function (e) {
                // Ubah tipe input
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                
                // Ubah ikon mata
                this.classList.toggle('fa-eye-slash');
            });
        });
    </script>
    
    {{-- Notifikasi untuk error validasi (jika ada) --}}
    @if ($errors->any())
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            html: `{!! implode('<br>', $errors->all()) !!}`
        });
    </script>
    @endif

</body>
</html>
