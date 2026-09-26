<?php
$pending = $data['pending'];
$mb = fn($b) => number_format($b / 1048576, 1, ',', '.') . ' MB';
$webp = count(array_filter($pending, fn($p) => $p['action'] === 'webp'));
?>
<div class="admin-header-section mb-4">
    <span class="admin-header-badge d-inline-block">PENGATURAN / SITUS / OPTIMASI GAMBAR</span>
    <h1 class="fw-bold mb-0">Optimasi Gambar</h1>
    <p class="text-muted small mb-0">Gambar yang diupload sekarang otomatis dikompres. Alat ini mengecilkan gambar lama agar website lebih cepat, terutama di HP.</p>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100"><div class="card-body p-4">
            <div class="text-muted small mb-1">Gambar yang bisa dioptimalkan</div>
            <div class="fs-2 fw-bold" id="optCount"><?= count($pending) ?></div>
        </div></div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100"><div class="card-body p-4">
            <div class="text-muted small mb-1">Ukuran saat ini</div>
            <div class="fs-2 fw-bold"><?= $mb($data['pending_bytes']) ?></div>
        </div></div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100"><div class="card-body p-4">
            <div class="text-muted small mb-1">Sudah dihemat (sesi ini)</div>
            <div class="fs-2 fw-bold text-success" id="optSaved">0 MB</div>
        </div></div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <?php if (!$pending) : ?>
            <div class="text-center py-4">
                <i class="fas fa-check-circle fa-2x text-success mb-3"></i>
                <h5 class="fw-bold mb-1">Semua gambar sudah optimal</h5>
                <p class="text-muted small mb-0">Tidak ada gambar lama yang perlu dikecilkan.</p>
            </div>
        <?php else : ?>
            <h5 class="fw-bold mb-2"><i class="fas fa-bolt me-2"></i>Yang akan dilakukan</h5>
            <ul class="small text-muted mb-4">
                <li>Foto JPG yang terlalu besar diperkecil ke maks. 1920px, dengan nama file yang sama.</li>
                <?php if ($webp) : ?><li><?= $webp ?> gambar PNG berukuran besar dibuatkan versi WebP yang jauh lebih ringan, lalu website memakai versi itu. File PNG aslinya tetap disimpan.</li><?php endif; ?>
                <li>Tampilan gambar tidak berubah. Proses berjalan bertahap; jangan tutup halaman ini sampai selesai.</li>
            </ul>
            <div class="progress mb-3 d-none" style="height: 10px;" id="optProgressWrap">
                <div class="progress-bar bg-success" id="optProgress" style="width: 0%"></div>
            </div>
            <div class="d-flex align-items-center gap-3">
                <button type="button" class="btn btn-primary px-4" id="optStart"><i class="fas fa-play"></i> Mulai optimasi</button>
                <span class="small text-muted" id="optStatus"></span>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('optStart');
    if (!btn) return;
    const total = <?= count($pending) ?>;
    let saved = 0;
    const fmt = (b) => (b / 1048576).toLocaleString('id-ID', { maximumFractionDigits: 1 }) + ' MB';

    btn.addEventListener('click', async function () {
        btn.disabled = true;
        document.getElementById('optProgressWrap').classList.remove('d-none');
        let remaining = total;
        try {
            while (remaining > 0) {
                const res = await fetch('<?= BASE_URL ?>admin/image_optimize_run', { method: 'POST' }).then((r) => r.json());
                if (res.status !== 'success') throw new Error(res.message || 'gagal');
                saved += res.saved;
                remaining = res.remaining;
                const doneCount = total - remaining;
                document.getElementById('optProgress').style.width = Math.round(doneCount / total * 100) + '%';
                document.getElementById('optStatus').textContent = `${doneCount} dari ${total} gambar diproses`;
                document.getElementById('optSaved').textContent = fmt(saved);
                document.getElementById('optCount').textContent = remaining;
                if (res.done === 0) break;
            }
            Swal.fire({ icon: 'success', title: 'Selesai', text: `Gambar lama sudah dioptimalkan. Hemat ${fmt(saved)}.` }).then(() => location.reload());
        } catch (e) {
            btn.disabled = false;
            Swal.fire({ icon: 'error', title: 'Terhenti', text: 'Proses terhenti. Klik "Mulai optimasi" lagi untuk melanjutkan.' });
        }
    });
});
</script>
