<div class="admin-header-section mb-4">
    <span class="admin-header-badge mb-2 d-inline-block">DASHBOARD / RINGKASAN</span>
    <h1 class="fw-bold mb-0">Dashboard</h1>
    <p class="text-muted small mb-0">Selamat datang kembali! Berikut ringkasan performa website GoSirk hari ini.</p>
</div>

<!-- Quick Stats -->
<div class="row g-4 mb-5">
    <!-- Stat 1 -->
    <div class="col-xl-3 col-md-6">
        <div class="stat-card-modern card shadow-sm">
            <div class="stat-icon-box stat-icon-blue">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <div class="stat-label">Total Pengunjung</div>
                <div class="stat-value text-dark"><?= number_format($counts['total_visitors']); ?></div>
                <div class="stat-trend <?= $counts['visitor_stats']['increase'] >= 0 ? 'text-success' : 'text-danger'; ?>">
                    <i class="fas fa-arrow-<?= $counts['visitor_stats']['increase'] >= 0 ? 'up' : 'down'; ?> me-1"></i> 
                    <?= number_format(abs($counts['visitor_stats']['increase']), 1); ?>% 
                    <span class="text-muted fw-normal">dari bulan lalu</span>
                </div>
            </div>
        </div>
    </div>
    <!-- Stat 2 -->
    <div class="col-xl-3 col-md-6">
        <div class="stat-card-modern card shadow-sm">
            <div class="stat-icon-box stat-icon-orange">
                <i class="fas fa-envelope-open-text"></i>
            </div>
            <div>
                <div class="stat-label">Pesan Baru</div>
                <div class="stat-value text-dark"><?= $counts['unread_messages']; ?></div>
                <div class="stat-trend <?= $counts['unread_messages'] > 0 ? 'text-orange' : 'text-success'; ?>">
                    <i class="fas fa-envelope me-1"></i> <?= $counts['unread_messages'] > 0 ? 'Perlu dibalas' : 'Tidak ada pesan baru'; ?>
                </div>
            </div>
        </div>
    </div>
    <!-- Stat 3 -->
    <div class="col-xl-3 col-md-6">
        <div class="stat-card-modern card shadow-sm">
            <div class="stat-icon-box stat-icon-green">
                <i class="fas fa-newspaper"></i>
            </div>
            <div>
                <div class="stat-label">Artikel Blog</div>
                <div class="stat-value text-dark"><?= $counts['articles']; ?></div>
                <div class="stat-trend text-success">
                    <i class="fas fa-newspaper me-1"></i> Artikel di website
                </div>
            </div>
        </div>
    </div>
    <!-- Stat 4 -->
    <div class="col-xl-3 col-md-6">
        <div class="stat-card-modern card shadow-sm">
            <div class="stat-icon-box stat-icon-purple">
                <i class="fas fa-file-signature"></i>
            </div>
            <div>
                <div class="stat-label">Permintaan Dokumen</div>
                <div class="stat-value text-dark"><?= $counts['doc_requests']; ?></div>
                <?php if (!empty($counts['doc_followup'])) : ?>
                    <a href="<?= BASE_URL; ?>admin/collaboration_requests?status=followup" class="stat-trend text-decoration-none d-inline-block fw-bold" style="color: #fd7e14;">
                        <i class="fas fa-exclamation-circle me-1"></i> <?= $counts['doc_followup'] ?> <span class="fw-normal">perlu tindak lanjut &rarr;</span>
                    </a>
                <?php else : ?>
                    <div class="stat-trend text-success"><i class="fas fa-check-circle me-1"></i> Semua <span class="text-muted fw-normal">sudah ditangani</span></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
