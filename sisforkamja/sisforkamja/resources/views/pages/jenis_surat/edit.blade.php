{{-- File: resources/views/pages/jenis_surat/edit.blade.php (atau modal-edit.blade.php) --}}

<div class="modal fade" id="editModal-{{ $item->jenis_surat_id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel-{{ $item->jenis_surat_id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel-{{ $item->jenis_surat_id }}">Edit Jenis Surat</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('jenis-surat.update', $item->jenis_surat_id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    {{-- Bagian Informasi Dasar --}}
                    <div class="form-group">
                        <label class="font-weight-bold">Nama Surat</label>
                        <input type="text" name="nama_surat" class="form-control" value="{{ $item->nama_surat }}" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Kode Surat</label>
                        <input type="text" name="kode_surat" class="form-control" value="{{ $item->kode_surat }}" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Ubah Template (.docx)</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="template_surat">
                            <label class="custom-file-label">Pilih file baru...</label>
                        </div>
                        @if($item->template_surat)
                        <small class="form-text text-muted">Template saat ini: <code class="text-success">{{ $item->template_surat }}</code></small>
                        @endif
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Persyaratan Dokumen</label>
                        <textarea name="persyaratan" class="form-control" rows="5" placeholder="Tulis setiap persyaratan di baris baru...">{{ $item->persyaratan }}</textarea>
                    </div>

                    <hr class="my-4">

                    {{-- === BAGIAN BARU: FORM BUILDER UNTUK ADMIN === --}}
                    <h6 class="font-weight-bold">Input Tambahan (Formulir Dinamis)</h6>
                    <p class="small text-muted">Tambahkan input yang harus diisi warga untuk jenis surat ini. Biarkan kosong jika tidak ada.</p>

                    <div id="custom-fields-wrapper-{{ $item->jenis_surat_id }}">
                        @php
                            $customFields = json_decode($item->custom_fields, true) ?? [];
                        @endphp

                        @foreach($customFields as $index => $field)
                        <div class="row align-items-center custom-field-row mb-2">
                            <div class="col-md-5">
                                <input type="text" name="custom_fields[{{ $index }}][label]" class="form-control" placeholder="Label Input (Contoh: Nama Usaha)" value="{{ $field['label'] ?? '' }}" required>
                            </div>
                            <div class="col-md-3">
                                <select name="custom_fields[{{ $index }}][type]" class="form-control">
                                    <option value="text" {{ ($field['type'] ?? '') == 'text' ? 'selected' : '' }}>Teks Singkat</option>
                                    <option value="textarea" {{ ($field['type'] ?? '') == 'textarea' ? 'selected' : '' }}>Teks Panjang</option>
                                    <option value="date" {{ ($field['type'] ?? '') == 'date' ? 'selected' : '' }}>Tanggal</option>
                                    <option value="number" {{ ($field['type'] ?? '') == 'number' ? 'selected' : '' }}>Angka</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="custom_fields[{{ $index }}][placeholder]" class="form-control" placeholder="Placeholder (Opsional)" value="{{ $field['placeholder'] ?? '' }}">
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-danger btn-sm remove-field-btn"><i class="fas fa-trash"></i></button>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="add-field-btn-{{ $item->jenis_surat_id }}">
                        <i class="fas fa-plus mr-1"></i> Tambah Input Baru
                    </button>
                    {{-- === AKHIR DARI BAGIAN BARU === --}}

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-2"></i>Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- JavaScript untuk Form Builder --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (!document.getElementById('add-field-btn-{{ $item->jenis_surat_id }}').dataset.initialized) {

        let fieldWrapper = document.getElementById('custom-fields-wrapper-{{ $item->jenis_surat_id }}');
        let addBtn = document.getElementById('add-field-btn-{{ $item->jenis_surat_id }}');
        let fieldIndex = {{ count($customFields) }};

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

        addBtn.dataset.initialized = 'true';
    }
});
</script>