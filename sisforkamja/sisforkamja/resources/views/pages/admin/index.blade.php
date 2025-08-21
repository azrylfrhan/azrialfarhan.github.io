@extends('layout.app')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Manajemen Pengguna</h1>
</div>
<div class="d-sm-flex pb-3">
    <button type="button" class="btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#createAdminModal">
        <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Pengguna
    </button>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Lingkungan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td><span class="badge badge-info">{{ $user->role->name ?? 'N/A' }}</span></td>
                        <td>{{ $user->lingkungan ?? '-' }}</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editAdminModal-{{ $user->id }}">
                                <i class="fas fa-pen"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteAdminModal-{{ $user->id }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @include('pages.admin.edit', ['user' => $user])
                    @include('pages.admin.confirmation-delete', ['user' => $user])
                    @empty
                    <tr><td colspan="6" class="text-center">Tidak ada data pengguna.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('pages.admin.create')
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Skrip ini akan mencari SEMUA ikon mata dengan kelas .password-toggle-icon
    const togglePasswordIcons = document.querySelectorAll('.password-toggle-icon');

    // Tambahkan event listener untuk setiap ikon yang ditemukan
    togglePasswordIcons.forEach(function(icon) {
        icon.addEventListener('click', function () {
            // Ambil target input dari atribut 'data-target'
            const targetInput = document.querySelector(this.dataset.target);
            
            if (targetInput) {
                // Ubah tipe input dari password ke text atau sebaliknya
                const type = targetInput.getAttribute('type') === 'password' ? 'text' : 'password';
                targetInput.setAttribute('type', type);
                
                // Ubah ikon mata (dari mata terbuka ke mata tertutup)
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            }
        });
    });
});
</script>
@endpush
