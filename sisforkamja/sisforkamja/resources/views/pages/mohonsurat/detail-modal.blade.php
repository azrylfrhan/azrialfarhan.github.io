    <!-- Modal Detail dan Ubah Status -->
    <div class="modal fade" id="modalDetail-{{ $item->permohonan_id }}" tabindex="-1" aria-labelledby="modalDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <form action="/surat/{{ $item->permohonan_id }}" method="POST"> 
        @csrf
        @method('PUT')
        <div class="modal-content border-0 shadow-lg rounded-5">
            
            <!-- Modal Header -->
            <div class="modal-header bg-primary text-white rounded-top">
            <h5 class="modal-title fw-bold" id="modalDetailLabel">Detail Permohonan</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <i class="fas fa-times text-white"></i> 
            </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body bg-light">
            <dl class="row">
                <dt class="col-sm-4">NIK :</dt>
                <dd class="col-sm-8">
                    {{-- Cek jika data penduduk ada sebelum menampilkannya --}}
                    @if ($item->penduduk)
                        {{ $item->penduduk->nik }}
                    @else
                        <span class="text-danger font-italic">Data Penduduk Telah Dihapus</span>
                    @endif
                </dd>

                <dt class="col-sm-4">Nama Pemohon :</dt>
                <dd class="col-sm-8">
                    @if ($item->penduduk)
                        {{ $item->penduduk->nama }}
                    @else
                        <span class="text-danger font-italic">Data Penduduk Telah Dihapus</span>
                    @endif
                </dd>

                <dt class="col-sm-4">Jenis Surat :</dt>
                <dd class="col-sm-8">{{ $item->jenisSurat->nama_surat }}</dd>

                <dt class="col-sm-4">Tgl Permohonan :</dt>
                <dd class="col-sm-8">{{ \Carbon\Carbon::parse($item->tanggal_permohonan)->translatedFormat('d F Y H:i') }} WITA</dd>

                <dt class="col-sm-4">Tgl Selesai :</dt>
                <dd class="col-sm-8">
                    @if($item->status === 'Selesai' && $item->tanggal_selesai)
                        {{ \Carbon\Carbon::parse($item->tanggal_selesai)->translatedFormat('d F Y H:i') }} WITA
                    @else
                        <span class="text-muted fst-italic">Belum selesai</span>
                    @endif
                </dd>

                <dt class="col-sm-4">No Telepon :</dt>
                <dd class="col-sm-8">{{ $item->no_telepon }}</dd>

                <dt class="col-sm-4">Status</dt>
                <dd class="col-sm-8">{{ $item->status }}</dd>

                <dt class="col-sm-4">Dokumen Upload</dt>
                <dd class="col-sm-8">
                @if($item->dokumen->isNotEmpty())
                    @foreach($item->dokumen as $dokumen)
                    <a href="{{ asset('storage/permohonan_dokumen/' . $dokumen->nama_file) }}" target="_blank" class="btn btn-sm btn-success mb-1 d-block">
                        <i class="fas fa-download me-1"></i> {{ $dokumen->nama_file }}
                    </a>
                    @endforeach
                @else
                    <span class="text-muted fst-italic">Tidak ada file</span>
                @endif
                </dd>
            </dl>

            <div class="mb-3">
                <label for="catatan" class="form-label fw-semibold">Catatan</label>
                <textarea name="catatan" id="catatan" class="form-control" rows="2" disabled>{{ $item->catatan }}</textarea>
            </div>
            <hr>
            <div class="form-group">
                <label for="catatan_admin" class="form-label fw-semibold">
                    Catatan / Alasan Penolakan (untuk Admin):
                </label>
                <textarea name="catatan_admin" id="catatan_admin" class="form-control" rows="3" 
                        placeholder="Isi di sini jika permohonan ditolak atau ada pesan khusus untuk pemohon..."></textarea>
            </div>
            </div>


            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                
                {{-- Tombol Generate Surat (tidak berubah) --}}
                @if($item->status === 'Diproses' || $item->status === 'Selesai')
                    <a href="/surat/{{ $item->permohonan_id }}/generate" target="_blank" class="btn btn-success">
                        <i class="fas fa-file-word"></i> Buat & Unduh Surat
                    </a>
                @endif

                {{-- =================================== --}}
                {{-- === LOGIKA BARU UNTUK TOMBOL AKSI === --}}
                {{-- =================================== --}}
                
                {{-- JIKA STATUS "MENUNGGU" --}}
                @if($item->status === 'Menunggu')
                    <button type="submit" name="status" value="Ditolak" class="btn btn-danger">Tolak</button>
                    <button type="submit" name="status" value="Diproses" class="btn btn-primary">Proses Permohonan</button>
                
                {{-- JIKA STATUS "DIPROSES" --}}
                @elseif($item->status === 'Diproses')
                    <button type="submit" name="status" value="Ditolak" class="btn btn-danger">Tolak</button>
                    <button type="submit" name="status" value="Selesai" class="btn btn-info">Tandai Selesai</button>

                {{-- =================================== --}}
                {{-- === KONDISI BARU YANG DITAMBAHKAN === --}}
                {{-- =================================== --}}
                {{-- JIKA STATUS "DITOLAK" --}}
                @elseif($item->status === 'Ditolak')
                    <button type="submit" name="status" value="Diproses" class="btn btn-primary">Proses Ulang Permohonan</button>
                @endif
                
                {{-- Jika status "Selesai", tidak ada tombol aksi update status yang muncul --}}
            </div>
        </div>
        </form>
    </div>
    </div>
