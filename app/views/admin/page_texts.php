<?php
$pages = $data['pages'];
$page = $data['page'];
$keys = $pages[$page]['keys'] ?? [];
$overrides = $data['overrides'];

// Group keys by section (second part of the key: home.cta.title -> "cta")
$groups = [];
foreach ($keys as $key) {
    $parts = explode('.', $key);
    $groups[count($parts) > 2 ? $parts[1] : 'umum'][] = $key;
}
$human = fn($s) => ucfirst(trim(str_replace(['_', '.'], [' ', ' · '], $s)));
?>
<style>
    .pt-toolbar { position: sticky; top: 76px; z-index: 20; }
    .pt-lang .btn { min-width: 64px; }
    .pt-section { border-radius: 14px !important; overflow: hidden; }
    .pt-section .accordion-button { font-weight: 600; font-size: .95rem; padding: 1rem 1.25rem; background: #fff; box-shadow: none; }
    .pt-section .accordion-button:not(.collapsed) { color: var(--bs-body-color); border-bottom: 1px solid #eef1f5; }
    .pt-row { padding: .75rem 1.25rem; }
    .pt-row + .pt-row { border-top: 1px solid #f3f5f8; }
    .pt-label { font-size: .75rem; color: #8a94a6; margin-bottom: .25rem; display: flex; align-items: center; gap: .4rem; }
    .pt-input { border: 1px solid transparent !important; background: transparent !important; padding: .35rem .5rem !important; margin-left: -.5rem; resize: none; overflow: hidden; font-size: .92rem; line-height: 1.5; box-shadow: none !important; }
    .pt-input:hover { background: #f7f9fc !important; }
    .pt-input:focus { border-color: #dfe5ee !important; background: #fff !important; }
    .pt-dot { width: 7px; height: 7px; border-radius: 50%; background: #f59f00; display: inline-block; }
    .pt-reset { font-size: .75rem; }
</style>

<div class="admin-header-section mb-4">
    <span class="admin-header-badge d-inline-block">DASHBOARD / KONTEN / TEKS HALAMAN</span>
    <h1 class="fw-bold mb-0">Teks Halaman</h1>
    <p class="text-muted small mb-0">Klik teks untuk mengubahnya. Perubahan disimpan dengan tombol di bawah.</p>
</div>

<form action="<?= BASE_URL; ?>admin/page_texts_update" method="POST" id="pageTextsForm" style="max-width: 980px;">
    <input type="hidden" name="page" value="<?= htmlspecialchars($page) ?>">

    <div class="pt-toolbar card border-0 shadow-sm rounded-4 mb-3">
        <div class="card-body p-2 d-flex flex-wrap gap-2 align-items-center">
            <select id="pagePicker" class="form-select form-select-sm border-0 bg-light fw-semibold" style="max-width: 280px;">
                <?php foreach ($pages as $key => $p) : ?>
                    <option value="<?= $key ?>" <?= $key === $page ? 'selected' : '' ?>><?= htmlspecialchars($p['label']) ?></option>
                <?php endforeach; ?>
            </select>
            <div class="btn-group btn-group-sm pt-lang" role="group" aria-label="Bahasa">
                <button type="button" class="btn btn-dark" data-lang="id">ID</button>
                <button type="button" class="btn btn-light" data-lang="en">EN</button>
            </div>
            <input type="search" id="textSearch" class="form-control form-control-sm border-0 bg-light ms-sm-auto" placeholder="Cari teks..." style="max-width: 220px;">
        </div>
    </div>

    <div class="accordion d-flex flex-column gap-2" id="ptAccordion">
        <?php $first = true; foreach ($groups as $section => $sectionKeys) : $gid = 'pt-' . preg_replace('/[^a-z0-9_]/', '', $section); ?>
            <div class="accordion-item border-0 shadow-sm pt-section">
                <h2 class="accordion-header">
                    <button class="accordion-button <?= $first ? '' : 'collapsed' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#<?= $gid ?>">
                        <span class="me-2"><?= htmlspecialchars($human($section)) ?></span>
                        <span class="text-muted fw-normal small"><?= count($sectionKeys) ?> teks</span>
                        <span class="badge rounded-pill bg-warning bg-opacity-25 text-warning-emphasis fw-normal ms-2 pt-changed-count d-none"></span>
                    </button>
                </h2>
                <div id="<?= $gid ?>" class="accordion-collapse collapse <?= $first ? 'show' : '' ?>">
                    <div class="accordion-body p-0">
                        <?php foreach ($sectionKeys as $key) :
                            $ov = $overrides[$key] ?? null;
                            $parts = explode('.', $key);
                            $rest = implode('.', array_slice($parts, count($parts) > 2 ? 2 : 1));
                        ?>
                            <div class="pt-row" data-key="<?= htmlspecialchars($key) ?>" data-has-override="<?= $ov ? '1' : '0' ?>">
                                <div class="pt-label" title="<?= htmlspecialchars($key) ?>">
                                    <span><?= htmlspecialchars($human($rest)) ?></span>
                                    <span class="pt-dot d-none" title="Sudah diubah"></span>
                                    <button type="button" class="btn btn-link p-0 text-muted text-decoration-none ms-auto pt-reset d-none">Kembalikan</button>
                                </div>
                                <textarea class="form-control pt-input" rows="1" data-lang="id" name="texts[<?= htmlspecialchars($key) ?>][id]"><?= htmlspecialchars($ov['id'] ?? '') ?></textarea>
                                <textarea class="form-control pt-input d-none" rows="1" data-lang="en" name="texts[<?= htmlspecialchars($key) ?>][en]"><?= htmlspecialchars($ov['en'] ?? '') ?></textarea>
                                <div class="pt-html-hint small text-muted d-none" style="font-size: .72rem;"><i class="fas fa-code me-1"></i>Bagian di dalam &lt; &gt; adalah format (baris baru / warna). Ubah teksnya saja, jangan hapus kodenya.</div>
                                <input type="hidden" class="reset-flag" name="texts[<?= htmlspecialchars($key) ?>][reset]" value="" disabled>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php $first = false; endforeach; ?>
    </div>

    <div class="position-sticky bottom-0 py-3" style="z-index: 5;">
        <div class="card border-0 shadow rounded-4">
            <div class="card-body py-2 px-3 d-flex align-items-center justify-content-between gap-3">
                <small class="text-muted" id="changedInfo"></small>
                <button type="submit" class="btn btn-primary btn-action px-4"><i class="fas fa-save"></i> Simpan</button>
            </div>
        </div>
    </div>
</form>

<!-- Default texts come from the same file the website uses -->
<script src="<?= ASSETS_URL ?>js/translations.js?v=<?= time() ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const get = (lang, key) => key.split('.').reduce((o, k) => (o && o[k] !== undefined ? o[k] : undefined), (typeof resources !== 'undefined' ? resources[lang]?.translation : undefined));
    const rows = [...document.querySelectorAll('.pt-row')];
    let lang = 'id';

    const autosize = (ta) => { if (ta.offsetParent === null) return; ta.style.height = 'auto'; ta.style.height = ta.scrollHeight + 2 + 'px'; };
    const isDefault = (row) => [...row.querySelectorAll('.pt-input')].every((ta) => ta.value.trim() === ta.dataset.default.trim());

    rows.forEach((row) => {
        row.querySelectorAll('.pt-input').forEach((ta) => {
            ta.dataset.default = get(ta.dataset.lang, row.dataset.key) ?? '';
            if (ta.value === '') ta.value = ta.dataset.default;   // show the text that is on the page now
            ta.addEventListener('input', () => { autosize(ta); markRow(row); });
        });
        if ([...row.querySelectorAll('.pt-input')].some((ta) => /<[a-z][^>]*>/i.test(ta.value))) row.querySelector('.pt-html-hint').classList.remove('d-none');
        markRow(row, true);
    });

    function markRow(row, silent) {
        const changed = !isDefault(row);
        row.querySelector('.pt-dot').classList.toggle('d-none', !changed);
        row.querySelector('.pt-reset').classList.toggle('d-none', !changed);
        if (!silent) updateCounts();
    }
    function updateCounts() {
        let total = 0;
        document.querySelectorAll('.pt-section').forEach((sec) => {
            const n = [...sec.querySelectorAll('.pt-row')].filter((r) => !isDefault(r)).length;
            total += n;
            const badge = sec.querySelector('.pt-changed-count');
            badge.textContent = n + ' diubah';
            badge.classList.toggle('d-none', n === 0);
        });
        document.getElementById('changedInfo').textContent = total ? total + ' teks berbeda dari bawaan' : 'Semua teks memakai bawaan';
    }
    updateCounts();

    // Show one language at a time
    document.querySelectorAll('.pt-lang .btn').forEach((btn) => btn.addEventListener('click', () => {
        lang = btn.dataset.lang;
        document.querySelectorAll('.pt-lang .btn').forEach((b) => { b.classList.toggle('btn-dark', b === btn); b.classList.toggle('btn-light', b !== btn); });
        document.querySelectorAll('.pt-input').forEach((ta) => ta.classList.toggle('d-none', ta.dataset.lang !== lang));
        document.querySelectorAll('.pt-input:not(.d-none)').forEach(autosize);
    }));

    // Size textareas when a section opens
    document.querySelectorAll('.accordion-collapse').forEach((c) => c.addEventListener('shown.bs.collapse', () => c.querySelectorAll('.pt-input:not(.d-none)').forEach(autosize)));
    document.querySelectorAll('.accordion-collapse.show .pt-input:not(.d-none)').forEach(autosize);

    document.querySelectorAll('.pt-reset').forEach((btn) => btn.addEventListener('click', () => {
        const row = btn.closest('.pt-row');
        row.querySelectorAll('.pt-input').forEach((ta) => { ta.value = ta.dataset.default; autosize(ta); });
        markRow(row);
    }));

    // Send only what matters: changed texts, and resets of texts that were changed before
    document.getElementById('pageTextsForm').addEventListener('submit', () => {
        rows.forEach((row) => {
            const same = isDefault(row);
            const had = row.dataset.hasOverride === '1';
            row.querySelectorAll('.pt-input').forEach((ta) => { ta.disabled = same; });
            const flag = row.querySelector('.reset-flag');
            flag.disabled = !(same && had);
            flag.value = same && had ? '1' : '';
        });
    });

    document.getElementById('pagePicker').addEventListener('change', (e) => {
        location.href = '<?= BASE_URL ?>admin/page_texts?page=' + encodeURIComponent(e.target.value);
    });

    // Search opens the sections that contain matches
    document.getElementById('textSearch').addEventListener('input', (e) => {
        const q = e.target.value.toLowerCase().trim();
        document.querySelectorAll('.pt-section').forEach((sec) => {
            let hits = 0;
            sec.querySelectorAll('.pt-row').forEach((row) => {
                const text = [...row.querySelectorAll('.pt-input')].map((t) => t.value).join(' ') + ' ' + row.querySelector('.pt-label').textContent;
                const show = q === '' || text.toLowerCase().includes(q);
                row.classList.toggle('d-none', !show);
                if (show) hits++;
            });
            sec.classList.toggle('d-none', hits === 0);
            const body = sec.querySelector('.accordion-collapse');
            if (q !== '' && hits) { bootstrap.Collapse.getOrCreateInstance(body, { toggle: false }).show(); }
        });
    });
});
</script>
