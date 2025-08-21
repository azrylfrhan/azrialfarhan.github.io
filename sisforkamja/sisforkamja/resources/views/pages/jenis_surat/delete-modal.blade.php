<!-- Modal -->
<div class="modal fade" id="deleteModal-{{ $item->jenis_surat_id }}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
<div class="modal-dialog">
    <form action="{{ route('jenis-surat.update', $item->jenis_surat_id) }}" method="POST">
        @csrf
        @method('DELETE')
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="econfirmationDeleteModalLabel">Konfirmasi Hapus Jenis Surat</h5>
            <button type="button" class="btn btn-default" data-bs-dismiss="modal" aria-label="Close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <span>Apakah anda yakin ingin menghapus data ini?</span>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-outline-danger">Ya, Hapus!</button>
        </div>
        </div>
    </form>
</div>
</div>