function time_elapsed_string($datetime, $full = false) {
    if ($datetime == 0) return 'baru saja';
    $now = new DateTime;
    $ago = new DateTime("@$datetime");
    $diff = $now->diff($ago);

    $w = floor($diff->d / 7);
    $d = $diff->d - ($w * 7);

    $string = array(
        'y' => 'tahun',
        'm' => 'bulan',
        'w' => 'minggu',
        'd' => 'hari',
        'h' => 'jam',
        'i' => 'menit',
        's' => 'detik',
    );
    
    // Manual map for properties
    $props = [
        'y' => $diff->y,
        'm' => $diff->m,
        'w' => $w,
        'd' => $d,
        'h' => $diff->h,
        'i' => $diff->i,
        's' => $diff->s,
    ];

    foreach ($string as $k => &$v) {
        if ($props[$k]) {
            $v = $props[$k] . ' ' . $v;
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' yang lalu' : 'baru saja';
}
?>

<!-- Recent Activity & Quick Actions -->
<div class="row g-4">
    <!-- Recent Activity Board -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0">Aktivitas Terkini</h5>
                <a href="#" class="btn btn-sm btn-light text-primary rounded-pill px-3 fw-bold" onclick="location.reload()">Muat Ulang</a>
            </div>
            <div class="activity-list" style="max-height: 580px; overflow-y: auto; padding-right: 10px;">
                <?php if (!empty($activities)): ?>
                    <?php foreach ($activities as $index => $activity): ?>
                        <div class="activity-item d-flex align-items-center <?= $index === count($activities)-1 ? 'border-0' : ''; ?> py-3">
                            <?php 
                                $dot_bg = 'bg-secondary';
                                $icon = 'fa-circle';
                                
                                switch($activity->action_type) {
                                    case 'CREATE': 
                                        $dot_bg = 'bg-success'; 
                                        $icon = 'fa-plus';
                                        break;
                                    case 'UPDATE': 
                                        $dot_bg = 'bg-info'; 
                                        $icon = 'fa-pen';
                                        break;
                                    case 'DELETE': 
                                        $dot_bg = 'bg-danger'; 
                                        $icon = 'fa-trash';
                                        break;
                                    case 'LOGIN': 
                                        $dot_bg = 'bg-primary'; 
                                        $icon = 'fa-sign-in-alt';
                                        break;
                                }
                            ?>
                            <div class="activity-icon-wrapper me-3">
                                <span class="badge rounded-circle p-2 <?= $dot_bg; ?>">
                                    <i class="fas <?= $icon; ?> text-white" style="font-size: 0.75rem;"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <span class="badge bg-light text-dark border me-2"><?= $activity->target_type; ?></span>
                                    <small class="text-muted" title="<?= date('d M Y H:i', strtotime($activity->created_at . ' UTC')); ?>">
                                        <?= time_elapsed_string(strtotime($activity->created_at . ' UTC')); ?>
                                    </small>
                                </div>
                                <p class="mb-1 text-dark" style="font-size: 0.95rem;"><?= $activity->description; ?></p>
                                <small class="text-muted">
                                    <i class="fas fa-user-circle me-1"></i> <?= $activity->user_name; ?>
                                </small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-history fs-1 text-muted opacity-25 mb-3"></i>
                        <p class="text-muted">Belum ada aktivitas tercatat.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Quick Actions Panel -->
    <div class="col-lg-4">
        <div class="card border-0 quick-action-card p-4 text-white">
            <h5 class="fw-bold mb-4">Aksi Cepat</h5>
            <div class="d-grid gap-3">
                <a href="<?= BASE_URL ?>admin/articles_create" class="btn quick-action-btn w-100 btn-lg">
                    <span>Tulis Artikel Baru</span>
                    <i class="fas fa-pen-nib"></i>
                </a>
                <a href="<?= BASE_URL ?>admin/articles_create/library" class="btn quick-action-btn w-100 btn-lg">
                    <span>Tambah Resource Library</span>
                    <i class="fas fa-book"></i>
                </a>
                <a href="<?= BASE_URL ?>admin/portfolio" class="btn quick-action-btn w-100 btn-lg">
                    <span>Kelola Portofolio</span>
                    <i class="fas fa-briefcase"></i>
                </a>
                <a href="<?= BASE_URL ?>admin/settings" class="btn quick-action-btn w-100 btn-lg">
                    <span>Pengaturan Situs</span>
                    <i class="fas fa-cog"></i>
                </a>
            </div>
        </div>
    </div>
</div>
