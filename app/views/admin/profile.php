<div class="admin-header-section mb-4">
    <span class="admin-header-badge d-inline-block">DASHBOARD / PROFIL</span>
    <h1 class="fw-bold mb-0">Profil Akun</h1>
    <p class="text-muted small mb-0">Perbarui informasi personal dan pengaturan keamanan akun Anda.</p>
</div>

<div class="row">
    <div class="col-12">
        <?php Flasher::flash(); ?>
    </div>
</div>

<style>
    .profile-card-left {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .profile-avatar-wrapper {
        position: relative;
        width: 150px;
        height: 150px;
        margin-bottom: 2rem;
    }

    .profile-avatar-preview {
        width: 150px !important;
        height: 150px !important;
        object-fit: cover;
        border-radius: 50% !important;
        border: 5px solid white !important;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
        background: #f8f9fa;
    }

    .btn-upload-avatar, .btn-delete-avatar {
        position: absolute;
        width: 42px;
        height: 42px;
        border-radius: 50%;
        border: 3px solid white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 10;
        bottom: 5px;
    }

    .btn-upload-avatar {
        right: -5px;
        background: #FF8F56;
        color: white;
    }

    .btn-upload-avatar:hover {
        background: #e67a42;
        transform: scale(1.1) rotate(15deg);
    }

    .btn-delete-avatar {
        left: -5px;
        background: #ff4757;
        color: white;
    }

    .btn-delete-avatar:hover {
        background: #e84118;
        transform: scale(1.1) rotate(-15deg);
    }

    #uploadSpinner {
        width: 1.2rem;
        height: 1.2rem;
    }
</style>

