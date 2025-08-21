@extends('layout.app')

@section('content')

<div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-800">Manajemen Pengaduan Warga</h1>
        <p class="mb-0 text-gray-600">Tinjau, kelola, dan tanggapi semua pengaduan yang masuk.</p>
    </div>
</div>

<div class="card shadow mb-4">

    <!-- Card Header - dengan Form Filter & Pencarian -->
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary mb-3">Daftar Pengaduan Masuk</h6>
        
        {{-- CATATAN: Form ini memerlukan penyesuaian di Controller untuk bisa berfungsi --}}
        <form action=" {{ route('pengaduan.index') }} " method="GET">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="filter_kategori" class="form-label small">Filter Kategori</label>
                    <select name="kategori" id="filter_kategori" class="form-select form-control">
                        <option value="">Semua Kategori</option>
                        {{-- Anda bisa mengisi opsi ini secara dinamis dari controller --}}
                        <option value="Infrastruktur" {{ request('kategori') == 'Infrastruktur' ? 'selected' : '' }}>Infrastruktur</option>
                        <option value="Kebersihan" {{ request('kategori') == 'Kebersihan' ? 'selected' : '' }}>Kebersihan</option>
                        <option value="Keamanan" {{ request('kategori') == 'Keamanan' ? 'selected' : '' }}>Keamanan</option>
                        <option value="Lainnya" {{ request('kategori') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filter_status" class="form-label small">Filter Status</label>
                    <select name="status" id="filter_status" class="form-select form-control">
                        <option value="">Semua Status</option>
                        <option value="Baru" {{ request('status') == 'Baru' ? 'selected' : '' }}>Baru</option>
                        <option value="Dalam Peninjauan" {{ request('status') == 'Dalam Peninjauan' ? 'selected' : '' }}>Dalam Peninjauan</option>
                        <option value="Ditindaklanjuti" {{ request('status') == 'Ditindaklanjuti' ? 'selected' : '' }}>Ditindaklanjuti</option>
                        <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="search_query" class="form-label small">Cari Kode atau Pelapor</label>
                    <input type="text" name="search" class="form-control" id="search_query" value="{{ request('search') }}" placeholder="Masukkan kode atau nama...">
                </div>
                <div class="col-md-2 d-flex">
                    <button type="submit" class="btn btn-primary w-100 me-2"><i class="fas fa-filter fa-sm"></i> Terapkan</button>
                    <a href=" {{ route('pengaduan.index') }} " class="btn btn-secondary" title="Reset Filter"><i class="fas fa-sync-alt"></i></a>
                </div>
            </div>
        </form>
    </div>

    <!-- Card Body - Tabel Data -->
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th>Kode</th>
                        <th>Pelapor</th>
                        <th>Judul Pengaduan</th>
                        <th>Kategori</th>
                        <th>Tanggal</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengaduans as $pengaduan)
                    <tr>
                        <td class="text-center">{{ $loop->iteration + $pengaduans->firstItem() - 1 }}</td>
                        <td><code class="font-weight-bold">{{ $pengaduan->kode_pengaduan }}</code></td>
                        <td class="text-truncate" title="{{ $pengaduan->penduduk->nama ?? 'N/A' }}">{{ $pengaduan->penduduk->nama ?? 'Warga Anonim' }}</td>
                        <td class="text-truncate" style="max-width: 250px;" title="{{ $pengaduan->judul }}">{{ $pengaduan->judul }}</td>
                        <td>
                            <span class="badge badge-pill badge-light border">{{ $pengaduan->kategori }}</span>
                        </td>
                        <td>{{ $pengaduan->created_at->format('d M Y') }}</td>
                        <td class="text-center">
                            @php
                                $statusConfig = [
                                    'Baru'      => ['class' => 'primary', 'icon' => 'fas fa-paper-plane'],
                                    'Dalam Peninjauan'  => ['class' => 'warning', 'icon' => 'fas fa-spinner fa-spin'],
                                    'Ditindaklanjuti'  => ['class' => 'warning', 'icon' => 'fas fa-spinner fa-spin'],
                                    'Selesai'   => ['class' => 'success', 'icon' => 'fas fa-check-circle'],
                                ];
                                $config = $statusConfig[$pengaduan->status] ?? ['class' => 'secondary', 'icon' => 'fas fa-question-circle'];
                            @endphp
                            <span class="badge badge-{{ $config['class'] }} p-2">
                                <i class="{{ $config['icon'] }} mr-1"></i>
                                {{ $pengaduan->status }}
                            </span>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#detailPengaduanModal-{{ $pengaduan->id }}" title="Lihat Detail & Tanggapi">
                                <i class="fas fa-search-plus"></i>
                            </button>
                        </td>
                    </tr>
                    @include('pages.pengaduan.modal-tanggapan')
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-gray-400 mb-3"></i>
                            <h5 class="text-gray-600">Tidak Ada Pengaduan</h4>
                            <p class="text-muted">Kotak masuk pengaduan masih kosong.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Card Footer - Pagination -->
    @if ($pengaduans->hasPages())
    <div class="card-footer d-flex justify-content-end">
        {{-- Pastikan menggunakan style bootstrap 5 --}}
        {{ $pengaduans->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection