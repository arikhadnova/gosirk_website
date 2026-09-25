<div class="admin-header-section mb-4">
    <span class="admin-header-badge d-inline-block">DASHBOARD / LAYOUT SETTINGS</span>
    <h1 class="fw-bold mb-0">Konfigurasi Website</h1>
    <p class="text-muted small mb-0">Atur tampilan header, footer, dan informasi identitas website GoSirk secara terpusat.</p>
</div>

<div class="row mb-4">
    <div class="col-12">
        <?php Flasher::flash(); ?>
    </div>
</div>

<div class="row g-4">
    <!-- Header Configuration -->
    <div class="col-lg-6">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-header bg-white border-bottom p-4">
                <div class="d-flex align-items-center">
                    <div class="stat-icon-box stat-icon-blue me-3">
                        <i class="fas fa-window-maximize"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">Header Configuration</h5>
                        <small class="text-muted">Logo, judul situs, dan navigasi atas.</small>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <form action="<?= BASE_URL; ?>admin/update_header" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="site_title" class="form-label small fw-bold text-dark">Site Title</label>
                        <input type="text" id="site_title" name="site_title" class="form-control" value="<?= $settings['site_title'] ?? 'GoSirk' ?>" <?= FormRules::attrs('settings_header', 'site_title', 'update') ?>>
                    </div>
                    <div class="mb-3">
                        <label for="site_description" class="form-label small fw-bold text-dark">Site Description (SEO)</label>
                        <textarea id="site_description" name="site_description" class="form-control" rows="3" placeholder="Deskripsi yang muncul di hasil pencarian Google" <?= FormRules::attrs('settings_header', 'site_description', 'update') ?>><?= $settings['site_description'] ?? '' ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="logo_url" class="form-label small fw-bold text-dark">Current Logo</label>
                        <div class="p-3 border rounded-3 text-center bg-light">
                            <?php 
                                $logo = $settings['site_logo'] ?? 'Logo-GoSirk-01.png';
                                $logo_url = (strpos($logo, 'http') === 0) ? $logo : ASSETS_URL . 'img/' . $logo;
                            ?>
                            <img src="<?= $logo_url ?>" alt="Logo" class="mb-2 d-block mx-auto" style="max-height: 50px;">
                            <code class="extra-small text-muted"><?= $logo ?></code>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="logo_file" class="form-label small fw-bold text-dark">Update Website Logo</label>
                        <input type="file" id="logo_file" name="logo_file" class="form-control form-control-sm" accept="image/*">
                        <small class="text-muted mt-2 d-block extra-small">Format: PNG, SVG, JPG. Max size 2MB.</small>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary py-2 fw-bold">
                            <i class="fas fa-save me-2"></i> Simpan Perubahan Header
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer Configuration -->
    <div class="col-lg-6">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-header bg-white border-bottom p-4">
                <div class="d-flex align-items-center">
                    <div class="stat-icon-box stat-icon-orange me-3">
                        <i class="fas fa-columns"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">Footer Configuration</h5>
                        <small class="text-muted">Informasi kontak, alamat, dan social media.</small>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <form action="<?= BASE_URL; ?>admin/update_footer" method="POST">
                    <div class="mb-3">
                        <label for="footer_text" class="form-label small fw-bold text-dark">Copyright Text</label>
                        <input type="text" id="footer_text" name="footer_text" class="form-control" value="<?= $settings['footer_copyright'] ?? '© 2025 PT Gocircular Solution Indonesia' ?>" <?= FormRules::attrs('settings_footer', 'footer_text', 'update') ?>>
                    </div>
                    <div class="mb-3">
                        <label for="contact_email" class="form-label small fw-bold text-dark">Email Kontak</label>
                        <input type="email" id="contact_email" name="contact_email" class="form-control" value="<?= $settings['contact_email'] ?? 'medcom.gosirk@gmail.com' ?>" <?= FormRules::attrs('settings_footer', 'contact_email', 'update') ?>>
                    </div>
                    <div class="mb-3">
                        <label for="contact_whatsapp" class="form-label small fw-bold text-dark">WhatsApp Kontak</label>
                        <input type="text" id="contact_whatsapp" name="contact_whatsapp" class="form-control" value="<?= $settings['contact_whatsapp'] ?? '628xxxxxxxxx' ?>" placeholder="628xxxxxxxxx" <?= FormRules::attrs('settings_footer', 'contact_whatsapp', 'update') ?>>
                        <div class="form-text small text-muted">Nomor WhatsApp untuk konsultasi layanan gratis.</div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="address_hq" class="form-label small fw-bold text-dark">Alamat Pusat (HQ)</label>
                            <textarea id="address_hq" name="address_hq" class="form-control small" rows="4" <?= FormRules::attrs('settings_footer', 'address_hq', 'update') ?>><?= $settings['address_hq'] ?? 'Jln Kepodang, Dusun Kepuh Wetan, RT002 RW005, Desa Kalirejo, Kecamatan Kabat, Kabupaten Banyuwangi, Jawa Timur 68461' ?></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="address_branch" class="form-label small fw-bold text-dark">Alamat Cabang (Branch)</label>
                            <textarea id="address_branch" name="address_branch" class="form-control small" rows="4" <?= FormRules::attrs('settings_footer', 'address_branch', 'update') ?>><?= $settings['address_branch'] ?? 'Perum Royal Griya Loka Blok S-23, Samsam, Kec. Kerambitan, Tabanan' ?></textarea>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-dark">Social Media Links</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text bg-white border-end-0"><i class="fab fa-facebook text-primary"></i></span>
                            <input type="url" name="social_facebook" class="form-control border-start-0 ps-0 small" value="<?= $settings['social_facebook'] ?? '' ?>" placeholder="Facebook URL" <?= FormRules::attrs('settings_footer', 'social_facebook', 'update') ?>>
                        </div>
                        <div class="input-group mb-2">
                            <span class="input-group-text bg-white border-end-0"><i class="fab fa-instagram text-danger"></i></span>
                            <input type="url" name="social_instagram" class="form-control border-start-0 ps-0 small" value="<?= $settings['social_instagram'] ?? 'https://www.instagram.com/gosirk_indonesia/' ?>" placeholder="Instagram URL" <?= FormRules::attrs('settings_footer', 'social_instagram', 'update') ?>>
                        </div>
                        <div class="input-group mb-2">
                            <span class="input-group-text bg-white border-end-0"><i class="fab fa-linkedin" style="color: #0077b5;"></i></span>
                            <input type="url" name="social_linkedin" class="form-control border-start-0 ps-0 small" value="<?= $settings['social_linkedin'] ?? 'https://www.linkedin.com/company/gocircular-indonesia-gosirk/' ?>" placeholder="LinkedIn URL" <?= FormRules::attrs('settings_footer', 'social_linkedin', 'update') ?>>
                        </div>
                        <div class="input-group mb-2">
                            <span class="input-group-text bg-white border-end-0"><i class="fab fa-youtube text-danger"></i></span>
                            <input type="url" name="social_youtube" class="form-control border-start-0 ps-0 small" value="<?= $settings['social_youtube'] ?? 'https://www.youtube.com/@gosirk_institute' ?>" placeholder="YouTube URL" <?= FormRules::attrs('settings_footer', 'social_youtube', 'update') ?>>
                        </div>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary py-2 fw-bold">
                            <i class="fas fa-save me-2"></i> Simpan Perubahan Footer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
