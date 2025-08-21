<!-- Modal Pengaturan Profil & Password -->
<div class="modal fade" id="settingsModal" tabindex="-1" role="dialog" aria-labelledby="settingsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="settingsModalLabel">Pengaturan Akun</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <!-- Navigasi Tab -->
                <ul class="nav nav-tabs nav-fill" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="true">
                            <i class="fas fa-user-edit mr-2"></i>Edit Profil
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="password-tab" data-toggle="tab" href="#password" role="tab" aria-controls="password" aria-selected="false">
                            <i class="fas fa-key mr-2"></i>Ubah Password
                        </a>
                    </li>
                </ul>

                <!-- Konten Tab -->
                <div class="tab-content p-4" id="myTabContent">
                    <!-- Konten Tab Profil -->
                    <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <form action="{{ route('profile.update', auth()->id()) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="profile_name">Nama Lengkap</label>
                                <input type="text" name="name" id="profile_name" class="form-control" value="{{ auth()->user()->name }}" required>
                            </div>
                            <div class="form-group">
                                <label for="profile_email">Email</label>
                                <input type="email" name="email" id="profile_email" class="form-control" value="{{ auth()->user()->email }}" readonly>
                                <small class="form-text text-muted">Email tidak dapat diubah.</small>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Simpan Perubahan Profil</button>
                        </form>
                    </div>

                    <!-- Konten Tab Ubah Password -->
                    <div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="password-tab">
                        <form action="{{ route('password.update', auth()->id()) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group position-relative">
                                <label for="current_password">Password Lama</label>
                                {{-- PERUBAHAN DI SINI --}}
                                <input type="password" name="current_password" id="current_password" class="form-control" required>
                                <i class="fas fa-eye position-absolute password-toggle-icon" data-target="#current_password" style="right: 1rem; top: 75%; transform: translateY(-50%); cursor: pointer;"></i>
                            </div>
                            
                            <div class="form-group position-relative">
                                <label for="password">Password Baru</label>
                                {{-- PERUBAHAN DI SINI --}}
                                <input type="password" name="password" id="password" class="form-control" required>
                                <i class="fas fa-eye position-absolute password-toggle-icon" data-target="#password" style="right: 1rem; top: 75%; transform: translateY(-50%); cursor: pointer;"></i>
                            </div>
                            
                            <div class="form-group position-relative">
                                <label for="password_confirmation">Konfirmasi Password Baru</label>
                                {{-- PERUBAHAN DI SINI --}}
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                                <i class="fas fa-eye position-absolute password-toggle-icon" data-target="#password_confirmation" style="right: 1rem; top: 75%; transform: translateY(-50%); cursor: pointer;"></i>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Simpan Password Baru</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
