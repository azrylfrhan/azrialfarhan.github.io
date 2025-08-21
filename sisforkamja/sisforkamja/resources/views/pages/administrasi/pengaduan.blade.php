<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Layanan Pengaduan - SI KAMJA</title>
    <link href="{{ asset('template/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="{{ asset('template/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('img/logotomohon.png') }}">
</head>
<body style="background-color: #f0f2f5;">

<div class="container">
    <div class="row justify-content-center py-5">
        <div class="col-lg-8">
            <div class="card o-hidden border-0 shadow-lg">
                <div class="card-header bg-primary text-white text-center py-4">
                    <h1 class="h4 mb-0 font-weight-bold">Formulir Pengaduan Masyarakat</h1>
                </div>
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('pengaduan.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="penduduk_id" id="penduduk_id" value="{{ $penduduk->id ?? '' }}">

                        <h5 class="font-weight-bold text-gray-800">Data Diri Pelapor</h5>
                        <p class="small text-muted mb-3">
                            @if($penduduk)
                                Data Anda di bawah ini sudah terisi otomatis.
                            @else
                                Masukkan NIK Anda untuk mengisi nama otomatis, lalu masukkan nomor WhatsApp aktif Anda.
                            @endif
                        </p>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nik">NIK</label>
                                <div class="input-group">
                                    <input type="text" inputmode="numeric" class="form-control" id="nik" name="nik" value="{{ $penduduk->nik ?? old('nik') }}" {{ $penduduk ? 'readonly' : '' }} required>
                                    <div class="input-group-append"><span class="input-group-text"><i id="nik-spinner" class="fas fa-spinner fa-spin" style="display: none;"></i></span></div>
                                </div>
                                <div id="nik-error" class="text-danger mt-1 small"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="nama">Nama Lengkap</label>
                                <input type="text" class="form-control bg-light" id="nama" name="nama" readonly placeholder="Akan terisi otomatis..." value="{{ $penduduk->nama ?? '' }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="no_telepon">Nomor Telepon WhatsApp Aktif</label>
                            <input type="text" inputmode="numeric" class="form-control" id="no_telepon" name="no_telepon" value="{{ $penduduk->no_telepon ?? old('no_telepon') }}" placeholder="Contoh: 08123456789" required>
                        </div>

                        <hr class="my-4">

                        <h5 class="font-weight-bold text-gray-800">Detail Laporan</h5>
                        
                        <div class="form-group">
                            <label for="judul">Judul Laporan/Pengaduan</label>
                            <input type="text" class="form-control" id="judul" name="judul" value="{{ old('judul') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="kategori">Kategori Pengaduan</label>
                            <select class="form-control" id="kategori" name="kategori" required>
                                <option value="">-- Pilih Kategori --</option>
                                <option value="Infrastruktur">Infrastruktur (Jalan, Drainase, dll)</option>
                                <option value="Kebersihan">Kebersihan (Sampah, dll)</option>
                                <option value="Keamanan">Keamanan & Ketertiban</option>
                                <option value="Layanan Publik">Layanan Publik</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="isi_laporan">Isi Laporan Lengkap</label>
                            <textarea class="form-control" id="isi_laporan" name="isi_laporan" rows="5" required>{{ old('isi_laporan') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="foto_bukti">Lampirkan Foto Bukti (Opsional)</label>
                            <input type="file" class="form-control-file" id="foto_bukti" name="foto_bukti" accept="image/*">
                        </div>

                        <hr class="my-4">

                        <div class="row">
                            <div class="col-md-6 mb-2 mb-md-0">
                                <a href="/administrasi" class="btn btn-secondary btn-block">Kembali</a>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" id="submit-button" class="btn btn-primary btn-block" {{ $penduduk ? '' : 'disabled' }}>Kirim Laporan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('template/vendor/jquery/jquery.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Kode ini hanya akan berjalan jika pengguna BELUM LOGIN
    if (!document.getElementById('nik').hasAttribute('readonly')) {
        const nikInput = document.getElementById('nik');
        const nikError = document.getElementById('nik-error');
        const nikSpinner = document.getElementById('nik-spinner');
        const namaField = document.getElementById('nama');
        const pendudukIdField = document.getElementById('penduduk_id');
        const submitButton = document.getElementById('submit-button');

        nikInput.addEventListener('change', function () {
            let nik = this.value;
            nikInput.classList.remove('is-invalid');
            nikError.textContent = '';
            namaField.value = '';
            pendudukIdField.value = '';
            submitButton.disabled = true;

            if (nik.length >= 16) {
                nikSpinner.style.display = 'inline-block';
                fetch(`/get-nama/${nik}`)
                    .then(response => {
                        if (!response.ok) throw new Error('NIK tidak terdaftar atau tidak aktif.');
                        return response.json();
                    })
                    .then(data => {
                        namaField.value = data.nama;
                        pendudukIdField.value = data.id;
                        submitButton.disabled = false; // Tombol aktif setelah NIK valid
                    })
                    .catch(error => {
                        nikInput.classList.add('is-invalid');
                        nikError.textContent = error.message;
                    })
                    .finally(() => {
                        nikSpinner.style.display = 'none';
                    });
            }
        });
    }
});
</script>

</body>
</html>