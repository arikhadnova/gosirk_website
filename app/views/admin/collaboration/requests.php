<?php
$filter = $data['filter'] ?? 'all';
$counts = $data['counts'];
$qs = $filter !== 'all' ? '?status=' . $filter : '';
$tabs = [
    'all' => ['Semua', (int) $counts->total, 'secondary'],
    'followup' => ['Perlu Tindak Lanjut', (int) $counts->followup, 'warning'],
    'sent' => ['Terkirim', (int) $counts->sent, 'success'],
];
?>
<div class="admin-header-section d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <span class="admin-header-badge d-inline-block">DASHBOARD / DOCUMENTS / REQUESTS</span>
        <h1 class="fw-bold mb-0">Permintaan Dokumen</h1>
        <p class="text-muted small mb-0">Pengunjung yang meminta Executive Summary, Company Profile, atau Concept Note, beserta status pengirimannya.</p>
    </div>
    <a href="<?= BASE_URL; ?>admin/collaboration" class="btn btn-light text-secondary fw-bold px-3 rounded-pill">
        <i class="fas fa-folder-open me-2"></i> Kelola Dokumen
    </a>
</div>

<div class="d-flex flex-wrap gap-2 mb-3">
    <?php foreach ($tabs as $key => [$label, $count, $color]) : ?>
        <a href="<?= BASE_URL; ?>admin/collaboration_requests<?= $key !== 'all' ? '?status=' . $key : '' ?>"
           class="btn btn-sm rounded-pill px-3 <?= $filter === $key ? 'btn-dark' : 'btn-light border' ?>">
            <?= $label ?> <span class="badge rounded-pill ms-1 bg-<?= $color ?> <?= $color === 'warning' ? 'text-dark' : '' ?>"><?= $count ?></span>
        </a>
    <?php endforeach; ?>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4">Waktu</th>
                    <th>Pemohon</th>
                    <th>Instansi / Jabatan</th>
                    <th>Dokumen</th>
                    <th>Status Pengiriman</th>
                    <th class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($data['requests'])) : ?>
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <?= $filter === 'followup' ? 'Tidak ada permintaan yang perlu ditindaklanjuti.' : 'Belum ada permintaan dokumen.' ?>
                        </td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($data['requests'] as $req) :
                        [$statusLabel, $statusColor] = Collaboration_model::DELIVERY[$req->delivery_status] ?? Collaboration_model::DELIVERY['unknown'];
                        $needsAction = in_array($req->delivery_status, ['pending', 'failed'], true);
                        $docLabel = $req->doc_title ?: 'Dokumen dihapus';
                        $typeLabel = Collaboration_model::DOC_TYPES[$req->doc_type] ?? '-';
                        $mode = $req->doc_title ? (((int) ($req->doc_auto_send ?? 1)) === 1 ? 'Otomatis' : 'Manual') : '-';
                        $deliveredInfo = $req->delivered_at ? date('d M Y H:i', strtotime($req->delivered_at)) . ($req->delivered_by ? ' oleh ' . $req->delivered_by : '') : '';
                        $detail = [
                            'Waktu permintaan' => date('d M Y, H:i', strtotime($req->requested_at)) . ' WIB',
                            'Nama' => $req->name, 'Email' => $req->email,
                            'Instansi' => $req->organization ?: '-', 'Jabatan' => $req->jabatan ?: '-',
                            'Dokumen' => $docLabel, 'Tipe dokumen' => $typeLabel, 'Mode pengiriman' => $mode,
                            'Status pengiriman' => $statusLabel, 'Dikirim' => $deliveredInfo ?: '-',
                        ];
                    ?>
                        <tr class="<?= $needsAction ? 'table-warning bg-opacity-25' : '' ?>">
                            <td class="ps-4">
                                <div class="text-dark fw-bold small"><?= date('d M Y', strtotime($req->requested_at)); ?></div>
                                <div class="text-muted extra-small"><?= date('H:i', strtotime($req->requested_at)); ?> WIB</div>
                            </td>
                            <td>
                                <div class="fw-bold text-dark small"><?= htmlspecialchars($req->name); ?></div>
                                <a href="mailto:<?= htmlspecialchars($req->email); ?>" class="extra-small text-primary text-decoration-none"><?= htmlspecialchars($req->email); ?></a>
                            </td>
                            <td>
                                <div class="extra-small fw-bold text-dark"><?= htmlspecialchars($req->organization ?: '-'); ?></div>
                                <div class="extra-small text-muted"><?= htmlspecialchars($req->jabatan ?: '-'); ?></div>
                            </td>
                            <td>
                                <div class="small fw-bold text-dark"><?= htmlspecialchars($docLabel); ?></div>
                                <div class="extra-small text-muted"><?= $typeLabel ?> &middot; <?= $mode ?></div>
                            </td>
                            <td>
                                <span class="badge rounded-pill px-3 bg-<?= $statusColor ?> bg-opacity-10 text-<?= $statusColor === 'warning' ? 'warning-emphasis' : $statusColor ?>"><?= $statusLabel ?></span>
                                <?php if ($deliveredInfo) : ?><div class="extra-small text-muted mt-1"><?= htmlspecialchars($deliveredInfo) ?></div><?php endif; ?>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-inline-flex gap-2 align-items-center justify-content-end">
                                    <?php if ($req->doc_file && $needsAction) : ?>
                                        <form action="<?= BASE_URL; ?>admin/collaboration_request_send/<?= (int) $req->id . $qs ?>" method="POST" class="m-0">
                                            <button type="submit" class="btn btn-primary btn-action" title="Kirim PDF ke email pemohon"><i class="fas fa-paper-plane"></i> Kirim</button>
                                        </form>
                                        <form action="<?= BASE_URL; ?>admin/collaboration_request_mark/<?= (int) $req->id . $qs ?>" method="POST" class="m-0">
                                            <button type="submit" class="btn btn-light text-success btn-icon" title="Tandai sudah dikirim (lewat jalur lain)"><i class="fas fa-check"></i></button>
                                        </form>
                                    <?php elseif ($req->doc_file) : ?>
                                        <form action="<?= BASE_URL; ?>admin/collaboration_request_send/<?= (int) $req->id . $qs ?>" method="POST" class="m-0">
                                            <button type="submit" class="btn btn-light text-secondary btn-icon" title="Kirim ulang dokumen ke email pemohon"><i class="fas fa-redo"></i></button>
                                        </form>
                                    <?php endif; ?>
                                    <button type="button" class="btn btn-light text-primary btn-icon btn-request-detail" title="Detail"
                                            data-detail="<?= htmlspecialchars(json_encode($detail), ENT_QUOTES) ?>"><i class="fas fa-eye"></i></button>
                                    <a href="<?= BASE_URL; ?>admin/collaboration_request_delete/<?= (int) $req->id ?>" class="btn btn-light text-danger btn-icon btn-delete-confirm" title="Hapus"
                                       data-confirm-message="Permintaan dari <?= htmlspecialchars($req->email) ?> akan dihapus permanen."><i class="fas fa-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="requestDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Detail Permintaan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <dl class="row mb-0 small" id="requestDetailBody"></dl>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const body = document.getElementById('requestDetailBody');
    const modal = new bootstrap.Modal(document.getElementById('requestDetailModal'));
    document.querySelectorAll('.btn-request-detail').forEach((btn) => btn.addEventListener('click', () => {
        const detail = JSON.parse(btn.dataset.detail);
        body.innerHTML = '';
        Object.entries(detail).forEach(([k, v]) => {
            const dt = document.createElement('dt'); dt.className = 'col-5 text-muted fw-normal mb-2'; dt.textContent = k;
            const dd = document.createElement('dd'); dd.className = 'col-7 fw-bold text-dark mb-2 text-break'; dd.textContent = v;
            body.append(dt, dd);
        });
        modal.show();
    }));
});
</script>
