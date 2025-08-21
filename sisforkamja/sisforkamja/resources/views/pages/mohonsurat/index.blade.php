@extends('layout.app')

@section('content')

<div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-800">Permohonan Surat</h1>
        <p class="mb-0 text-gray-600">Daftar semua permohonan surat yang masuk dari warga.</p>
    </div>
        <a href="{{ url('/jenis-surat') }}" class="btn btn-secondary btn-icon-split mt-2 mt-md-0">
        <span class="icon text-white-50">
            <i class="fas fa-cogs"></i>
        </span>
    <span class="text">Kelola Jenis Surat</span>
</a>
</div>


<div class="card shadow mb-4">

    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary mb-3">Data Permohonan</h6>
        
        <form action="{{ url('/surat') }}" method="GET">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="search_by" class="form-label small">Cari Berdasarkan</label>
                    <select name="search_by" id="search_by" class="form-select form-control">
                        <option value="kode_pelacakan" {{ request('search_by') == 'kode_pelacakan' ? 'selected' : '' }}>Kode Pelacakan</option>
                        <option value="nama" {{ request('search_by') == 'nama' ? 'selected' : '' }}>Nama Pemohon</option>
                        <option value="tanggal" {{ request('search_by') == 'tanggal' ? 'selected' : '' }}>Tanggal</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="query" class="form-label small">Kata Kunci</label>
                    {{-- Input akan berubah jenisnya tergantung pilihan --}}
                    <input type="text" name="query" class="form-control" id="query" value="{{ request('query') }}" placeholder="Masukkan kata kunci...">
                </div>
                <div class="col-md-2 d-flex">
                    <button type="submit" class="btn btn-primary w-100 me-2"><i class="fas fa-search fa-sm"></i> Cari</button>
                    <a href="/surat" class="btn btn-secondary" title="Reset Filter"><i class="fas fa-sync-alt"></i></a>
                </div>
            </div>
        </form>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th>Kode Pelacakan</th>
                        <th>Nama Pemohon</th>
                        <th>Jenis Surat</th>
                        <th>Tanggal</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Prioritas</th> <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $item)
                    <tr>
                        {{-- Nomor dengan pagination --}}
                        <td class="text-center">{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}</td>
                        
                        {{-- Kode Pelacakan dengan gaya monospasi --}}
                        <td><code class="font-weight-bold">{{ $item->kode_pelacakan ?? '-' }}</code></td>

                        {{-- Nama Pemohon --}}
                        <td>
                            @if ($item->penduduk)
                                {{ $item->penduduk->nama }}
                                
                                {{-- Tampilkan badge jika data penduduk sudah di-soft delete --}}
                                @if ($item->penduduk->deleted_at)
                                    <span class="badge badge-danger ml-2">Telah Dihapus</span>
                                @endif
                            @else
                                <span class="text-muted">Data Penduduk Tidak Ditemukan</span>
                            @endif
                        </td>
                        
                        {{-- Jenis Surat --}}
                        <td class="text-truncate" style="max-width: 200px;" title="{{ $item->jenisSurat->nama_surat ?? '-' }}">
                            {{ $item->jenisSurat->nama_surat ?? '-' }}
                        </td>
                        
                        {{-- Tanggal Permohonan --}}
                        <td>{{ \Carbon\Carbon::parse($item->tanggal_permohonan)->format('d M Y') }}</td>

                        {{-- Status dengan Badge (Kode Anda sudah bagus) --}}
                        <td class="text-center">
                            @php
                                $statusConfig = [
                                    'Menunggu' => ['class' => 'warning', 'icon' => 'fas fa-clock'],
                                    'Ditolak'  => ['class' => 'danger',  'icon' => 'fas fa-times-circle'],
                                    'Diproses' => ['class' => 'primary', 'icon' => 'fas fa-spinner fa-spin'],
                                    'Selesai'  => ['class' => 'success', 'icon' => 'fas fa-check-circle'],
                                ];
                                $config = $statusConfig[$item->status] ?? ['class' => 'secondary', 'icon' => 'fas fa-question-circle'];
                            @endphp
                            <span class="badge badge-{{ $config['class'] }} p-2">
                                <i class="{{ $config['icon'] }} mr-1"></i>
                                {{ $item->status }}
                            </span>
                        </td>
                        
                        <td class="text-center">
                            @php
                                $badge_class = 'secondary'; // Warna default
                                if ($item->prioritas == 'Tinggi') $badge_class = 'danger';
                                if ($item->prioritas == 'Sedang') $badge_class = 'warning';
                                if ($item->prioritas == 'Rendah') $badge_class = 'success';
                            @endphp
                            <span class="badge badge-{{ $badge_class }} p-2">
                                {{ $item->prioritas ?? 'N/A' }}
                            </span>
                        </td>
                        {{-- Tombol Aksi --}}
                        <td class="text-center">
                            <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#modalDetail-{{ $item->permohonan_id }}" title="Lihat Detail Permohonan">
                                <i class="fas fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                    @include('pages.mohonsurat.detail-modal') 
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5"> <i class="fas fa-folder-open fa-3x text-gray-400 mb-3"></i>
                            <h5 class="text-gray-600">Belum ada data permohonan.</h4>
                            <p class="text-muted">Saat ada permohonan baru, datanya akan muncul di sini.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if ($data->hasPages())
    <div class="card-footer d-flex justify-content-end">
        {{ $data->links('pagination::bootstrap-5') }}
    </div>
    @endif

</div>

@endsection

@push('scripts')
{{-- SCRIPT ANDA TIDAK PERLU DIUBAH SAMA SEKALI, KARENA ID ELEMENT MASIH SAMA --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchBySelect = document.getElementById('search_by');
    const queryInput = document.getElementById('query');

    // Fungsi untuk mengubah tipe input
    function toggleInputType() {
        if (searchBySelect.value === 'tanggal') {
            queryInput.type = 'date';
            queryInput.placeholder = '';
        } else {
            queryInput.type = 'text';
            queryInput.placeholder = 'Masukkan kata kunci...';
        }
    }

    // Panggil fungsi saat halaman dimuat untuk set tipe input yang benar jika ada filter aktif
    toggleInputType();

    // Panggil fungsi setiap kali pilihan di dropdown berubah
    searchBySelect.addEventListener('change', toggleInputType);
});
</script>
@endpush