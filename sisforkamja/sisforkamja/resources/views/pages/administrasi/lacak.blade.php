<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Lacak Layanan - SI KAMJA</title>
    {{-- (Sertakan semua link CSS Anda seperti biasa) --}}
    <link href="{{ asset('template/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="{{ asset('template/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('img/logotomohon.png') }}">
    {{-- CSS Khusus untuk Komponen Progress Step --}}
    <style>
        .tracker-container { font-family: 'Nunito', sans-serif; }
        .step-container { display: flex; justify-content: space-between; position: relative; margin-bottom: 1.5rem; }
        .step-container::before { content: ''; position: absolute; top: 19px; left: 0; right: 0; height: 2px; background-color: #e9ecef; z-index: 1; }
        .step-progress-bar { position: absolute; top: 19px; left: 0; height: 2px; z-index: 2; transition: width 0.6s ease; }
        .step { display: flex; flex-direction: column; align-items: center; position: relative; z-index: 3; width: 120px; }
        .step-circle { width: 40px; height: 40px; border-radius: 50%; background-color: #fff; border: 2px solid #e9ecef; display: flex; align-items: center; justify-content: center; transition: all 0.4s ease; color: #d1d5db; }
        .step-label { margin-top: 0.75rem; font-size: 0.8rem; color: #858796; text-align: center; }
        .step-date { font-size: 0.75rem; color: #b7b9cc; }
        /* WARNA HIJAU UNTUK YANG SELESAI */
        .step.completed .step-circle { background-color: #1cc88a; border-color: #1cc88a; color: #fff; }
        .step.completed .step-label { color: #1cc88a; font-weight: bold;}
        /* WARNA BIRU UNTUK YANG SEDANG AKTIF */
        .step.active .step-circle { background-color: #4e73df; border-color: #4e73df; color: #fff; transform: scale(1.1); box-shadow: 0 0 0 4px rgba(78, 115, 223, 0.2); }
        .step.active .step-label { color: #4e73df; font-weight: bold; }
    </style>
</head>
<body style="background-color: #f0f2f5;">

<div class="container tracker-container">
    <div class="row justify-content-center py-5">
        <div class="col-xl-9 col-lg-10 col-md-11">
            
            {{-- Form Pencarian Umum (tidak berubah) --}}
            <div class="card o-hidden border-0 shadow-lg mb-4">
                 <div class="card-body p-4">
                    <div class="text-center">
                        <h1 class="h4 text-gray-900 mb-2 font-weight-bold">Lacak Status Layanan Anda</h1>
                        <p class="mb-4 small">Masukkan Kode Pelacakan (untuk Surat atau Pengaduan) di bawah ini.</p>
                    </div>
                    <form action="{{ route('administrasi.lacak') }}" method="GET">
                        <div class="input-group">
                            <input type="text" name="kode" class="form-control form-control-user" value="{{ request('kode') }}" placeholder="Contoh: SKTM-25062701 atau ADU-25062701" required>
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary px-3"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- ============================================= --}}
            {{-- === BAGIAN HASIL PENCARIAN (DINAMIS) === --}}
            {{-- ============================================= --}}
            
            @if(isset($hasil))
                {{-- Template untuk Permohonan Surat --}}
                @if($jenisLayanan == 'surat')
                    <div class="card o-hidden border-0 shadow-lg">
                        <div class="card-header bg-white py-3"><h6 class="m-0 font-weight-bold text-primary">Status Permohonan Surat: {{ $hasil->kode_pelacakan }}</h6></div>
                        <div class="card-body p-4 p-md-5">
                            @if($hasil->status == 'Ditolak')
                                <div class="text-center"><i class="fas fa-times-circle text-danger fa-4x mb-3"></i><h4 class="font-weight-bold">Permohonan Ditolak</h4><div class="alert alert-light mt-3"><strong>Alasan:</strong> {{ $hasil->catatan_admin ?? 'Tidak ada alasan spesifik.' }}</div></div>
                            @else
                                @php
                                    $steps = ['Menunggu', 'Diproses', 'Selesai'];
                                    $currentStepIndex = array_search($hasil->status, $steps);
                                    $progressWidth = $currentStepIndex > 0 ? ($currentStepIndex / (count($steps) - 1)) * 100 : 0;
                                @endphp
                                <div class="step-container">
                                    <div class="step-progress-bar" style="width: {{ $progressWidth }}%; background-color: #1cc88a;"></div>
                                    {{-- PERBAIKAN LOGIKA DI BAWAH INI --}}
                                    <div class="step {{ $currentStepIndex >= 0 ? 'completed' : '' }} {{ $hasil->status == 'Menunggu' ? 'active' : '' }}"><div class="step-circle"><i class="fas {{ $currentStepIndex > 0 ? 'fa-check' : 'fa-inbox' }}"></i></div><div class="step-label">Diterima</div></div>
                                    <div class="step {{ $currentStepIndex >= 1 ? 'completed' : '' }} {{ $hasil->status == 'Diproses' ? 'active' : '' }}"><div class="step-circle"><i class="fas {{ $currentStepIndex > 1 ? 'fa-check' : 'fa-cogs' }}"></i></div><div class="step-label">Diproses</div></div>
                                    <div class="step {{ $currentStepIndex >= 2 ? 'completed' : '' }}"><div class="step-circle"><i class="fas fa-check-circle"></i></div><div class="step-label">Selesai</div></div>
                                </div>
                            @endif
                            
                            <div class="text-center mt-5">
                                <p class="lead"><strong>Jenis Surat:</strong> {{ $hasil->jenisSurat->nama_surat }}</p>
                                <p class="text-muted">
                                    <strong>Pemohon:</strong> {{ $hasil->penduduk->nama ?? 'Data Dihapus' }} <br>
                                    <strong>Tanggal Pengajuan:</strong> {{ $hasil->created_at->translatedFormat('d F Y') }}
                                </p>
                                <hr>
                                @if($hasil->status == 'Menunggu')
                                    <p class="font-italic">Permohonan Anda sedang dalam antrian untuk diverifikasi oleh admin.</p>
                                @elseif($hasil->status == 'Diproses')
                                    <p class="font-italic">Admin sedang memverifikasi data dan dokumen Anda. Mohon ditunggu.</p>
                                @elseif($hasil->status == 'Selesai')
                                    <p class="font-weight-bold text-success">Surat Anda sudah siap diambil di Kantor Kelurahan!</p>
                                @endif
                            </div>
                        </div>
                    </div>

                {{-- Template untuk Layanan Pengaduan --}}
                @elseif($jenisLayanan == 'pengaduan')
                    <div class="card o-hidden border-0 shadow-lg">
                         <div class="card-header bg-white py-3"><h6 class="m-0 font-weight-bold text-primary">Status Laporan Pengaduan: {{ $hasil->kode_pengaduan }}</h6></div>
                        <div class="card-body p-4 p-md-5">
                             @if($hasil->status == 'Ditolak')
                                 <div class="text-center"><i class="fas fa-times-circle text-danger fa-4x mb-3"></i><h4 class="font-weight-bold">Laporan Ditolak</h4><div class="alert alert-light mt-3"><strong>Tanggapan:</strong> {{ $hasil->tanggapan_admin ?? 'Tidak ada alasan spesifik.' }}</div></div>
                            @else
                                @php
                                    $steps = ['Baru', 'Dalam Peninjauan', 'Ditindaklanjuti', 'Selesai'];
                                    $currentStepIndex = array_search($hasil->status, $steps);
                                    $progressWidth = $currentStepIndex > 0 ? ($currentStepIndex / (count($steps) - 1)) * 100 : 0;
                                @endphp
                                <div class="step-container">
                                    <div class="step-progress-bar" style="width: {{ $progressWidth }}%; background-color: #1cc88a;"></div>
                                    {{-- PERBAIKAN LOGIKA DI BAWAH INI --}}
                                    <div class="step {{ $currentStepIndex >= 0 ? 'completed' : '' }} {{ $hasil->status == 'Baru' ? 'active' : '' }}"><div class="step-circle"><i class="fas {{ $currentStepIndex > 0 ? 'fa-check' : 'fa-bullhorn' }}"></i></div><div class="step-label">Diterima</div></div>
                                    <div class="step {{ $currentStepIndex >= 1 ? 'completed' : '' }} {{ $hasil->status == 'Dalam Peninjauan' ? 'active' : '' }}"><div class="step-circle"><i class="fas {{ $currentStepIndex > 1 ? 'fa-check' : 'fa-search' }}"></i></div><div class="step-label">Ditinjau</div></div>
                                    <div class="step {{ $currentStepIndex >= 2 ? 'completed' : '' }} {{ $hasil->status == 'Ditindaklanjuti' ? 'active' : '' }}"><div class="step-circle"><i class="fas {{ $currentStepIndex > 2 ? 'fa-check' : 'fa-user-cog' }}"></i></div><div class="step-label">Ditindaklanjuti</div></div>
                                    <div class="step {{ $currentStepIndex >= 3 ? 'completed' : '' }}"><div class="step-circle"><i class="fas fa-check-circle"></i></div><div class="step-label">Selesai</div></div>
                                </div>
                            @endif
                            <div class="text-center mt-5">
                                <p class="lead"><strong>Judul Laporan:</strong> {{ $hasil->judul }}</p>
                                <p class="text-muted"><strong>Tanggapan Terakhir:</strong> {{ $hasil->tanggapan_admin ?? 'Belum ada tanggapan dari admin.' }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            @endif

            {{-- Tampilkan pesan error JIKA ada --}}
            @if(isset($errorMessage))
            <div class="alert alert-warning text-center mt-4" role="alert"><i class="fas fa-exclamation-triangle mr-2"></i> {{ $errorMessage }}</div>
            @endif

            {{-- Tampilkan pesan error JIKA ada --}}
            @if(isset($errorMessage))
            <div class="alert alert-warning text-center mt-4" role="alert">
                <i class="fas fa-exclamation-triangle mr-2"></i> {{ $errorMessage }}
            </div>
            @endif

            <div class="text-center mt-4">
                 <a class="small" href="/administrasi">&larr; Kembali ke Halaman Utama</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>
