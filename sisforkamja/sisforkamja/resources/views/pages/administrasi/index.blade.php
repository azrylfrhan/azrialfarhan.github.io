<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Portal Layanan Online - SI KAMJA</title>

    <!-- Aset CSS & Font -->
    <link href="{{ asset('template/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="{{ asset('template/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('img/logotomohon.png') }}">
    
    <!-- SweetAlert2 untuk notifikasi -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- CSS Kustom untuk Tampilan Portal yang Lebih Baik --}}
    <style>
        .hero-section {
            background: linear-gradient(rgba(78, 115, 223, 0.9), rgba(78, 115, 223, 0.9)), url('{{ asset('img/background.JPG') }}');
            background-size: cover;
            background-position: center;
            padding: 5rem 0;
            color: white;
        }
        .service-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            border: none;
            border-radius: 0.75rem;
            height: 100%;
        }
        .service-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 1rem 3rem rgba(0,0,0,.175)!important;
        }
        .service-card .card-body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .service-card .icon-circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            background-color: rgba(78, 115, 223, 0.1);
            color: #4e73df;
            font-size: 2.5rem;
        }
    </style>
</head>
<body style="background-color: #f8f9fc;">
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand font-weight-bold text-primary" href="{{ url('/administrasi') }}">
            SI KAMJA
        </a>
        
        {{-- Tombol "Hamburger" untuk mode mobile --}}
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            {{-- Pindahkan menu ke dalam div collapsible ini --}}
            <ul class="navbar-nav ml-auto">
                @auth
                    {{-- JIKA PENGGUNA SUDAH LOGIN --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-user-circle mr-1"></i>
                            {{ auth()->user()->name }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                @else
                    {{-- JIKA PENGGUNA BELUM LOGIN (PENGUNJUNG) --}}
                    <li class="nav-item">
                        <a class="btn btn-outline-primary btn-sm" href="{{ route('warga.login.form') }}">Login Warga</a>
                    </li>
                    <li class="nav-item ml-2">
                        <a class="btn btn-primary btn-sm" href="{{ route('warga.register.form') }}">Registrasi</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
    <!-- Bagian Hero Section -->
    <div class="hero-section text-center">
        <div class="container">
            <img src="{{ asset('img/logotomohon.png') }}" alt="Logo" style="width: 90px;" class="mb-3">
            <h1 class="display-4 font-weight-bold">Portal Layanan Digital</h1>
            <p class="lead">Kelurahan Kampung Jawa, Tomohon</p>
            <hr style="border-color: rgba(255,255,255,0.3); width: 100px;">
            <p class="mt-4">Layanan administrasi yang cepat, mudah, dan transparan untuk seluruh masyarakat.</p>
            <a href="{{ route('administrasi.lacak') }}" class="btn btn-warning btn-lg mt-2">
                <i class="fas fa-search mr-2"></i> Lacak Status Layanan Anda
            </a>
        </div>
    </div>

    <!-- Bagian Kartu Layanan -->
    <section class="container py-5">
        <div class="text-center mb-5">
            <h2 class="font-weight-bold">Pilih Layanan</h2>
            <p class="text-muted">Pilih salah satu layanan yang tersedia di bawah ini untuk memulai.</p>
        </div>

        <div class="row justify-content-center">
            <!-- Kartu 1: Pengurusan Surat -->
            <div class="col-lg-4 col-md-6 mb-4">
                <a href="{{ route('administrasi.surat') }}" class="text-decoration-none">
                    <div class="card service-card shadow-sm text-center">
                        <div class="card-body p-4">
                            <div class="icon-circle">
                                <i class="fas fa-file-signature"></i>
                            </div>
                            <h5 class="card-title font-weight-bold text-gray-800">Pengurusan Surat</h5>
                            <p class="card-text small text-muted">Ajukan berbagai jenis surat keterangan secara online tanpa perlu datang ke kantor.</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Kartu 2: Layanan Pengaduan -->
            <div class="col-lg-4 col-md-6 mb-4">
                <a href="{{ route('administrasi.pengaduan') }}" class="text-decoration-none">
                    <div class="card service-card shadow-sm text-center">
                        <div class="card-body p-4">
                            <div class="icon-circle">
                                <i class="fas fa-bullhorn"></i>
                            </div>
                            <h5 class="card-title font-weight-bold text-gray-800">Layanan Pengaduan</h5>
                            <p class="card-text small text-muted">Sampaikan laporan atau keluhan Anda terkait fasilitas dan layanan publik di lingkungan Anda.</p>
                        </div>
                    </div>
                </a>
            </div>
            
            {{-- Tambahkan kartu lain di sini jika ada, misalnya "Informasi Kelurahan" --}}

        </div>
    </section>

    <!-- Footer Sederhana -->
    <footer class="py-4 bg-light mt-auto">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between small">
                <div class="text-muted">Copyright &copy; SI KAMJA {{ date('Y') }}</div>
                <div>
                    <a href="#">Privacy Policy</a>
                    &middot;
                    <a href="#">Terms &amp; Conditions</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="{{ asset('template/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('template/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- Skrip Notifikasi (SweetAlert2) -->
    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            timer: 3000,
            showConfirmButton: false
        });
    </script>
    @endif

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
