<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Uji Model Prediksi Prioritas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Formulir Uji Prediksi Prioritas Surat</h2>
        <p>Masukkan data pemohon untuk memprediksi prioritas permohonan surat.</p>

        {{-- Menampilkan Hasil Prediksi atau Error --}}
        @if(session('hasil_prediksi'))
            <div class="alert alert-success mt-4">
                <strong>Hasil Prediksi Prioritas:</strong> {{ session('hasil_prediksi') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger mt-4">
                <strong>Terjadi Error:</strong> <pre>{{ session('error') }}</pre>
            </div>
        @endif

        <form action="/prediksi" method="POST" class="mt-4">
            @csrf <div class="mb-3">
                <label for="nama_surat" class="form-label">Jenis Surat</label>
                <select name="nama_surat" id="nama_surat" class="form-select" required>
                    <option value="Surat Keterangan Usaha">Surat Keterangan Usaha</option>
                    <option value="Surat Keterangan Kematian">Surat Keterangan Kematian</option>
                    <option value="Surat Keterangan Pindah">Surat Keterangan Pindah</option>
                    <option value="Surat Keterangan Tidak Mampu">Surat Keterangan Tidak Mampu</option>
                    <option value="Surat Keterangan Domisili">Surat Keterangan Domisili</option>
                    <option value="Surat Keterangan Ahli Waris">Surat Keterangan Ahli Waris</option>
                </select>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                    <select name="jenis_kelamin" id="jenis_kelamin" class="form-select" required>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="status_perkawinan" class="form-label">Status Perkawinan</label>
                    <select name="status_perkawinan" id="status_perkawinan" class="form-select" required>
                        <option value="Belum Kawin">Belum Kawin</option>
                        <option value="Kawin">Kawin</option>
                        <option value="Cerai Hidup">Cerai Hidup</option>
                        <option value="Cerai Mati">Cerai Mati</option>
                    </select>
                </div>
            </div>

             <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="pekerjaan" class="form-label">Pekerjaan</label>
                    <input type="text" class="form-control" id="pekerjaan" name="pekerjaan" value="Wiraswasta" required>
                </div>
                 <div class="col-md-6 mb-3">
                    <label for="lingkungan" class="form-label">Lingkungan</tabel>
                    <input type="text" class="form-control" id="lingkungan" name="lingkungan" value="Lingkungan I" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="umur" class="form-label">Umur</label>
                <input type="number" class="form-control" id="umur" name="umur" value="30" required>
            </div>

            <button type="submit" class="btn btn-primary">Prediksi Prioritas</button>
        </form>
    </div>
</body>
</html>