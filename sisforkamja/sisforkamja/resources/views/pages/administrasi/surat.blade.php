<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Layanan Pengurusan Surat - SI KAMJA</title>

    <!-- Aset CSS & Font -->
    <link href="{{ asset('template/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="{{ asset('template/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('img/logotomohon.png') }}">
</head>
<body style="background-color: #f0f2f5;">
    <section class="h-100">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card shadow-lg border-0 rounded-lg">
                        <div class="card-header bg-primary text-white text-center py-4">
                            <h1 class="h4 mb-0 font-weight-bold">Formulir Permohonan Surat</h1>
                        </div>
                        <div class="card-body p-4 p-md-5">
                            <form action="{{ route('administrasi.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                
                                <h5 class="font-weight-bold text-gray-800">Langkah 1: Data Diri Pemohon</h5>
                                <p class="small text-muted mb-3">
                                    {{-- Pesan dinamis berdasarkan status login --}}
                                    @if(auth()->check())
                                        Data Anda di bawah ini sudah terisi otomatis.
                                    @else
                                        Masukkan NIK Anda untuk mengisi nama secara otomatis.
                                    @endif
                                </p>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="nik">Nomor Induk Kependudukan (NIK)</label>
                                        <div class="input-group">
                                            {{-- =============================================== --}}
                                            {{-- === PERUBAHAN UNTUK AUTOFILL === --}}
                                            {{-- =============================================== --}}
                                            <input type="text" inputmode="numeric" name="nik" id="nik" class="form-control @error('nik') is-invalid @enderror" 
                                                   value="{{ $penduduk->nik ?? old('nik') }}" 
                                                   {{ $penduduk ? 'readonly' : '' }} required>
                                            {{-- =============================================== --}}
                                            <div class="input-group-append"><span class="input-group-text"><i id="nik-spinner" class="fas fa-spinner fa-spin" style="display: none;"></i></span></div>
                                        </div>
                                        @error('nik')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                        <div id="nik-error" class="text-danger mt-1 small"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="nama">Nama Lengkap</label>
                                        {{-- =============================================== --}}
                                        {{-- === PERUBAHAN UNTUK AUTOFILL === --}}
                                        {{-- =============================================== --}}
                                        <input type="text" name="nama" id="nama" readonly class="form-control bg-light" 
                                               value="{{ $penduduk->nama ?? 'Akan terisi otomatis...' }}" 
                                               placeholder="Akan terisi otomatis...">
                                        {{-- =============================================== --}}
                                    </div>
                                </div>

                                <hr class="my-4">

                                <h5 class="font-weight-bold text-gray-800">Langkah 2: Detail Permohonan</h5>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="jenis_surat_id">Jenis Surat yang Diperlukan</label>
                                        <div class="input-group">
                                            <select name="jenis_surat_id" id="jenis_surat_id" class="form-control @error('jenis_surat_id') is-invalid @enderror" required>
                                                <option value="" selected disabled>-- Pilih Jenis Surat --</option>
                                                @foreach($jenisSurat as $jenis)
                                                    <option value="{{ $jenis->jenis_surat_id }}" {{ old('jenis_surat_id') == $jenis->jenis_surat_id ? 'selected' : '' }}>
                                                        {{ $jenis->nama_surat }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-info" type="button" id="lihatPersyaratanBtn" data-toggle="modal" data-target="#persyaratanModal" disabled>
                                                    <i class="fas fa-list-alt"></i> Lihat Syarat
                                                </button>
                                            </div>
                                        </div>
                                        @error('jenis_surat_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>
                                    @if($penduduk)
                                        {{-- JIKA SUDAH LOGIN, MINTA VERIFIKASI --}}
                                        <div class="col-md-12 mb-3">
                                            <label for="verifikasi_telepon">Verifikasi No. Telepon</label>
                                            <input type="text" inputmode="numeric" name="verifikasi_telepon" id="verifikasi_telepon" class="form-control" 
                                                   placeholder="Masukkan 4 angka terakhir dari No. Telepon Anda" required maxlength="4">
                                            <small class="form-text text-muted">Untuk keamanan, masukkan 4 angka terakhir dari nomor telepon yang terdaftar di data kependudukan Anda.</small>
                                        </div>
                                    @else
                                        {{-- JIKA BELUM LOGIN, MINTA INPUT MANUAL --}}
                                        <div class="col-md-12 mb-3">
                                            <label for="no_telepon">No. Telepon (WhatsApp Aktif)</label>
                                            <input type="text" inputmode="numeric" name="no_telepon" id="no_telepon" class="form-control" 
                                                   value="{{ old('no_telepon') }}" placeholder="Contoh: 08123456789" required>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="form-group">
                                    <label for="catatan">Catatan (Keperluan, dll.)</label>
                                    <textarea name="catatan" id="catatan" rows="4" class="form-control @error('catatan') is-invalid @enderror">{{ old('catatan') }}</textarea>
                                    @error('catatan')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>

                                <div id="additional-fields-container" class="mt-3"></div>

                                <hr class="my-4">
                                
                                <h5 class="font-weight-bold text-gray-800">Langkah 3: Unggah Dokumen</h5>
                                <div class="form-group">
                                    <label for="file_dokumen_upload">Dokumen Pendukung</label>
                                    <input type="file" name="file_dokumen_upload[]" id="file_dokumen_upload" class="form-control-file @error('file_dokumen_upload.*') is-invalid @enderror" multiple>
                                    <small class="form-text text-muted">Pastikan dokumen sesuai persyaratan. Anda bisa memilih lebih dari satu file.</small>
                                    @error('file_dokumen_upload.*')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>

                                {{-- =============================================== --}}
                                {{-- === PERUBAHAN UNTUK AUTOFILL === --}}
                                {{-- =============================================== --}}
                                <input type="hidden" name="penduduk_id" id="penduduk_id" value="{{ $penduduk->id ?? '' }}" />
                                {{-- =============================================== --}}
                                <hr class="my-4">

                                <div class="row">
                                    <div class="col-md-6 mb-2 mb-md-0">
                                        <a href="/administrasi" class="btn btn-secondary btn-block">Kembali</a>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="submit" id="submit-button" class="btn btn-primary btn-block" {{ $penduduk ? '' : 'disabled' }}>Kirim Permohonan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal untuk Menampilkan Persyaratan -->
    <div class="modal fade" id="persyaratanModal" tabindex="-1" role="dialog" aria-labelledby="persyaratanModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="persyaratanModalLabel">Persyaratan Dokumen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <h6 class="font-weight-bold" id="namaSuratModal">Pilih jenis surat terlebih dahulu</h6>
                    <p class="small text-muted">Mohon siapkan dokumen-dokumen berikut dalam bentuk file digital (scan/foto) sebelum melanjutkan:<br><p class="font-weight-bold">Semua dokumen Berformat <span class="text-danger">PDF</span></p></p>
                    <ul class="list-group list-group-flush" id="daftarPersyaratan"></ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Saya Mengerti</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap & JQuery -->
    <script src="{{ asset('template/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('template/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // === BAGIAN 1: DEFINISI ELEMEN ===
        const nikInput = document.getElementById('nik');
        const nikError = document.getElementById('nik-error');
        const nikSpinner = document.getElementById('nik-spinner');
        const namaField = document.getElementById('nama');
        const pendudukIdField = document.getElementById('penduduk_id');
        const submitButton = document.getElementById('submit-button');
        
        const jenisSuratSelect = document.getElementById('jenis_surat_id');
        const lihatBtn = document.getElementById('lihatPersyaratanBtn');
        const daftarPersyaratan = document.getElementById('daftarPersyaratan');
        const namaSuratModal = document.getElementById('namaSuratModal');
        // === ELEMEN BARU UNTUK FORM DINAMIS ===
        const additionalFieldsContainer = document.getElementById('additional-fields-container');

        // === BAGIAN 2: EVENT LISTENER UNTUK NIK AUTOFILL (Tidak ada perubahan) ===
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
                        if (!response.ok) { throw new Error('NIK tidak terdaftar atau tidak aktif.'); }
                        return response.json();
                    })
                    .then(data => {
                        if (data && data.nama) {
                            namaField.value = data.nama;
                            pendudukIdField.value = data.id;
                            submitButton.disabled = false;
                        } else {
                            throw new Error('Data penduduk tidak valid.');
                        }
                    })
                    .catch(error => {
                        nikInput.classList.add('is-invalid');
                        nikError.textContent = error.message;
                    })
                    .finally(() => {
                        nikSpinner.style.display = 'none';
                    });
            } else if (nik.length > 0) {
                nikInput.classList.add('is-invalid');
                nikError.textContent = 'NIK harus terdiri dari 16 digit.';
            }
        });

        // === BAGIAN 3: EVENT LISTENER UNTUK JENIS SURAT (DIPERBARUI) ===
        jenisSuratSelect.addEventListener('change', function() {
            const suratId = this.value;
            lihatBtn.disabled = this.value === '';
            
            // =====================================================================
            // === PERUBAHAN 2: KOSONGKAN WADAH SETIAP KALI JENIS SURAT BERUBAH ===
            // =====================================================================
            additionalFieldsContainer.innerHTML = ''; 

            if (!suratId) return;

            // Logika untuk menampilkan persyaratan di modal (tidak ada perubahan)
            daftarPersyaratan.innerHTML = '<li class="list-group-item text-muted">Memuat...</li>';
            namaSuratModal.textContent = '...';

            fetch(`/api/jenis-surat/${suratId}/persyaratan`)
                .then(response => response.json())
                .then(data => {
                    // Menampilkan persyaratan (logika Anda tetap sama)
                    namaSuratModal.textContent = data.nama_surat;
                    daftarPersyaratan.innerHTML = '';
                    if (data.persyaratan && data.persyaratan.length > 0 && data.persyaratan[0].trim() !== '') {
                        data.persyaratan.forEach(item => {
                            if(item.trim() !== '') {
                                let li = document.createElement('li');
                                li.className = 'list-group-item';
                                li.textContent = item.trim().replace(/^- /, '');
                                daftarPersyaratan.appendChild(li);
                            }
                        });
                    } else {
                        let li = document.createElement('li');
                        li.className = 'list-group-item';
                        li.textContent = 'Tidak ada persyaratan khusus untuk surat ini.';
                        daftarPersyaratan.appendChild(li);
                    }

                    // ===============================================
                    // === PERUBAHAN 3: LOGIKA BARU UNTUK MEMBUAT FORM DINAMIS ===
                    // ===============================================
                    if (data.custom_fields && data.custom_fields.length > 0) {
                        const title = document.createElement('h6');
                        title.className = 'font-weight-bold text-gray-700 mt-4 border-top pt-3';
                        title.textContent = 'Informasi Tambahan';
                        additionalFieldsContainer.appendChild(title);

                        data.custom_fields.forEach(field => {
                            const formGroup = document.createElement('div');
                            formGroup.className = 'form-group';

                            const label = document.createElement('label');
                            label.htmlFor = field.name;
                            label.textContent = field.label;
                            formGroup.appendChild(label);

                            let input;
                            if (field.type === 'textarea') {
                                input = document.createElement('textarea');
                                input.rows = 3;
                            } else {
                                input = document.createElement('input');
                                input.type = field.type;
                            }
                            
                            input.name = `additional_data[${field.name}]`; 
                            input.id = field.name;
                            input.className = 'form-control';
                            input.placeholder = field.placeholder || '';
                            input.required = true;
                            
                            formGroup.appendChild(input);
                            additionalFieldsContainer.appendChild(formGroup);
                        });
                    }
                })
                .catch(error => {
                    console.error('Error fetching persyaratan:', error);
                    daftarPersyaratan.innerHTML = '<li class="list-group-item text-danger">Gagal memuat data persyaratan.</li>';
                });
        });
    });
    </script>
</body>
</html>