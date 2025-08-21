<!-- Modal untuk Import Data Penduduk -->
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Import Data Penduduk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('penduduk.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <p>Silakan unggah file Excel (.xlsx, .xls) atau CSV (.csv) dengan format yang sesuai. Pastikan baris pertama adalah header/judul kolom.</p>
                    {{-- Ganti link lama dengan yang ini --}}
                    <a href="{{ route('penduduk.template.download') }}">Unduh Template Format di Sini</a>
                    <hr>
                    <div class="form-group">
                        <label for="file_penduduk">Pilih File</label>
                        <input type="file" class="form-control-file" id="file_penduduk" name="file_penduduk" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Import Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
