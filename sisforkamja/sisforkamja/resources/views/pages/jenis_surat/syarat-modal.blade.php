<div class="modal fade" id="syaratModal-{{ $item->jenis_surat_id }}" tabindex="-1" role="dialog" aria-labelledby="syaratModalLabel-{{ $item->jenis_surat_id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="syaratModalLabel-{{ $item->jenis_surat_id }}">Persyaratan untuk:</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h6 class="font-weight-bold">{{ $item->nama_surat }}</h6>
                <hr>
                {{-- Tampilkan persyaratan sebagai daftar list --}}
                <ul>
                    @foreach(explode("\n", $item->persyaratan) as $syarat)
                        @if(trim($syarat) !== '')
                            <li>{{ trim($syarat) }}</li>
                        @endif
                    @endforeach
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>