<div class="row g-4">
    <!-- Left Column: Account Overview -->
    <div class="col-lg-4">
        <div class="card p-4 h-100 border-0 shadow-sm">
            <div class="profile-card-left">
                <div class="profile-avatar-wrapper">
                    <img src="<?= ($user->photo && !empty($user->photo)) ? asset_v('img/profile/' . $user->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=FF8F56&color=fff&size=200' ?>" 
                         alt="Profile" 
                         id="profilePreview"
                         class="profile-avatar-preview">
                    
                    <label for="avatarUpload" class="btn-upload-avatar" title="Upload Foto">
                        <i class="fas fa-camera" id="uploadIcon"></i>
                        <div class="spinner-border text-light d-none" id="uploadSpinner" role="status"></div>
                    </label>

                    <?php if (!empty($user->photo)): ?>
                    <button type="button" class="btn-delete-avatar" id="btnDeletePhoto" title="Hapus Foto">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                    <?php endif; ?>
                    
                    <input type="file" id="avatarUpload" class="d-none" accept="image/jpeg,image/png">
                </div>
                
                <h4 class="fw-bold text-dark mb-1"><?= $user->name ?></h4>
                <p class="text-primary small fw-bold mb-4"><?= ucfirst($user->role) ?> Peran</p>
            </div>
            
            <div class="text-start border-top pt-4 mt-2">
                <div class="profile-info-item mb-3">
                    <div class="text-muted extra-small fw-bold text-uppercase mb-1">Username Aktif</div>
                    <div class="text-dark fw-bold"><?= $user->username ?></div>
                </div>
                <div class="profile-info-item mb-3">
                    <div class="text-muted extra-small fw-bold text-uppercase mb-1">Terakhir Login</div>
                    <div class="text-dark fw-bold">15 Jan 2026, 10:45</div>
                </div>
                <div class="profile-info-item">
                    <div class="text-muted extra-small fw-bold text-uppercase mb-1">Member Sejak</div>
                    <div class="text-dark fw-bold"><?= date('F Y', strtotime($user->created_at)) ?></div>
                </div>
            </div>

            <div class="mt-4">
                <div class="p-3 bg-light rounded-3">
                    <p class="extra-small text-muted mb-0">Pastikan informasi profil Anda valid untuk keperluan notifikasi sistem.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Edit Forms -->
    <div class="col-lg-8">
        <!-- Personal Information Card -->
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-white border-bottom p-4 d-flex align-items-center gap-3">
                <div class="stat-icon-box stat-icon-blue" style="width: 40px; height: 40px;">
                    <i class="fas fa-id-card"></i>
                </div>
                <h5 class="fw-bold mb-0">Informasi Personal</h5>
            </div>
            <div class="card-body p-4">
                <form action="" method="POST">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-dark">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" value="<?= $user->name ?>" <?= FormRules::attrs('profile', 'name', 'update') ?>>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-dark">Username</label>
                            <input type="text" name="username" class="form-control" value="<?= $user->username ?>" <?= FormRules::attrs('profile', 'username', 'update') ?>>
                        </div>
                        <div class="col-12 mt-4 text-end">
                            <button type="submit" class="btn btn-primary px-4 py-2 fw-bold">
                                <i class="fas fa-check-circle me-2"></i> Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Security Card -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom p-4 d-flex align-items-center gap-3">
                <div class="stat-icon-box stat-icon-orange" style="width: 40px; height: 40px;">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h5 class="fw-bold mb-0">Pengaturan Keamanan</h5>
            </div>
            <div class="card-body p-4">
                <form action="" method="POST">
                    <div class="row g-4">
                        <div class="col-12">
                            <label class="form-label small fw-bold text-dark">Password Saat Ini</label>
                            <input type="password" name="current_password" class="form-control" placeholder="Masukkan password lama untuk verifikasi" <?= FormRules::attrs('password_change', 'current_password', 'update') ?>>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-dark">Password Baru</label>
                            <input type="password" name="new_password" class="form-control" placeholder="Minimal 8 karakter" <?= FormRules::attrs('password_change', 'new_password', 'update') ?>>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-dark">Konfirmasi Password</label>
                            <input type="password" name="confirm_password" class="form-control" placeholder="Ulangi password baru" <?= FormRules::attrs('password_change', 'confirm_password', 'update') ?>>
                        </div>
                        <div class="col-12 mt-4 text-end">
                            <button type="submit" class="btn btn-primary px-4 py-2 fw-bold">
                                <i class="fas fa-key me-2"></i> Update Password
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('avatarUpload').addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
        const formData = new FormData();
        formData.append('photo', file);

        const icon = document.getElementById('uploadIcon');
        const spinner = document.getElementById('uploadSpinner');
        const preview = document.getElementById('profilePreview');

        // Show spinner
        icon.classList.add('d-none');
        spinner.classList.remove('d-none');

        fetch('<?= BASE_URL ?>admin/profile_upload', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                preview.src = data.url;
                
                // Update sidebar/header profile image if any
                const navAvatars = document.querySelectorAll('.nav-profile-img, .sidebar-profile-img');
                navAvatars.forEach(img => img.src = data.url);

                if (window.Swal) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Foto profil diperbarui',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                }
            } else {
                alert(data.message || 'Upload gagal');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat upload');
        })
        .finally(() => {
            // Hide spinner
            icon.classList.remove('d-none');
            spinner.classList.add('d-none');
        });
    }
});

// Delete Photo Logic
const btnDeletePhoto = document.getElementById('btnDeletePhoto');
if (btnDeletePhoto) {
    btnDeletePhoto.addEventListener('click', function() {
        if (window.Swal) {
            Swal.fire({
                title: 'Hapus Foto?',
                text: "Foto profil akan dihapus permanen.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#FF8F56',
                cancelButtonColor: '#adb5bd',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    processDelete();
                }
            });
        } else {
            if (confirm('Hapus foto profil?')) {
                processDelete();
            }
        }
    });

    function processDelete() {
        fetch('<?= BASE_URL ?>admin/profile_photo_delete', {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                location.reload();
            } else {
                alert(data.message || 'Gagal menghapus foto');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus foto');
        });
    }
}
</script>
