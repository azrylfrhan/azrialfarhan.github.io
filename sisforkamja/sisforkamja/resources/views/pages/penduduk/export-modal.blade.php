<!-- Modal untuk Export Data Penduduk -->
<div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-labelledby="exportModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exportModalLabel">Export Data Penduduk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('penduduk.export') }}" method="GET">
                <div class="modal-body">
                    <p>Pilih kriteria untuk memfilter data yang akan di-export. Biarkan 'Semua Data' untuk meng-export semua.</p>
                    <hr>
                    <div class="form-group">
                        <label for="filter_by">Filter Berdasarkan</label>
                        <select name="filter_by" id="filter_by" class="form-control">
                            <option value="all" selected>Semua Data</option>
                            <option value="lingkungan">Lingkungan</option>
                            <option value="status">Status Penduduk</option>
                            <option value="agama">Agama</option>
                            <option value="pekerjaan">Pekerjaan</option>
                            <option value="jenis_kelamin">Jenis Kelamin</option>
                        </select>
                    </div>
                    
                    {{-- Dropdown kedua yang akan berubah secara dinamis --}}
                    <div class="form-group" id="filter-value-wrapper" style="display: none;">
                        <label for="filter_value">Pilih Nilai</label>
                        <select name="filter_value" id="filter_value" class="form-control">
                            {{-- Opsi akan diisi oleh JavaScript --}}
                        </select>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-file-excel"></i> Export Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
