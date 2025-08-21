<div class="modal fade" id="detailPengaduanModal-{{ $pengaduan->id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel-{{ $pengaduan->id }}" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document"> {{-- Diubah ke modal-xl untuk ruang lebih luas --}}
        <div class="modal-content">
            <form action="{{ route('pengaduan.update', $pengaduan->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalLabel-{{ $pengaduan->id }}">
                        <i class="fas fa-bullhorn mr-2"></i>
                        Detail Pengaduan: {{ $pengaduan->kode_pengaduan }}
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">

                        <div class="col-md-7 border-right">
                            <h6 class="font-weight-bold text-primary">Laporan Warga</h6>
                            <hr class="mt-2">

                            <div class="row">
                                <div class="col-sm-6 mb-3">
                                    <small class="text-muted d-block">Pelapor</small>
                                    <strong><i class="fas fa-user mr-2 text-gray-500"></i>{{ $pengaduan->penduduk->nama ?? 'Warga Anonim' }}</strong>
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <small class="text-muted d-block">Tanggal Laporan</small>
                                    <strong><i class="fas fa-calendar-alt mr-2 text-gray-500"></i>{{ $pengaduan->created_at->format('d M Y, H:i') }} WITA</strong>
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <small class="text-muted d-block">Judul Laporan</small>
                                    <strong>{{ $pengaduan->judul }}</strong>
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <small class="text-muted d-block">Kategori</small>
                                    <strong>{{ $pengaduan->kategori }}</strong>
                                </div>
                            </div>

                            <div class="mt-2">
                                <small class="text-muted d-block">Isi Laporan Lengkap</small>
                                <div class="bg-light p-3 rounded" style="white-space: pre-wrap;">{{ $pengaduan->isi_laporan }}</div>
                            </div>

                            @if($pengaduan->foto_bukti)
                            <div class="mt-3">
                                <small class="text-muted d-block mb-2">Foto Bukti</small>
                                <a href="{{ asset('storage/' . $pengaduan->foto_bukti) }}" target="_blank" title="Klik untuk melihat gambar penuh">
                                    <img src="{{ asset('storage/' . $pengaduan->foto_bukti) }}" class="img-fluid rounded border p-1" alt="Foto Bukti" style="max-height: 250px;">
                                </a>
                            </div>
                            @endif
                        </div>

                        <div class="col-md-5">
                            <h6 class="font-weight-bold text-success">Tindak Lanjut & Tanggapan Admin</h6>
                            <hr class="mt-2">

                            <div class="form-group">
                                <label for="status-{{$pengaduan->id}}"><strong>1. Ubah Status Pengaduan</strong></label>
                                <select name="status" id="status-{{$pengaduan->id}}" class="form-control" required>
                                    <option value="Baru" {{ request('status') == 'Baru' ? 'selected' : '' }}>Baru</option>
                                    <option value="Dalam Peninjauan" {{ request('status') == 'Dalam Peninjauan' ? 'selected' : '' }}>Dalam Peninjauan</option>
                                    <option value="Ditindaklanjuti" {{ request('status') == 'Ditindaklanjuti' ? 'selected' : '' }}>Ditindaklanjuti</option>
                                    <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                    <option value="Ditolak" {{ $pengaduan->status == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>

                                </select>
                            </div>
                            <div class="form-group">
                                <label for="tanggapan_admin-{{$pengaduan->id}}"><strong>2. Tulis Tanggapan</strong></label>
                                <textarea name="tanggapan_admin" id="tanggapan_admin-{{$pengaduan->id}}" class="form-control" rows="8" placeholder="Tulis tanggapan atau hasil tindak lanjut di sini..." required>{{ $pengaduan->tanggapan_admin }}</textarea>
                                <small class="form-text text-muted">Tanggapan ini akan dikirimkan sebagai notifikasi kepada pelapor.</small>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times mr-2"></i>Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane mr-2"></i>Simpan & Kirim Tanggapan</button>
                </div>
            </form>
        </div>
    </div>
</div>