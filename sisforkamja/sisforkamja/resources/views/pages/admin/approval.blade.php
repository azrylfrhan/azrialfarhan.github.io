@extends('layout.app')

@section('content')

{{-- Header Halaman --}}
<div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-800">Persetujuan Pengguna Warga</h1>
        <p class="mb-0 text-gray-600">Daftar akun warga yang sedang menunggu persetujuan.</p>
    </div>
</div>

{{-- Tabel Daftar Pengguna Pending --}}
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Menunggu Persetujuan</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th>Nama</th>
                        <th>NIK</th>
                        <th class="text-center">Tanggal Registrasi</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pendingUsers as $user)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $user->name }}</td>
                        <td><code>{{ $user->nik }}</code></td>
                        <td class="text-center">{{ $user->created_at->translatedFormat('d F Y') }}</td>
                        <td class="text-center">
                            {{-- === PERUBAHAN DI SINI === --}}
                            <form id="approval-form-{{ $user->id }}" action="{{ route('admin.approve.user', $user->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                {{-- Tombol submit sekarang tidak punya onclick --}}
                                <button type-="submit" class="btn btn-sm btn-success">
                                    <i class="fas fa-check mr-1"></i> Setujui
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <i class="fas fa-check-circle fa-3x text-gray-400 mb-3"></i>
                            <h5 class="text-gray-600">Tidak ada pengguna yang menunggu persetujuan.</h4>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cari semua formulir persetujuan
    const approvalForms = document.querySelectorAll('form[id^="approval-form-"]');
    
    approvalForms.forEach(form => {
        form.addEventListener('submit', function(event) {
            // Hentikan pengiriman formulir sementara
            event.preventDefault();
            
            // Tampilkan dialog konfirmasi
            Swal.fire({
                title: 'Anda yakin?',
                text: "Anda akan menyetujui pengguna ini.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, setujui!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                // Jika pengguna mengklik "Ya", kirimkan formulirnya
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});
</script>
@endpush