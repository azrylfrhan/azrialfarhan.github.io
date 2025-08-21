<!-- Modal -->
<div class="modal fade" id="confirmationDeleteModal-{{ $item->id }}" tabindex="-1" aria-labelledby="econfirmationDeleteModalLabel" aria-hidden="true">
<div class="modal-dialog">
    <form action="/penduduk/{{ $item->id }}" method="POST">
        @csrf
        @method('DELETE')
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="econfirmationDeleteModalLabel">Konfirmasi Hapus</h5>
            <button type="button" class="btn btn-default    " data-bs-dismiss="modal" aria-label="Close">
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