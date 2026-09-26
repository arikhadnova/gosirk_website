<div class="admin-header-section d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <span class="admin-header-badge d-inline-block">DASHBOARD / PENGATURAN / AKUN</span>
        <h1 class="fw-bold mb-0">Kelola Akun</h1>
        <p class="text-muted small mb-0">Kelola akun administrator dan editor untuk akses panel kontrol.</p>
    </div>
    <div>
        <a href="<?= BASE_URL; ?>admin/users_create" class="btn btn-primary px-4 py-2">
            <i class="fas fa-user-plus me-2"></i> Tambah Akun Baru
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <?php Flasher::flash(); ?>
    </div>
</div>

<!-- Search Area -->
<div class="row mb-4 g-3">
    <div class="col-lg-5">
        <div class="search-container shadow-sm">
            <i class="fas fa-search"></i>
            <input type="text" class="form-control" placeholder="Cari berdasarkan nama atau username..." id="userSearch">
        </div>
    </div>
</div>

<div class="table-responsive">
    <table class="table align-middle">
        <thead>
            <tr>
                <th style="width: 60px;">ID</th>
                <th>Nama Lengkap</th>
                <th>Username</th>
                <th>Peran</th>
                <th>Terdaftar Pada</th>
                <th class="text-end">Aksi</th>
            </tr>
        </thead>
        <tbody id="userTableBody">
            <?php if (empty($users)) : ?>
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <div class="text-muted">Belum ada akun lain. Klik "Tambah Akun Baru" untuk memulai.</div>
                    </td>
                </tr>
            <?php else : ?>
                <?php foreach ($users as $u) : ?>
                    <tr class="shadow-sm">
                        <td><span class="text-muted small">#<?= $u->id; ?></span></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; overflow: hidden;">
                                    <?php if ($u->photo) : ?>
                                        <img src="<?= asset_v('img/profile/' . $u->photo) ?>" class="w-100 h-100 object-fit-cover">
                                    <?php else : ?>
                                        <i class="fas fa-user text-muted opacity-50"></i>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold user-name"><?= $u->name; ?></h6>
                                    <?php if ($u->id == $_SESSION['user_id']) : ?>
                                        <span class="badge bg-primary bg-opacity-10 text-primary extra-small px-2 py-0">You</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td><code class="user-username"><?= $u->username; ?></code></td>
                        <td>
                            <?php if ($u->role == 'admin') : ?>
                                <span class="badge bg-success bg-opacity-10 text-success extra-small fw-bold text-uppercase">Admin</span>
                            <?php else : ?>
                                <span class="badge bg-info bg-opacity-10 text-info extra-small fw-bold text-uppercase">Editor</span>
                            <?php endif; ?>
                        </td>
                        <td><span class="text-muted extra-small"><?= date('d M Y, H:i', strtotime($u->created_at)); ?></span></td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="<?= BASE_URL; ?>admin/users_edit/<?= $u->id; ?>" class="btn btn-sm btn-light text-primary" title="Edit"><i class="fas fa-edit"></i></a>
                                <?php if ($u->id != $_SESSION['user_id']) : ?>
                                    <a href="<?= BASE_URL; ?>admin/users_delete/<?= $u->id; ?>" class="btn btn-sm btn-light text-danger btn-delete-confirm" title="Hapus" data-confirm-message="Akun ini akan dihapus permanen!"><i class="fas fa-trash"></i></a>
                                <?php else : ?>
                                    <button class="btn btn-sm btn-light text-muted" disabled title="Tidak bisa menghapus diri sendiri"><i class="fas fa-trash"></i></button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('userSearch');
    const tableRows = document.querySelectorAll('#userTableBody tr');

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const term = this.value.toLowerCase();
            tableRows.forEach(row => {
                const nameEl = row.querySelector('.user-name');
                const usernameEl = row.querySelector('.user-username');
                if (nameEl && usernameEl) {
                    const name = nameEl.innerText.toLowerCase();
                    const username = usernameEl.innerText.toLowerCase();
                    if (name.includes(term) || username.includes(term)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                }
            });
        });
    }

    // Delete confirmation with SweetAlert2
    const deleteButtons = document.querySelectorAll('.btn-delete-confirm');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const href = this.getAttribute('href');
            const message = this.getAttribute('data-confirm-message') || "Data ini akan dihapus permanen!";

            Swal.fire({
                title: 'Apakah anda yakin?',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#0D4A7C',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                }
            });
        });
    });
});
</script>
