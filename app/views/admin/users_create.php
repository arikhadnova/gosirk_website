<div class="admin-header-section mb-4">
    <div class="d-flex align-items-center gap-3">
        <a href="<?= BASE_URL; ?>admin/users" class="btn btn-light rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
            <i class="fas fa-arrow-left text-muted"></i>
        </a>
        <div>
            <span class="admin-header-badge d-inline-block">PENGATURAN / AKUN / TAMBAH</span>
            <h1 class="fw-bold mb-0">Tambah Akun Baru</h1>
            <p class="text-muted small mb-0">Daftarkan administrator atau editor baru untuk panel kontrol.</p>
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
                <form action="<?= BASE_URL; ?>admin/users_store" method="POST">
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-dark">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: John Doe" <?= FormRules::attrs('user', 'name', 'store') ?>>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-dark">Username</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-at"></i></span>
                            <input type="text" name="username" class="form-control border-start-0 ps-0" placeholder="johndoe" <?= FormRules::attrs('user', 'username', 'store') ?>>
                        </div>
                        <div class="text-muted extra-small mt-1">Gunakan huruf kecil, angka, atau underscore.</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-dark">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-key"></i></span>
                            <input type="password" name="password" id="password" class="form-control border-start-0 ps-0" placeholder="Min. 6 karakter" <?= FormRules::attrs('user', 'password', 'store') ?>>
                            <button class="btn btn-light border border-start-0 text-muted" type="button" id="togglePassword">
                                <i class="fas fa-eye-slash"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-dark">Peran / Izin Akses</label>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="role-card border rounded-3 p-3 d-block cursor-pointer position-relative">
                                    <input type="radio" name="role" value="admin" class="position-absolute opacity-0" checked>
                                    <div class="d-flex align-items-center">
                                        <div class="role-icon bg-success bg-opacity-10 text-success rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                            <i class="fas fa-user-shield"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold small">Admin</div>
                                            <div class="extra-small text-muted">Akses penuh sistem</div>
                                        </div>
                                    </div>
                                    <div class="check-mark position-absolute top-0 end-0 p-2 text-success">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                </label>
                            </div>
                            <div class="col-md-6">
                                <label class="role-card border rounded-3 p-3 d-block cursor-pointer position-relative">
                                    <input type="radio" name="role" value="editor" class="position-absolute opacity-0">
                                    <div class="d-flex align-items-center">
                                        <div class="role-icon bg-info bg-opacity-10 text-info rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                            <i class="fas fa-user-edit"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold small">Editor</div>
                                            <div class="extra-small text-muted">Hanya kelola konten</div>
                                        </div>
                                    </div>
                                    <div class="check-mark position-absolute top-0 end-0 p-2 text-info d-none">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-5 py-3 fw-bold flex-grow-1">
                            <i class="fas fa-save me-2"></i> Buat Akun Baru
                        </button>
                        <a href="<?= BASE_URL; ?>admin/users" class="btn btn-light px-4 py-3 text-muted fw-bold">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 bg-light shadow-none">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3 d-flex align-items-center">
                    <i class="fas fa-info-circle text-primary me-2"></i> Petunjuk Keamanan
                </h6>
                <ul class="text-muted small mb-0 ps-3">
                    <li class="mb-2"><strong>Username</strong> harus unik dan tidak boleh digunakan oleh akun lain.</li>
                    <li class="mb-2"><strong>Password</strong> disarankan menggunakan kombinasi huruf, angka, dan simbol.</li>
                    <li class="mb-2"><strong>Admin</strong> memiliki akses untuk mengelola akun lain dan pengaturan sistem.</li>
                    <li><strong>Editor</strong> hanya memiliki akses untuk mengelola artikel, portfolio, dan konten lainnya.</li>
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
.role-card input:checked + .d-flex {
    color: #333;
}
input[type="radio"]:checked ~ .check-mark {
    display: block !important;
}
input[type="radio"]:not(:checked) ~ .check-mark {
    display: none !important;
}
.role-card input:checked + div + .check-mark {
    display: block;
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
            roleCards.forEach(c => c.style.borderColor = '');
            this.style.borderColor = '#0D4A7C';
        });
    });
});
</script>
