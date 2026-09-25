<div class="admin-header-section mb-4">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
            <span class="admin-header-badge d-inline-block text-uppercase">DASHBOARD / CAPACITY BUILDING / VIDEO</span>
            <h1 class="fw-bold mb-0">Video Belajar Bersama GoSirk</h1>
            <p class="text-muted small mb-0">Kelola video yang ditampilkan di bagian "Belajar Bersama GoSirk".</p>
        </div>
        <a href="<?= BASE_URL; ?>admin/gi_videos_create" class="btn btn-primary rounded-pill px-4 py-2 shadow-sm">
            <i class="fas fa-plus-circle me-2"></i> Tambah Video GI
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <?php Flasher::flash(); ?>
    </div>
</div>

<?php $section = $data['section'] ?? null; ?>
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-header bg-white border-bottom p-4">
        <h5 class="fw-bold mb-0 text-dark">Pengaturan Section</h5>
        <small class="text-muted">Judul, subjudul, dan link YouTube di bagian "Belajar Bersama GoSirk". Terjemahan bahasa Inggris dibuat otomatis. Kosongkan untuk memakai teks default.</small>
    </div>
    <div class="card-body p-4">
        <form action="<?= BASE_URL; ?>admin/update_gi_video_section" method="POST">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold small text-dark">Judul</label>
                    <input type="text" name="title_id" class="form-control" placeholder="BELAJAR BERSAMA GOSIRK" value="<?= htmlspecialchars($section->title_id ?? '', ENT_QUOTES); ?>" <?= FormRules::attrs('gi_video_section', 'title_id', 'update') ?>>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold small text-dark">Link Tombol YouTube</label>
                    <input type="url" name="youtube_url" class="form-control" placeholder="https://youtube.com/@gosirk_institute" value="<?= htmlspecialchars($section->content_2_id ?? '', ENT_QUOTES); ?>" <?= FormRules::attrs('gi_video_section', 'youtube_url', 'update') ?>>
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold small text-dark">Subjudul</label>
                    <textarea name="content_id" class="form-control" rows="2" placeholder="Ruang pembelajaran terbuka untuk berbagi pengalaman, praktik baik, dan pengetahuan pengelolaan sampah dari lapangan." <?= FormRules::attrs('gi_video_section', 'content_id', 'update') ?>><?= htmlspecialchars($section->content_id ?? '', ENT_QUOTES); ?></textarea>
                </div>
                <div class="col-12 d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" id="videoSectionActive" <?= (!$section || (int) $section->is_active === 1) ? 'checked' : ''; ?>>
                        <label class="form-check-label small" for="videoSectionActive">Tampilkan section di halaman GoSirk Institute</label>
                    </div>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                        <i class="fas fa-save me-2"></i> Simpan Pengaturan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4" style="width: 150px;">Thumbnail</th>
                    <th>URL YouTube</th>
                    <th class="text-center">Prioritas</th>
                    <th class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($videos)) : ?>
                    <tr>
                        <td colspan="4" class="text-center py-5">
                            <div class="text-muted">Belum ada video GI.</div>
                        </td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($videos as $v) : ?>
                        <tr>
                            <td class="ps-4">
                                <div class="rounded-3 overflow-hidden bg-light" style="width: 120px; height: 67px; position: relative;">
                                    <?php 
                                    $thumb_url = '';
                                    if ($v->thumbnail) {
                                        $thumb_url = ASSETS_URL . 'img/gi/videos/' . $v->thumbnail;
                                    } else {
                                        // Attempt to get YouTube thumbnail
                                        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $v->url, $match);
                                        $yt_id = isset($match[1]) ? $match[1] : null;
                                        if ($yt_id) {
                                            $thumb_url = "https://img.youtube.com/vi/$yt_id/mqdefault.jpg";
                                        }
                                    }
                                    ?>
                                    <?php if ($thumb_url) : ?>
                                        <img src="<?= $thumb_url; ?>" class="w-100 h-100 object-fit-cover">
                                    <?php else : ?>
                                        <div class="d-flex h-100 align-items-center justify-content-center text-muted">
                                            <i class="fas fa-play-circle fs-4"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div class="position-absolute bottom-0 end-0 p-1">
                                        <span class="badge bg-dark bg-opacity-75 extra-small">YT</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <a href="<?= $v->url; ?>" target="_blank" class="small text-truncate d-inline-block" style="max-width: 420px;"><?= $v->url; ?></a>
                            </td>
                            <td class="text-center">
                                <?= $v->order_priority; ?>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="<?= BASE_URL; ?>admin/gi_videos_edit/<?= $v->id; ?>" class="btn btn-sm btn-light text-primary rounded-circle" style="width: 32px; height: 32px; padding: 0; display: flex; align-items: center; justify-content: center;" title="Edit"><i class="fas fa-edit small"></i></a>
                                    <a href="<?= BASE_URL; ?>admin/gi_videos_delete/<?= $v->id; ?>" class="btn btn-sm btn-light text-danger rounded-circle btn-delete-confirm" style="width: 32px; height: 32px; padding: 0; display: flex; align-items: center; justify-content: center;" title="Hapus"><i class="fas fa-trash small"></i></a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
