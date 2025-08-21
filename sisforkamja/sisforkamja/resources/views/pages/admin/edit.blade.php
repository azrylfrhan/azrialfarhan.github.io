<!-- Modal Edit Pengguna -->
<div class="modal fade" id="editAdminModal-{{ $user->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Pengguna: {{ $user->name }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form action="{{ route('admin.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                    </div>

                    {{-- =============================================== --}}
                    {{-- === BAGIAN PASSWORD YANG DIPERBARUI === --}}
                    {{-- =============================================== --}}
                    <div class="form-group position-relative">
                        <label>Password Baru</label>
                        {{-- ID dibuat unik dengan $user->id --}}
                        <input type="password" name="password" id="password-edit-{{ $user->id }}" class="form-control" placeholder="Kosongkan jika tidak diubah">
                        {{-- Ikon mata juga memiliki ID unik dan data-target --}}
                        <i class="fas fa-eye position-absolute password-toggle-icon" 
                           id="togglePassword-edit-{{ $user->id }}" 
                           data-target="#password-edit-{{ $user->id }}"
                           style="right: 1rem; top: 75%; transform: translateY(-50%); cursor: pointer; color: #858796;"></i>
                    </div>
                    {{-- =============================================== --}}

                    <div class="form-group">
                        <label>Role</label>
                        <select name="role_id" class="form-control" required>
                            @foreach(App\Models\Role::all() as $role)
                                <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Lingkungan (Isi jika role Kepala Lingkungan)</label>
                        <input type="text" name="lingkungan" class="form-control" value="{{ $user->lingkungan }}" placeholder="Contoh: lingkungan1">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
