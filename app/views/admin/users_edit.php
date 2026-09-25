<div class="admin-header-section mb-4">
    <div class="d-flex align-items-center gap-3">
        <a href="<?= BASE_URL; ?>admin/users" class="btn btn-light rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
            <i class="fas fa-arrow-left text-muted"></i>
        </a>
        <div>
            <span class="admin-header-badge d-inline-block">PENGATURAN / AKUN / EDIT</span>
            <h1 class="fw-bold mb-0">Edit Akun</h1>
            <p class="text-muted small mb-0">Perbarui informasi akun <strong><?= $user->username; ?></strong>.</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom p-4">
                <h5 class="fw-bold mb-0">Informasi Akun</h5>
            </div>
            <div class="card-body p-4">
                <form action="<?= BASE_URL; ?>admin/users_update" method="POST">
                    <input type="hidden" name="id" value="<?= $user->id; ?>">
                    
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-dark">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" value="<?= $user->name; ?>" placeholder="Contoh: John Doe" <?= FormRules::attrs('user', 'name', 'update') ?>>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-dark">Username</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-at"></i></span>
                            <input type="text" name="username" class="form-control border-start-0 ps-0" value="<?= $user->username; ?>" placeholder="johndoe" <?= FormRules::attrs('user', 'username', 'update') ?>>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-dark">Password Baru <span class="text-muted fw-normal">(Opsional)</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-key"></i></span>
                            <input type="password" name="password" id="password" class="form-control border-start-0 ps-0" placeholder="Kosongkan jika tidak ingin diubah" <?= FormRules::attrs('user', 'password', 'update') ?>>
                            <button class="btn btn-light border border-start-0 text-muted" type="button" id="togglePassword">
                                <i class="fas fa-eye-slash"></i>
                            </button>
                        </div>
                        <div class="text-muted extra-small mt-1">Kosongkan jika tidak ingin merubah password saat ini.</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-dark">Peran / Izin Akses</label>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="role-card border rounded-3 p-3 d-block cursor-pointer position-relative <?= $user->role == 'admin' ? 'border-primary' : '' ?>">
                                    <input type="radio" name="role" value="admin" class="position-absolute opacity-0" <?= $user->role == 'admin' ? 'checked' : '' ?>>
                                    <div class="d-flex align-items-center">
                                        <div class="role-icon bg-success bg-opacity-10 text-success rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                            <i class="fas fa-user-shield"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold small">Admin</div>
                                            <div class="extra-small text-muted">Akses penuh sistem</div>
                                        </div>
                                    </div>
                                    <div class="check-mark position-absolute top-0 end-0 p-2 text-success <?= $user->role == 'admin' ? '' : 'd-none' ?>">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                </label>
                            </div>
                            <div class="col-md-6">
                                <label class="role-card border rounded-3 p-3 d-block cursor-pointer position-relative <?= $user->role == 'editor' ? 'border-primary' : '' ?>">
                                    <input type="radio" name="role" value="editor" class="position-absolute opacity-0" <?= $user->role == 'editor' ? 'checked' : '' ?>>
                                    <div class="d-flex align-items-center">
                                        <div class="role-icon bg-info bg-opacity-10 text-info rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                            <i class="fas fa-user-edit"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold small">Editor</div>
                                            <div class="extra-small text-muted">Hanya kelola konten</div>
                                        </div>
                                    </div>
                                    <div class="check-mark position-absolute top-0 end-0 p-2 text-info <?= $user->role == 'editor' ? '' : 'd-none' ?>">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-5 py-3 fw-bold flex-grow-1">
                            <i class="fas fa-save me-2"></i> Perbarui Akun
                        </button>
                        <a href="<?= BASE_URL; ?>admin/users" class="btn btn-light px-4 py-3 text-muted fw-bold">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <?php if ($user->id == $_SESSION['user_id']) : ?>
            <div class="card border-0 bg-warning bg-opacity-10 shadow-none mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3 d-flex align-items-center text-orange">
                        <i class="fas fa-exclamation-triangle me-2"></i> Profil Anda Sendiri
                    </h6>
                    <p class="text-muted small mb-0">
                        Anda sedang mengedit akun Anda sendiri. Jika Anda mengubah <strong>Peran</strong> menjadi Editor, Anda akan kehilangan akses ke halaman Manajemen Akun ini setelah disimpan.
                    </p>
                </div>
            </div>
        <?php endif; ?>

        <div class="card border-0 bg-light shadow-none">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3 d-flex align-items-center">
                    <i class="fas fa-info-circle text-primary me-2"></i> Petunjuk
                </h6>
                <ul class="text-muted small mb-0 ps-3">
                    <li class="mb-2">Perubahan <strong>Username</strong> mungkin akan mempengaruhi sesi login jika akun sedang digunakan.</li>
                    <li class="mb-2">Jika lupa password, Anda bisa mengaturnya kembali di sini tanpa perlu tahu password lama (sebagai Admin).</li>
                    <li>Sistem menggunakan enkripsi <strong>BCRYPT</strong> yang aman untuk penyimpanan password.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
.role-card {
    transition: all 0.2s ease;
}
.role-card:hover {
    border-color: #0D4A7C !important;
    background: #f8f9fa;
}
.role-card.border-primary {
    border-color: #0D4A7C !important;
}
input[type="radio"]:checked ~ .check-mark {
    display: block !important;
}
input[type="radio"]:not(:checked) ~ .check-mark {
    display: none !important;
}
.cursor-pointer { cursor: pointer; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    if (togglePassword) {
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    }

    // Role selection styling
    const roleCards = document.querySelectorAll('.role-card');
    roleCards.forEach(card => {
        card.addEventListener('click', function() {
            roleCards.forEach(c => c.classList.remove('border-primary'));
            this.classList.add('border-primary');
        });
    });
});
</script>
