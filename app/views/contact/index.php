 
 <div class="w-75 mx-auto">
    <header class="contact-header">
        <div class="container-fluid">
            <h1 data-i18n="contact.header.title">Kontak Kami</h1>
            <p data-i18n="contact.header.desc">Punya pertanyaan, ide kolaborasi, atau ingin tahu lebih banyak tentang layanan kami? Tim kami siap bantu.</p>
        </div>
    </header>

    <section class="contact-container">
        <div class="row g-4">
            <div class="col-lg-5">
                <div class="info-panel">
                    <h2 data-i18n="contact.info.title">Mari Terhubung</h2>
                    
<?php
                    // Same settings as the footer (Admin > Layouts > Footer)
                    $set = $data['settings'] ?? [];
                    $hq = trim($set['address_hq'] ?? '') ?: 'Jln Kepodang, Dusun Kepuh Wetan, RT002 RW005, Desa Kalirejo, Kecamatan Kabat, Kabupaten Banyuwangi, Jawa Timur 68461';
                    $branch = trim($set['address_branch'] ?? '') ?: 'Perum Royal Griya Loka Blok S-23, Samsam, Kec. Kerambitan, Tabanan';
                    $email = trim($set['contact_email'] ?? '') ?: 'dontwasteourfuture@gosirk.co.id';
                    $wa = $this->waNumber();
                    $linkedin = trim($set['social_linkedin'] ?? '');
                    $hoursId = trim($set['office_hours'] ?? '');
                    $hoursEn = trim($set['office_hours_en'] ?? '') ?: $hoursId;
                    ?>
                    <div class="info-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <strong data-i18n="contact.info.hq_title">Kantor Pusat, Banyuwangi</strong>
                            <span><?= nl2br(htmlspecialchars($hq)) ?></span>
                        </div>
                    </div>

                    <div class="info-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <strong data-i18n="contact.info.branch_title">Kantor Cabang, Bali</strong>
                            <span><?= nl2br(htmlspecialchars($branch)) ?></span>
                        </div>
                    </div>

                    <div class="info-item">
                        <i class="fas fa-clock"></i>
                        <div>
                            <?php if ($hoursId !== '') : ?>
                                <strong data-i18n="contact.info.hours_label">Jam Kerja</strong>
                                <span data-lang-id="<?= htmlspecialchars($hoursId, ENT_QUOTES) ?>" data-lang-en="<?= htmlspecialchars($hoursEn, ENT_QUOTES) ?>"><?= htmlspecialchars($hoursId) ?></span>
                            <?php else : ?>
                                <strong data-i18n="contact.info.hours_title">Jam Kerja: Senin s/d Jumat</strong>
                                <span data-i18n="contact.info.hours_desc">08:00 - 16:00 WIB</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="info-item">
                        <i class="fas fa-envelope"></i>
                        <div><a href="mailto:<?= htmlspecialchars($email) ?>" class="text-reset text-decoration-none"><?= htmlspecialchars($email) ?></a></div>
                    </div>

                    <?php if ($wa !== '') : ?>
                    <div class="info-item">
                        <i class="fab fa-whatsapp"></i>
                        <div><a href="<?= htmlspecialchars($this->waLink()) ?>" target="_blank" rel="noopener" class="text-reset text-decoration-none">+<?= htmlspecialchars($wa) ?></a></div>
                    </div>
                    <?php endif; ?>

                    <div class="info-item">
                        <i class="fab fa-linkedin"></i>
                        <div>
                            <?php if ($linkedin !== '') : ?>
                                <a href="<?= htmlspecialchars($linkedin) ?>" target="_blank" rel="noopener" class="text-reset text-decoration-none">Gocircular Solutions Indonesia</a>
                            <?php else : ?>
                                Gocircular Solutions Indonesia
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="form-panel">
                    <p data-i18n="contact.form.title">Silahkan tinggalkan pesan anda dibawah ini</p>
                    <form id="contactForm">
                            <div class="mb-4">
                                <label class="form-label" data-i18n="contact.form.name">Nama</label>
                                <input type="text" name="name" class="form-control" placeholder="Nama Anda" data-i18n="contact.form.name_placeholder" <?= FormRules::attrs('contact', 'name') ?>>
                            </div>
                            <div class="mb-4">
                                <label class="form-label" data-i18n="contact.form.email">Email</label>
                                <input type="email" name="email" class="form-control" placeholder="Email Anda" data-i18n="contact.form.email_placeholder" <?= FormRules::attrs('contact', 'email') ?>>
                            </div>
                            <div class="mb-4">
                                <label class="form-label" data-i18n="contact.form.message">Pesan</label>
                                <textarea name="message" class="form-control" rows="5" placeholder="Pesan Anda" data-i18n="contact.form.message_placeholder" <?= FormRules::attrs('contact', 'message') ?>></textarea>
                            </div>
                        <p class="form-consent small text-muted mb-3"><span data-i18n="common.consent">Dengan mengirim formulir ini, Anda menyetujui data Anda digunakan GoSirk untuk menanggapi permintaan Anda dan memberi informasi program terkait, sesuai</span> <a href="<?= BASE_URL ?>privacy" target="_blank" rel="noopener" data-i18n="common.privacy_link">Kebijakan Privasi</a>.</p>
                        <button type="submit" id="btnSubmit" class="btn btn-submit" data-i18n="contact.form.submit">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.getElementById('contactForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const btn = document.getElementById('btnSubmit');
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Mengirim...';
    
    const formData = new FormData(this);
    
    fetch('<?= BASE_URL ?>contact/store', {
        method: 'POST',
        body: formData
    })
    .then(async response => {
        const text = await response.text();
        try {
            return JSON.parse(text);
        } catch (e) {
            console.error('Raw response:', text);
            throw new Error('Server returned invalid JSON: ' + text.substring(0, 100));
        }
    })
    .then(data => {
        if (data.status === 'success') {
            Swal.fire({
                title: 'Berhasil!',
                text: data.message,
                icon: 'success',
                confirmButtonColor: '#007BFF'
            });
            this.reset();
        } else {
            Swal.fire({
                title: 'Gagal!',
                text: data.message,
                icon: 'error',
                confirmButtonColor: '#007BFF'
            });
        }
    })
    .catch(error => {
        console.error('Error detail:', error);
        Swal.fire({
            title: 'Error!',
            text: error.message || 'Terjadi kesalahan pada sistem.',
            icon: 'error'
        });
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = originalText;
    });
});
</script>

<!-- BANNER -->
<div class="banner-section text-center">
    <img loading="lazy" decoding="async" src="<?= PageImages::attr('contact.banner') ?>" alt="Banner GoSirk" class="img-fluid w-75 mx-auto d-block mb-5">
</div>