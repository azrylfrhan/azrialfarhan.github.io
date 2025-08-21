@extends('layout.app')

@section('content')

{{-- ======================================================= --}}
{{-- === BAGIAN HEADER HALAMAN (DESAIN BARU) === --}}
{{-- ======================================================= --}}
<div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-800">Manajemen Jenis Surat</h1>
        <p class="mb-0 text-gray-600">Kelola semua jenis surat yang dapat diajukan oleh warga.</p>
    </div>
    <div class="btn-group mt-2 mt-md-0" role="group">
        <a href="{{ route('surat.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left fa-sm mr-2"></i>Kembali ke Permohonan
        </a>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambahModal">
            <i class="fas fa-plus fa-sm mr-2"></i>Tambah Jenis Surat
        </button>
    </div>
</div>


{{-- ======================================================= --}}
{{-- === BAGIAN TABEL DATA (DESAIN BARU) === --}}
{{-- ======================================================= --}}
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Jenis Surat yang Tersedia</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
                <thead class="thead-light">
                    <tr>
                        <th class="text-center">No</th>
                        <th>Nama Surat</th>
                        <th>Kode Surat</th>
                        <th class="text-center">Template Dokumen</th>
                        <th class="text-center">Persyaratan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jenis_surat as $item)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="font-weight-bold">{{ $item->nama_surat }}</td>
                        <td><code>{{ $item->kode_surat }}</code></td>
                        <td class="text-center">
                            @if($item->template_surat)
                                <a href="{{ asset('storage/templates/' . $item->template_surat) }}" target="_blank" class="badge badge-success p-2" title="Tersedia: {{ $item->template_surat }}">
                                    <i class="fas fa-check-circle mr-1"></i> Tersedia
                                </a>
                            @else
                                <span class="badge badge-secondary p-2" title="Template belum diunggah">
                                    <i class="fas fa-times-circle mr-1"></i> Tidak Ada
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if(!empty($item->persyaratan))
                                <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#syaratModal-{{ $item->jenis_surat_id }}" title="Lihat Persyaratan">
                                    <i class="fas fa-list-ul"></i>
                                </button>
                            @else
                                <span class="text-muted" title="Persyaratan masih kosong">
                                    <i class="fas fa-minus-circle"></i>
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editModal-{{ $item->jenis_surat_id }}" title="Edit">
                                    <i class="fas fa-pen"></i>
                                </button>
                                {{-- Tombol ini menargetkan deleteModal dari file delete-modal.blade.php Anda --}}
                                <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal-{{ $item->jenis_surat_id }}" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class="fas fa-folder-open fa-3x text-gray-400 mb-3"></i>
                            <h5 class="text-gray-600">Belum Ada Jenis Surat</h4>
                            <p class="text-muted">Gunakan tombol "Tambah Jenis Surat" untuk mulai membuat.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Memanggil modal tambah dari file Anda --}}
@include('pages.jenis_surat.create')

{{-- Melakukan loop untuk memanggil modal edit, hapus, dan syarat untuk setiap item --}}
@foreach($jenis_surat as $item)
    @include('pages.jenis_surat.edit', ['item' => $item])
    @include('pages.jenis_surat.delete-modal', ['item' => $item])
    @include('pages.jenis_surat.syarat-modal', ['item' => $item])
@endforeach

@endsection
