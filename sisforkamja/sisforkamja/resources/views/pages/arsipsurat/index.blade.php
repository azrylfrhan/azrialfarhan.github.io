@extends('layout.app')

@section('content')

<div class="mb-4">
    <h1 class="h3 mb-1 text-gray-800">Arsip Surat Digital</h1>
    <p class="mb-0 text-gray-600">Daftar semua surat yang telah selesai diproses dan diterbitkan.</p>
</div>

<div class="card shadow mb-4">


    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th>Nomor Surat</th>
                        <th>Kode Pelacakan</th>
                        <th>Nama Pemohon</th>
                        <th>Jenis Surat</th>
                        <th>Tanggal Terbit</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dataArsip as $item)
                    <tr>
                        <td class="text-center">{{ ($dataArsip->currentPage() - 1) * $dataArsip->perPage() + $loop->iteration }}</td>
                        <td><code class="font-weight-bold">{{ $item->nomor_surat ?? 'N/A' }}</code></td>
                        <td><code>{{ $item->kode_pelacakan ?? 'N/A' }}</code></td>
                        <td class="text-truncate" style="max-width: 200px;" title="{{ $item->penduduk->nama ?? '-' }}">{{ $item->penduduk->nama ?? '-' }}</td>
                        <td class="text-truncate" style="max-width: 200px;" title="{{ $item->jenisSurat->nama_surat ?? '-' }}">{{ $item->jenisSurat->nama_surat ?? '-' }}</td>
                        {{-- Menggunakan translatedFormat agar nama bulan menjadi Bahasa Indonesia --}}
                        <td>{{ \Carbon\Carbon::parse($item->tanggal_selesai)->translatedFormat('d F Y') }}</td>
                        <td class="text-center">
                            {{-- Tombol untuk mengunduh ulang file surat --}}
                            <a href="{{ route('surat.generate', $item->permohonan_id) }}" target="_blank" class="btn btn-sm btn-success" title="Unduh Ulang Surat">
                                <i class="fas fa-download"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="fas fa-archive fa-3x text-gray-400 mb-3"></i>
                            <h5 class="text-gray-600">Arsip Digital Masih Kosong</h4>
                            <p class="text-muted">Surat yang telah selesai akan secara otomatis masuk ke halaman ini.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if ($dataArsip->hasPages())
    <div class="card-footer d-flex justify-content-end">
        {{-- Pastikan menggunakan style bootstrap 5 --}}
        {{ $dataArsip->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

@endsection