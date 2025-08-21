<!-- Modal Detail Penduduk -->
<div class="modal fade" id="detailPendudukModal-{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="detailPendudukModalLabel-{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailPendudukModalLabel-{{ $item->id }}">Detail Lengkap Penduduk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body bg-light">
                {{-- Menggunakan Definition List untuk tampilan yang rapi --}}
                <dl class="row">
                    <dt class="col-sm-4">Nama Lengkap</dt>
                    <dd class="col-sm-8 font-weight-bold">{{ $item->nama }}</dd>

                    <dt class="col-sm-4">Nomor Induk Kependudukan (NIK)</dt>
                    <dd class="col-sm-8">{{ $item->nik }}</dd>

                    <dt class="col-sm-4">Tempat, Tanggal Lahir</dt>
                    <dd class="col-sm-8">{{ $item->tempat_lahir }}, {{ \Carbon\Carbon::parse($item->tanggal_lahir)->translatedFormat('d F Y') }}</dd>

                    <dt class="col-sm-4">Jenis Kelamin</dt>
                    <dd class="col-sm-8">{{ ucfirst($item->jenis_kelamin) }}</dd>

                    <dt class="col-sm-4">Alamat</dt>
                    <dd class="col-sm-8">{{ $item->alamat }}</dd>

                    <dt class="col-sm-4">Lingkungan</dt>
                    <dd class="col-sm-8">{{ ucfirst($item->lingkungan) }}</dd>

                    <dt class="col-sm-4">Agama</dt>
                    <dd class="col-sm-8">{{ $item->agama }}</dd>

                    <dt class="col-sm-4">Status Perkawinan</dt>
                    <dd class="col-sm-8">{{ ucfirst($item->status_perkawinan) }}</dd>

                    <dt class="col-sm-4">Pekerjaan</dt>
                    <dd class="col-sm-8">{{ $item->pekerjaan }}</dd>

                    <dt class="col-sm-4">Nomor Telepon</dt>
                    <dd class="col-sm-8">{{ $item->no_telepon ?? '-' }}</dd>

                    <dt class="col-sm-4">Status Kependudukan</dt>
                    <dd class="col-sm-8">
                        <span class="badge badge-{{ $item->status == 'aktif' ? 'success' : ($item->status == 'meninggal' ? 'dark' : 'warning') }}">
                            {{ ucfirst($item->status) }}
                        </span>
                    </dd>
                </dl>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <a href="{{ url('/penduduk/' . $item->id . '/edit') }}" class="btn btn-warning">
                    <i class="fas fa-pen"></i> Edit Data
                </a>
            </div>
        </div>
    </div>
</div>
