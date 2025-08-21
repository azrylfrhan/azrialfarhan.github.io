@extends('layout.app')

@section('content')

<div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-800">Manajemen Data Penduduk</h1>
        {{-- Deskripsi dinamis berdasarkan peran --}}
        @if(auth()->user()->role->name == 'Admin')
            <p class="mb-0 text-gray-600">Tambah, lihat, ubah, dan hapus data penduduk.</p>
        @else
            <p class="mb-0 text-gray-600">Lihat dan cari data penduduk di wilayah Anda.</p>
        @endif
    </div>
    
    {{-- Tombol Tambah, Unduh, dan Unggah HANYA untuk Admin --}}
    @if(auth()->user()->role->name == 'Admin')
    <div class="btn-group mt-2 mt-md-0" role="group" aria-label="Aksi Data Penduduk">
        <a href="/penduduk/create" class="btn btn-primary">
            <i class="fas fa-plus fa-sm"></i> Tambah
        </a>
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#exportModal">
            <i class="fas fa-file-excel fa-sm"></i> Unduh
        </button>
        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#importModal">
            <i class="fas fa-file-import fa-sm"></i> Unggah
        </button>
    </div>
    @endif
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary mb-3">Daftar Penduduk</h6>
        {{-- Form Pencarian tidak diubah --}}
        <form action="{{ route('penduduk.index') }}" method="GET">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="search_by" class="form-label small">Cari Berdasarkan</label>
                    <select name="search_by" id="search_by" class="form-select form-control">
                        <option value="nama" {{ request('search_by') == 'nama' ? 'selected' : '' }}>Nama</option>
                        <option value="nik" {{ request('search_by') == 'nik' ? 'selected' : '' }}>NIK</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="search_query" class="form-label small">Kata Kunci</label>
                    <input type="text" name="search_query" class="form-control" id="search_query" value="{{ request('search_query') }}" placeholder="Masukkan nama atau NIK...">
                </div>
                <div class="col-md-2 d-flex">
                    <button type="submit" class="btn btn-primary w-100 me-2"><i class="fas fa-search fa-sm"></i></button>
                    <a href="{{ route('penduduk.index') }}" class="btn btn-secondary" title="Reset Filter"><i class="fas fa-sync-alt"></i></a>
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
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>No Telepon</th>
                        <th>Lingkungan</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($penduduks as $item)
                    <tr>
                        <td class="text-center">{{ ($penduduks->currentPage() - 1) * $penduduks->perPage() + $loop->iteration }}</td>
                        <td><code class="font-weight-bold">{{ $item->nik }}</code></td>
                        <td class="text-truncate" title="{{$item->nama}}">{{ $item->nama }}</td>
                        <td>{{ $item->no_telepon ?? '-' }}</td>
                        <td>{{ $item->lingkungan ?? '-' }}</td>
                        <td class="text-center">
                            @php
                                $statusConfig = [
                                    'aktif'     => ['class' => 'success', 'icon' => 'fas fa-check-circle'],
                                    'meninggal' => ['class' => 'dark',    'icon' => 'fas fa-cross'],
                                    'pindah'    => ['class' => 'warning', 'icon' => 'fas fa-arrow-right'],
                                ];
                                $config = $statusConfig[$item->status] ?? ['class' => 'secondary', 'icon' => 'fas fa-question-circle'];
                            @endphp
                            <span class="badge badge-{{ $config['class'] }} p-2" title="{{ ucfirst($item->status) }}"><i class="{{ $config['icon'] }}"></i></span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                {{-- Tombol Lihat Detail: Terlihat oleh semua peran --}}
                                <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#detailPendudukModal-{{ $item->id }}" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                
                                {{-- Tombol Edit & Hapus: HANYA untuk Admin --}}
                                @if(auth()->user()->role->name == 'Admin')
                                    <a href="{{ url('/penduduk/' . $item->id . '/edit') }}" class="btn btn-sm btn-warning" title="Edit Data">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#confirmationDeleteModal-{{ $item->id }}" title="Hapus Data">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    
                    {{-- Include modal detail untuk semua peran --}}
                    @include('pages.penduduk.detail-modal')

                    {{-- Include modal konfirmasi hapus HANYA untuk Admin --}}
                    @if(auth()->user()->role->name == 'Admin')
                        @include('pages.penduduk.confirmation-delete')
                    @endif
                    
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="fas fa-users-slash fa-3x text-gray-400 mb-3"></i>
                            <h5 class="text-gray-600">Belum Ada Data Penduduk</h4>
                            <p class="text-muted">Data penduduk tidak ditemukan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if ($penduduks->hasPages())
    <div class="card-footer d-flex justify-content-end">
        {{ $penduduks->links('pagination::bootstrap-5') }}
    </div>
    @endif

</div>

{{-- Tampilkan Modal Ekspor dan Impor HANYA untuk Admin --}}
@if(auth()->user()->role->name == 'Admin')
    @include('pages.penduduk.export-modal')
    @include('pages.penduduk.import-modal')
@endif

@endsection 
 


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Ambil semua elemen yang kita butuhkan
    const filterBySelect = document.getElementById('filter_by');
    const filterValueWrapper = document.getElementById('filter-value-wrapper');
    const filterValueSelect = document.getElementById('filter_value');

    // 2. Simpan semua pilihan yang dikirim dari controller ke dalam variabel JavaScript
    const filterOptions = @json($filterOptions);

    // 3. Tambahkan event listener untuk mendengarkan perubahan pada dropdown pertama
    filterBySelect.addEventListener('change', function() {
        const selectedCategory = this.value;

        // Kosongkan dropdown kedua setiap kali ada perubahan
        filterValueSelect.innerHTML = '';

        // Jika user memilih kategori selain "Semua Data"
        if (selectedCategory !== 'all') {
            // Ambil daftar pilihan untuk kategori yang dipilih
            const options = filterOptions[selectedCategory];

            // Tambahkan pilihan default
            let defaultOption = document.createElement('option');
            defaultOption.text = `Pilih ${selectedCategory.replace('_', ' ')}...`;
            defaultOption.value = '';
            filterValueSelect.appendChild(defaultOption);

            // Isi dropdown kedua dengan pilihan yang sesuai
            if (options) {
                options.forEach(function(optionValue) {
                    let option = document.createElement('option');
                    option.text = optionValue.charAt(0).toUpperCase() + optionValue.slice(1); // Buat huruf awal jadi kapital
                    option.value = optionValue;
                    filterValueSelect.appendChild(option);
                });
            }
            
            // Tampilkan dropdown kedua
            filterValueWrapper.style.display = 'block';
        } else {
            // Jika user memilih "Semua Data", sembunyikan dropdown kedua
            filterValueWrapper.style.display = 'none';
        }
    });
});
</script>
@endpush