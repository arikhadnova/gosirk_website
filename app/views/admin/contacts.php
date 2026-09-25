<div class="admin-header-section d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <span class="admin-header-badge d-inline-block">DASHBOARD / PESAN KONTAK</span>
        <h1 class="fw-bold mb-0">Kotak Masuk Kontak</h1>
        <p class="text-muted small mb-0">Kelola pesan masuk dari pengunjung website melalui halaman Kontak.</p>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <?php Flasher::flash(); ?>
    </div>
</div>

<div class="card border-0 shadow-sm mt-4" style="border-radius: 15px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 border-0 py-3" style="font-size: 11px; text-transform: uppercase; color: #64748b; letter-spacing: 0.05em;">Waktu</th>
                        <th class="border-0 py-3" style="font-size: 11px; text-transform: uppercase; color: #64748b; letter-spacing: 0.05em;">Pengirim</th>
                        <th class="border-0 py-3" style="font-size: 11px; text-transform: uppercase; color: #64748b; letter-spacing: 0.05em;">Email</th>
                        <th class="border-0 py-3" style="font-size: 11px; text-transform: uppercase; color: #64748b; letter-spacing: 0.05em;">Pesan</th>
                        <th class="border-0 py-3 text-center" style="font-size: 11px; text-transform: uppercase; color: #64748b; letter-spacing: 0.05em;">Status</th>
                        <th class="pe-4 border-0 py-3 text-end" style="font-size: 11px; text-transform: uppercase; color: #64748b; letter-spacing: 0.05em;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($contacts)) : ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">Belum ada pesan masuk.</td>
                        </tr>
                    <?php else : ?>
                        <?php foreach ($contacts as $c) : ?>
                            <tr class="<?= $c->is_read == 0 ? 'bg-primary bg-opacity-10 fw-bold' : '' ?>">
                                <td class="ps-4">
                                    <div class="small"><?= date('d M Y', strtotime($c->created_at)) ?></div>
                                    <div class="extra-small text-muted fw-normal"><?= date('H:i', strtotime($c->created_at)) ?></div>
                                </td>
                                <td><?= $c->name ?></td>
                                <td><a href="https://mail.google.com/mail/u/0/?view=cm&fs=1&to=<?= $c->email ?>&su=Re: Pesan dari <?= urlencode($c->name) ?>" target="_blank" class="text-primary text-decoration-none small"><?= $c->email ?></a></td>
                                <td>
                                    <div class="small text-truncate" style="max-width: 300px;"><?= $c->message ?></div>
                                </td>
                                <td class="text-center">
                                    <span class="badge <?= $c->is_read == 1 ? 'bg-light text-secondary' : 'bg-primary text-white' ?> extra-small rounded-pill px-3">
                                        <?= $c->is_read == 1 ? 'Read' : 'New' ?>
                                    </span>
                                </td>
                                <td class="pe-4 text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <button class="btn btn-sm btn-light text-primary" onclick="viewMessage(<?= $c->id ?>)" title="Lihat Pesan">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-light text-danger" onclick="confirmDelete(<?= $c->id ?>)" title="Hapus">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal View Message -->
<div class="modal fade" id="messageModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
            <div class="modal-header border-0 bg-light rounded-top-4 p-4">
                <h5 class="modal-title fw-bold" id="modalName">Detail Pesan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-4">
                    <small class="text-muted d-block mb-1">Dari:</small>
                    <div class="fw-bold" id="modalFrom"></div>
                    <div class="small text-primary" id="modalEmail"></div>
                </div>
                <div class="mb-4">
                    <small class="text-muted d-block mb-1">Waktu:</small>
                    <div class="small" id="modalTime"></div>
                </div>
                <div>
                    <small class="text-muted d-block mb-1">Pesan:</small>
                    <div class="p-3 bg-light rounded-3 small mt-2" style="white-space: pre-wrap; line-height: 1.6;" id="modalMessage"></div>
                </div>
            </div>
            <div class="modal-footer border-0 p-4 pt-0">
                <a href="" id="modalReply" target="_blank" class="btn btn-primary fw-bold px-4 rounded-pill">Balas via Email</a>
                <button type="button" class="btn btn-light fw-bold px-4 rounded-pill" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
function viewMessage(id) {
    fetch('<?= BASE_URL ?>admin/contacts_read/' + id)
        .then(response => response.json())
        .then(data => {
            document.getElementById('modalFrom').textContent = data.name;
            document.getElementById('modalEmail').textContent = data.email;
            document.getElementById('modalTime').textContent = new Date(data.created_at).toLocaleString('id-ID', { 
                day: '2-digit', month: 'long', year: 'numeric', 
                hour: '2-digit', minute: '2-digit' 
            });
            document.getElementById('modalMessage').textContent = data.message;
            document.getElementById('modalReply').href = 'https://mail.google.com/mail/u/0/?view=cm&fs=1&to=' + data.email + '&su=Re: Pesan dari ' + encodeURIComponent(data.name);
            
            const modal = new bootstrap.Modal(document.getElementById('messageModal'));
            modal.show();
            
            // Reload page on close if it was unread
            document.getElementById('messageModal').addEventListener('hidden.bs.modal', function () {
                location.reload();
            }, { once: true });
        });
}

function confirmDelete(id) {
    Swal.fire({
        title: 'Hapus Pesan?',
        text: "Pesann ini akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        borderRadius: '15px'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '<?= BASE_URL ?>admin/contacts_delete/' + id;
        }
    })
}
</script>
