<?php
$section = $data['section'] ?? null;
$isActive = !$section || (int) $section->is_active === 1;
?>
<div class="admin-header-section mb-4">
    <span class="admin-header-badge d-inline-block">DASHBOARD / IMPLEMENTASI PARTNER / SOROTAN</span>
    <h1 class="fw-bold mb-0">Sorotan Implementasi Partner</h1>
    <p class="text-muted small mb-0">Foto dan video YouTube yang tampil di section "Sorotan" halaman Implementasi Partner, setelah "Mitra dan Jejaring Kami".</p>
</div>

<form action="<?= BASE_URL; ?>admin/partner_highlights_update" method="POST" enctype="multipart/form-data">
    <div class="card border-0 shadow-sm rounded-4" style="max-width: 960px;">
        <div class="card-body p-4">
            <?php
            $highlightItems = $data['items'] ?: [['type' => 'image']];
            $highlightFieldLabel = 'DAFTAR SOROTAN';
            require __DIR__ . '/portfolio_highlights_field.php';
            ?>

            <hr class="my-4">

            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_active" id="highlightsActive" <?= $isActive ? 'checked' : ''; ?>>
                    <label class="form-check-label small" for="highlightsActive">Tampilkan section Sorotan di halaman Implementasi Partner</label>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <a href="<?= BASE_URL; ?>implementasi_partner" target="_blank" class="small text-decoration-none"><i class="fas fa-external-link-alt me-1"></i> Lihat halaman</a>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">
                        <i class="fas fa-save me-2"></i> Simpan Sorotan
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
