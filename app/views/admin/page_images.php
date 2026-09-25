<?php
$groups = [];
foreach (PageImages::SLOTS as $key => [$page, $label, $default]) {
    $groups[$page][$key] = $label;
}
$pageNames = array_keys($groups);
$slug = fn($s) => 'pg-' . substr(md5($s), 0, 8);
?>
<style>
    .pi-tabs .nav-link { border-radius: 999px; padding: .35rem .9rem; font-size: .85rem; color: #5b6475; }
    .pi-tabs .nav-link.active { background: #212529; color: #fff; }
    .pi-card { border-radius: 14px; overflow: hidden; background: #fff; box-shadow: 0 1px 3px rgba(16, 24, 40, .06); }
    .pi-thumb { position: relative; height: 130px; background: #f4f6f9; cursor: pointer; display: block; margin: 0; }
    .pi-thumb img { width: 100%; height: 100%; }
    .pi-thumb .pi-overlay { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; gap: .4rem;
        background: rgba(17, 24, 39, .45); color: #fff; font-size: .85rem; font-weight: 600; opacity: 0; transition: opacity .15s; }
    .pi-thumb:hover .pi-overlay, .pi-thumb:focus-within .pi-overlay { opacity: 1; }
    .pi-missing { position: absolute; inset: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #dc3545; font-size: .78rem; font-weight: 600; }
</style>

<div class="admin-header-section mb-4">
    <span class="admin-header-badge d-inline-block">DASHBOARD / KONTEN / GAMBAR HALAMAN</span>
    <h1 class="fw-bold mb-0">Gambar Halaman</h1>
    <p class="text-muted small mb-0">Klik gambar untuk menggantinya. JPG, PNG, atau WEBP.</p>
</div>

<ul class="nav pi-tabs gap-1 mb-4" role="tablist">
    <?php foreach ($pageNames as $i => $page) : ?>
        <li class="nav-item" role="presentation">
            <button class="nav-link <?= $i === 0 ? 'active' : '' ?>" data-bs-toggle="pill" data-bs-target="#<?= $slug($page) ?>" type="button" role="tab"><?= htmlspecialchars($page) ?></button>
        </li>
    <?php endforeach; ?>
</ul>

<div class="tab-content">
    <?php foreach ($groups as $page => $slots) : ?>
        <div class="tab-pane fade <?= $page === $pageNames[0] ? 'show active' : '' ?>" id="<?= $slug($page) ?>" role="tabpanel">
            <div class="row g-3">
                <?php foreach ($slots as $key => $label) :
                    $custom = PageImages::custom($key);
                    $contain = strpos($key, 'logo') !== false || strpos($key, 'banner') !== false;
                ?>
                    <div class="col-xl-3 col-lg-4 col-sm-6" id="slot-<?= htmlspecialchars($key) ?>">
                        <div class="pi-card h-100">
                            <form action="<?= BASE_URL; ?>admin/page_images_update" method="POST" enctype="multipart/form-data" class="m-0">
                                <input type="hidden" name="slot" value="<?= htmlspecialchars($key) ?>">
                                <label class="pi-thumb" title="Ganti gambar">
                                    <img src="<?= PageImages::attr($key) ?>" alt="" loading="lazy"
                                         style="object-fit: <?= $contain ? 'contain; padding: 14px' : 'cover' ?>;"
                                         onerror="this.style.display='none'; this.parentElement.querySelector('.pi-missing').classList.remove('d-none');">
                                    <span class="pi-missing d-none"><i class="fas fa-image fs-4 mb-1 opacity-50"></i>Belum ada gambar</span>
                                    <span class="pi-overlay"><i class="fas fa-upload"></i> Ganti</span>
                                    <input type="file" name="image" accept="image/jpeg,image/png,image/webp" class="d-none" onchange="if (this.files.length) this.form.submit()">
                                </label>
                            </form>
                            <div class="px-3 py-2 d-flex align-items-center gap-2">
                                <span class="small text-dark text-truncate" title="<?= htmlspecialchars($label) ?>"><?= htmlspecialchars($label) ?></span>
                                <?php if ($custom) : ?>
                                    <form action="<?= BASE_URL; ?>admin/page_images_update" method="POST" class="m-0 ms-auto">
                                        <input type="hidden" name="slot" value="<?= htmlspecialchars($key) ?>">
                                        <input type="hidden" name="reset" value="1">
                                        <button type="submit" class="btn btn-link p-0 text-muted text-decoration-none small" title="Kembalikan ke gambar bawaan"><i class="fas fa-undo"></i></button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<script>
// Reopen the tab of the slot that was just changed
document.addEventListener('DOMContentLoaded', function () {
    const target = location.hash && document.getElementById(decodeURIComponent(location.hash.slice(1)));
    const pane = target && target.closest('.tab-pane');
    if (pane) bootstrap.Tab.getOrCreateInstance(document.querySelector(`[data-bs-target="#${pane.id}"]`)).show();
});
</script>
