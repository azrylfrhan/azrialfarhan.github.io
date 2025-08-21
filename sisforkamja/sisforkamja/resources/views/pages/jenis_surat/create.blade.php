{{-- File: resources/views/pages/jenis_surat/create.blade.php (atau modal-tambah.blade.php) --}}

<div class="modal fade" id="tambahModal" tabindex="-1" role="dialog" aria-labelledby="tambahModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahModalLabel">Tambah Jenis Surat Baru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('jenis-surat.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    {{-- Bagian Informasi Dasar --}}
                    <div class="form-group">
                        <label for="nama_surat_tambah" class="font-weight-bold">Nama Surat</label>
                        <input type="text" id="nama_surat_tambah" name="nama_surat" class="form-control" placeholder="Contoh: Surat Keterangan Usaha" required>
                    </div>
                    <div class="form-group">
                        <label for="kode_surat_tambah" class="font-weight-bold">Kode Surat</label>
                        <input type="text" id="kode_surat_tambah" name="kode_surat" class="form-control" placeholder="Contoh: SKU" required>
                    </div>
                    <div class="form-group">
                        <label for="template_surat_tambah" class="font-weight-bold">Unggah Template (.docx)</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="template_surat_tambah" name="template_surat">
                            <label class="custom-file-label" for="template_surat_tambah">Pilih file...</label>
                        </div>
                        <small class="form-text text-muted">Opsional. Anda bisa mengunggah template surat di sini.</small>
                    </div>
                    <div class="form-group">
                        <label for="persyaratan_tambah" class="font-weight-bold">Persyaratan Dokumen</label>
                        <textarea id="persyaratan_tambah" name="persyaratan" class="form-control" rows="5" placeholder="Tulis setiap persyaratan di baris baru.&#10;Contoh:&#10;- Fotokopi KTP&#10;- Fotokopi Kartu Keluarga"></textarea>
                    </div>

                    <hr class="my-4">

                    {{-- Bagian Form Builder --}}
                    <h6 class="font-weight-bold">Input Tambahan (Formulir Dinamis)</h6>
                    <p class="small text-muted">Tambahkan input yang harus diisi warga untuk jenis surat ini. Biarkan kosong jika tidak ada.</p>

                    <div id="custom-fields-wrapper-tambah">
                        {{-- Wadah untuk input dinamis --}}
                    </div>

                    <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="add-field-btn-tambah">
                        <i class="fas fa-plus mr-1"></i> Tambah Input Baru
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-2"></i>Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- JavaScript untuk Form Builder dan File Input --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Pastikan script ini hanya berjalan sekali
    if (!document.getElementById('add-field-btn-tambah').dataset.initialized) {
        
        let fieldWrapper = document.getElementById('custom-fields-wrapper-tambah');
        let addBtn = document.getElementById('add-field-btn-tambah');
        let fieldIndex = 0;

        addBtn.addEventListener('click', function() {
            let newRow = document.createElement('div');
            newRow.className = 'row align-items-center custom-field-row mb-2';
            newRow.innerHTML = `
                <div class="col-md-5">
                    <input type="text" name="custom_fields[${fieldIndex}][label]" class="form-control" placeholder="Label Input (Contoh: Nama Usaha)" required>
                </div>
                <div class="col-md-3">
                    <select name="custom_fields[${fieldIndex}][type]" class="form-control">
                        <option value="text">Teks Singkat</option>
                        <option value="textarea">Teks Panjang</option>
                        <option value="date">Tanggal</option>
                        <option value="number">Angka</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text" name="custom_fields[${fieldIndex}][placeholder]" class="form-control" placeholder="Placeholder (Opsional)">
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger btn-sm remove-field-btn"><i class="fas fa-trash"></i></button>
                </div>
            `;
            fieldWrapper.appendChild(newRow);
            fieldIndex++;
        });

        fieldWrapper.addEventListener('click', function(e) {
            if (e.target && (e.target.matches('.remove-field-btn') || e.target.closest('.remove-field-btn'))) {
                e.target.closest('.custom-field-row').remove();
            }
        });

        // ===============================================
        // === BAGIAN BARU: JAVASCRIPT UNTUK FILE INPUT ===
        // ===============================================
        const fileInput = document.getElementById('template_surat_tambah');
        if (fileInput) {
            fileInput.addEventListener('change', function(e) {
                // Ambil nama file dari file yang dipilih
                let fileName = e.target.files[0] ? e.target.files[0].name : 'Pilih file...';
                // Cari label yang sesuai dan ubah teksnya
                let nextSibling = e.target.nextElementSibling;
                if (nextSibling) {
                    nextSibling.innerText = fileName;
                }
            });
        }
        // ===============================================

        addBtn.dataset.initialized = 'true';
    }
});
</script>