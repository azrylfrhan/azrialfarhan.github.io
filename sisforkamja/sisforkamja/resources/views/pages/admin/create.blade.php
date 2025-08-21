<!-- Modal Tambah Pengguna -->
<div class="modal fade" id="createAdminModal" tabindex="-1" role="dialog" aria-labelledby="createAdminModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createAdminModalLabel">Tambah Pengguna Baru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form action="{{ route('admin.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group position-relative">
                        <label>Password</label>
                        {{-- ID dibuat unik --}}
                        <input type="password" name="password" id="password-create" class="form-control" required>
                        {{-- Ikon mata memiliki kelas dan data-target --}}
                        <i class="fas fa-eye position-absolute password-toggle-icon" 
                            data-target="#password-create"
                            style="right: 1rem; top: 75%; transform: translateY(-50%); cursor: pointer; color: #858796;"></i>
                    </div>
                    {{-- =============================================== --}}

                    <div class="form-group">
                        <label>Role</label>
                        <select name="role_id" class="form-control" required>
                            <option value="">-- Pilih Role --</option>
                            @foreach(App\Models\Role::all() as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Lingkungan (Isi jika role Kepala Lingkungan)</label>
                        <input type="text" name="lingkungan" class="form-control" placeholder="Contoh: lingkungan1">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Cari SEMUA ikon mata dengan kelas 'password-toggle-icon'
        const toggleIcons = document.querySelectorAll('.password-toggle-icon');

        toggleIcons.forEach(function(icon) {
            icon.addEventListener('click', function() {
                // 1. Ambil selector target input dari atribut 'data-target'
                const targetInputSelector = this.getAttribute('data-target');
                const targetInput = document.querySelector(targetInputSelector);

                if (targetInput) {
                    // 2. Ganti tipe input dari password ke text atau sebaliknya
                    const type = targetInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    targetInput.setAttribute('type', type);

                    // 3. Ganti ikon mata
                    this.classList.toggle('fa-eye');
                    this.classList.toggle('fa-eye-slash');
                }
            });
        });
    });
</script>
@endpush
