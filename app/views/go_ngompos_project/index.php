<?php
$heroGnp = $data['hero'] ?? null;
$gnpHeroImages = json_decode($heroGnp->image ?? '', true);
$gnpHeroImages = is_array($gnpHeroImages) ? $gnpHeroImages : [$heroGnp->image ?? ''];
$gnpHeroImages = array_values(array_filter(array_map(function ($image) {
    return $image && strpos($image, 'http') !== 0 ? ASSETS_URL . 'img/' . $image : $image;
}, $gnpHeroImages)));
if (empty($gnpHeroImages)) $gnpHeroImages[] = 'https://images.unsplash.com/photo-1589923188900-85dae523342b?auto=format&fit=crop&q=80&w=900';
$heroTransition = in_array(($data['hero_transition'] ?? 'slide'), ['slide', 'fade']) ? $data['hero_transition'] : 'slide';
$gnpLogo = $data['hero_logo'] ?? 'logo-go-ngompos.svg';
$gnpLogoUrl = $gnpLogo && filter_var($gnpLogo, FILTER_VALIDATE_URL) ? $gnpLogo : ASSETS_URL . 'img/' . $gnpLogo;

$impactItems = $data['impacts'] ?? [];
?>
<section class="hero-ggc">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-6 d-none d-lg-block">
        <div class="hero-visual-frame">
          <div class="hero-visual-bg bg-success rounded-circle opacity-50"></div>
          <div class="hero-image-slider hero-image-slider-<?= $heroTransition ?> rounded-5 shadow-lg position-relative z-1 overflow-hidden">
            <?php foreach (array_slice($gnpHeroImages, 0, 5) as $index => $slide): ?>
              <img loading="lazy" decoding="async" src="<?= htmlspecialchars($slide, ENT_QUOTES) ?>" class="hero-image-slide <?= $index === 0 ? 'active' : '' ?>" alt="Composting organic waste">
            <?php endforeach; ?>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="ps-lg-5">
          <div class="hero-logo-wrapper mb-4 text-start">
            <img loading="lazy" decoding="async" src="<?= htmlspecialchars($gnpLogoUrl, ENT_QUOTES) ?>" alt="Go Ngompos Project" class="img-fluid" style="max-height: 180px;">
          </div>
          <p class="lead text-secondary text-start mb-4 fs-4" style="max-width: 520px;"
             data-lang-id="<?= htmlspecialchars($heroGnp->subtitle_id ?? 'Gerakan pengolahan sampah organik menjadi kompos dari rumah, sekolah, kantor, dan komunitas.') ?>"
             data-lang-en="<?= htmlspecialchars($heroGnp->subtitle_en ?? 'A movement to turn organic waste into compost from homes, schools, offices, and communities.') ?>">
            <?= $heroGnp->subtitle_id ?? 'Gerakan pengolahan sampah organik menjadi kompos dari rumah, sekolah, kantor, dan komunitas.' ?>
          </p>
          <div class="d-flex justify-content-start gap-3 flex-wrap">
            <a href="#about" class="btn btn-success btn-sm rounded-pill px-4 py-3 fw-bold shadow-sm" data-i18n="ggc.hero.cta_learn">Pelajari Lebih Lanjut</a>
            <a href="#programs" class="btn btn-outline-success btn-sm rounded-pill px-4 py-3 fw-bold" data-i18n="gnp.hero.cta_program">Program Kami</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<style>
  :root {
    --gnp-brown: #8b5e34;
    --gnp-brown-dark: #6f4322;
  }
  .hero-visual-frame { position: relative; display: grid; place-items: center; max-width: 540px; margin-inline: auto; isolation: isolate; }
  .hero-visual-bg { position: absolute; width: min(96%, 500px); aspect-ratio: 1 / 1; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 0; }
  .hero-image-slider { width: 100%; aspect-ratio: 4 / 3; background: #f8f9fa; }
  .hero-image-slide { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; }
  .hero-image-slider-fade .hero-image-slide { opacity: 0; transform: scale(1.03); transition: opacity 1s ease, transform 6s ease; }
  .hero-image-slider-fade .hero-image-slide.active { opacity: 1; transform: scale(1); }
  .hero-image-slider-slide .hero-image-slide { opacity: 1; transform: translateX(100%); transition: transform 0.85s ease; }
  .hero-image-slider-slide .hero-image-slide.active { transform: translateX(0); z-index: 2; }
  .hero-image-slider-slide .hero-image-slide.previous { transform: translateX(-100%); z-index: 1; }
  .hero-ggc .btn-outline-success {
    color: var(--gnp-brown);
    border-color: var(--gnp-brown);
  }
  .hero-ggc .btn-outline-success:hover {
    color: #fff;
    background-color: var(--gnp-brown);
    border-color: var(--gnp-brown);
  }
  .section-title-wrapper h4.text-success,
  #about .text-success,
  .metric-card .metric-value {
    color: var(--gnp-brown) !important;
  }
  #programs .bg-primary {
    background-color: var(--gnp-brown) !important;
  }
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.hero-image-slider').forEach(function(slider) {
    const slides = slider.querySelectorAll('.hero-image-slide');
    if (slides.length <= 1) return;
    let active = 0;
    setInterval(function() {
      const previous = active;
      slides[previous].classList.remove('active', 'previous');
      active = (active + 1) % slides.length;
      slides[previous].classList.add('previous');
      slides[active].classList.add('active');
      setTimeout(function() { slides[previous].classList.remove('previous'); }, 900);
    }, 5000);
  });
});
</script>

<section class="section bg-white border-top">
  <div class="container">
    <div class="section-title-wrapper text-center">
      <div class="title-bg" data-i18n="gnp.impact.title_bg">Impact</div>
      <h4 class="fw-bold fs-2 mb-2 text-success" data-i18n="gnp.impact.title">DAMPAK YANG INGIN DIBANGUN</h4>
      <p class="text-muted" data-i18n="gnp.impact.subtitle">Mendorong kebiasaan mengolah sampah organik dari sumbernya.</p>
    </div>

    <div class="row g-4 justify-content-center">
      <?php if (!empty($impactItems)) : ?>
      <?php foreach ($impactItems as $impact) : ?>
        <div class="col-lg-3 col-md-6">
          <div class="metric-card">
            <div class="metric-value"><?= $impact->value ?></div>
            <div class="metric-unit" data-lang-id="<?= htmlspecialchars($impact->unit ?? '', ENT_QUOTES) ?>" data-lang-en="<?= htmlspecialchars($impact->unit_en ?? $impact->unit ?? '', ENT_QUOTES) ?>"><?= htmlspecialchars($impact->unit ?? '') ?></div>
            <div class="metric-label" data-lang-id="<?= htmlspecialchars($impact->label_id ?? '', ENT_QUOTES) ?>" data-lang-en="<?= htmlspecialchars($impact->label_en ?? $impact->label_id ?? '', ENT_QUOTES) ?>"><?= htmlspecialchars($impact->label_id ?? '') ?></div>
            <div class="metric-note" data-lang-id="<?= htmlspecialchars($impact->note_id ?? '', ENT_QUOTES) ?>" data-lang-en="<?= htmlspecialchars($impact->note_en ?? $impact->note_id ?? '', ENT_QUOTES) ?>"><?= htmlspecialchars($impact->note_id ?? '') ?></div>
          </div>
        </div>
      <?php endforeach; ?>
      <?php else : ?>
        <div class="col-12 text-center py-4">
          <p class="text-muted mb-0">Data dampak belum tersedia.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php
  $aboutSection = $data['about_section'] ?? null;
  $aboutBadgeId = $aboutSection->badge_id ?? 'TENTANG PROGRAM';
  $aboutBadgeEn = $aboutSection->badge_en ?? 'ABOUT THE PROGRAM';
  $aboutTitleId = $aboutSection->title_id ?? 'MENGENAL <span class="text-success">GO NGOMPOS PROJECT</span>';
  $aboutTitleEn = $aboutSection->title_en ?? 'GET TO KNOW <span class="text-success">GO NGOMPOS PROJECT</span>';
  $aboutContentId = $aboutSection->content_id ?? 'Go Ngompos Project adalah inisiatif GoSirk untuk mengajak masyarakat mengurangi sampah organik yang terbuang ke TPA melalui praktik pengomposan yang mudah dan dekat dengan keseharian.';
  $aboutContentEn = $aboutSection->content_en ?? 'Go Ngompos Project is a GoSirk initiative inviting people to reduce organic waste sent to landfills through simple composting practices close to daily life.';
  $aboutContent2Id = $aboutSection->content_2_id ?? 'Program ini menggabungkan edukasi, pendampingan, dan kampanye perubahan perilaku agar rumah tangga, sekolah, kantor, dan komunitas mampu mengelola sampah organiknya sendiri.';
  $aboutContent2En = $aboutSection->content_2_en ?? 'This program combines education, assistance, and behavior change campaigns so households, schools, offices, and communities can manage their own organic waste.';
  $aboutImage = $aboutSection->image ?? 'https://images.unsplash.com/photo-1591857177580-dc82b9ac4e1e?auto=format&fit=crop&q=80&w=900';
  $aboutImageUrl = $aboutImage && filter_var($aboutImage, FILTER_VALIDATE_URL) ? $aboutImage : ASSETS_URL . 'img/' . $aboutImage;
?>
<?php if (!$aboutSection || ((int) ($aboutSection->is_active ?? 1)) === 1): ?>
<section class="section" id="about">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-6">
        <div class="bg-light p-2 rounded-5 shadow-sm overflow-hidden">
          <img loading="lazy" decoding="async" src="<?= htmlspecialchars($aboutImageUrl, ENT_QUOTES) ?>" class="img-fluid rounded-4 shadow" alt="Compost and plants">
        </div>
      </div>
      <div class="col-lg-6">
        <div class="ps-lg-4">
          <div class="text-success fw-bold mb-2" data-lang-id="<?= htmlspecialchars($aboutBadgeId, ENT_QUOTES) ?>" data-lang-en="<?= htmlspecialchars($aboutBadgeEn, ENT_QUOTES) ?>"><?= $aboutBadgeId ?></div>
          <h2 class="fw-bold mb-4 display-6" data-lang-id="<?= htmlspecialchars($aboutTitleId, ENT_QUOTES) ?>" data-lang-en="<?= htmlspecialchars($aboutTitleEn, ENT_QUOTES) ?>">
            <?= $aboutTitleId ?>
          </h2>
          <p class="lead text-secondary mb-4" data-lang-id="<?= htmlspecialchars($aboutContentId, ENT_QUOTES) ?>" data-lang-en="<?= htmlspecialchars($aboutContentEn, ENT_QUOTES) ?>">
            <?= $aboutContentId ?>
          </p>
          <p class="text-muted mb-4" data-lang-id="<?= htmlspecialchars($aboutContent2Id, ENT_QUOTES) ?>" data-lang-en="<?= htmlspecialchars($aboutContent2En, ENT_QUOTES) ?>">
            <?= $aboutContent2Id ?>
          </p>
        </div>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<?php $this->views('partials/programs', [
    'programs' => $data['programs'] ?? [], 'section' => $data['programs_section'] ?? null, 'model' => 'GnpProgram_model',
    'i18n' => 'gnp.programs', 'default_title' => 'PROGRAM UTAMA', 'default_subtitle' => 'Langkah praktis untuk membangun kebiasaan ngompos yang konsisten.',
]); ?>

<section class="cta-ggc text-center" style="background-image: linear-gradient(rgba(10, 50, 20, 0.85), rgba(10, 50, 20, 0.85)), url('<?= PageImages::attr('gnp.cta_bg') ?>');">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <h2 class="fw-bold mb-4 display-5" data-i18n="gnp.cta.title">Ayo Mulai Ngompos Bersama</h2>
        <p class="mb-5 lead opacity-75" data-i18n="gnp.cta.desc">
          Ubah sisa organik menjadi dampak baik bagi lingkungan, tanaman, dan komunitas.
        </p>
        <div class="d-flex flex-column align-items-center gap-3">
          <?php if (!empty($data['concept_notes'])) : // concept note available: this button replaces "Hubungi Kami" ?>
          <button type="button" class="btn btn-warning btn-cta-yellow rounded-pill px-4 py-2 d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#conceptNoteModal">
            <span class="material-symbols-outlined">description</span>
            <span data-i18n="gnp.cta.button_partnership" data-i18n-html="true">We are calling for partnership. <i class="text-decoration-underline">Get the concept note. Contact us.</i></span>
          </button>
          <?php else : ?>
          <a href="<?= BASE_URL ?>contact" class="btn btn-ggc-light btn-lg shadow-lg" data-i18n="gnp.cta.btn">
            <i class="bi bi-chat-dots-fill me-2"></i> Hubungi Kami
          </a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<?php if (!empty($data['concept_notes'])) : $conceptNotes = $data['concept_notes']; ?>
<!-- Concept Note request modal (documents of type "Concept Note" from Admin > Documents) -->
<div class="modal fade" id="conceptNoteModal" tabindex="-1" aria-labelledby="conceptNoteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4">
      <div class="modal-header">
        <h5 class="modal-title fw-bold" id="conceptNoteModalLabel" data-i18n="gnp.concept_modal.title">Dapatkan Concept Note</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-start">
        <p class="mb-4 text-muted small" data-i18n="gnp.concept_modal.desc">Silakan isi formulir di bawah ini. Concept note akan dikirim ke email Anda.</p>
        <form id="conceptNoteForm"><?= FormGuard::honeypot() ?>
          <?php if (count($conceptNotes) === 1) : ?>
            <input type="hidden" name="doc_id" value="<?= (int) $conceptNotes[0]->id ?>">
            <div class="alert alert-warning border-0 small mb-4">
              <i class="bi bi-file-earmark-text me-1"></i>
              <span data-i18n="gnp.concept_modal.doc">Dokumen</span>: <strong><?= htmlspecialchars($conceptNotes[0]->title_id) ?></strong>
            </div>
          <?php else : ?>
            <div class="mb-3">
              <label for="cnDoc" class="form-label" data-i18n="gnp.concept_modal.doc">Dokumen</label>
              <select class="form-select" id="cnDoc" name="doc_id" data-label="Dokumen" required>
                <?php foreach ($conceptNotes as $doc) : ?>
                  <option value="<?= (int) $doc->id ?>"><?= htmlspecialchars($doc->title_id) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          <?php endif; ?>
          <div class="mb-3">
            <label for="cnName" class="form-label" data-i18n="home.modal.name">Nama Lengkap</label>
            <input type="text" class="form-control" id="cnName" placeholder="Masukkan nama Anda" data-i18n="home.modal.name_placeholder" <?= FormRules::attrs('doc_request', 'name') ?>>
          </div>
          <div class="mb-3">
            <label for="cnEmail" class="form-label" data-i18n="home.modal.email">Alamat Email</label>
            <input type="email" class="form-control" id="cnEmail" placeholder="name@example.com" <?= FormRules::attrs('doc_request', 'email') ?>>
          </div>
          <div class="mb-3">
            <label for="cnOrganization" class="form-label" data-i18n="home.modal.org">Instansi / Perusahaan</label>
            <input type="text" class="form-control" id="cnOrganization" placeholder="Nama instansi Anda" data-i18n="home.modal.org_placeholder" <?= FormRules::attrs('doc_request', 'organization') ?>>
          </div>
          <div class="mb-3">
            <label for="cnJabatan" class="form-label" data-i18n="home.modal.position">Jabatan</label>
            <input type="text" class="form-control" id="cnJabatan" placeholder="Jabatan Anda" data-i18n="home.modal.position_placeholder" <?= FormRules::attrs('doc_request', 'jabatan') ?>>
          </div>
          <p class="form-consent small text-muted mb-3"><span data-i18n="common.consent">Dengan mengirim formulir ini, Anda menyetujui data Anda digunakan GoSirk untuk menanggapi permintaan Anda dan memberi informasi program terkait, sesuai</span> <a href="<?= BASE_URL ?>privacy" target="_blank" rel="noopener" data-i18n="common.privacy_link">Kebijakan Privasi</a>.</p>
          <div class="d-grid gap-2 mt-4">
            <button type="submit" class="btn btn-warning rounded-pill text-white fw-bold" data-i18n="home.modal.submit">Kirim</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('conceptNoteForm');
    if (!form) return;
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        const btn = form.querySelector('button[type="submit"]');
        const original = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> ' + ((localStorage.getItem('gosirk_language') || 'en') === 'id' ? 'Mengirim...' : 'Sending...');

        const fd = new FormData();
        fd.append('doc_id', form.querySelector('[name="doc_id"]').value);
        fd.append('name', document.getElementById('cnName').value);
        fd.append('email', document.getElementById('cnEmail').value);
        fd.append('organization', document.getElementById('cnOrganization').value);
        fd.append('jabatan', document.getElementById('cnJabatan').value);

        fetch('<?= BASE_URL ?>collaboration/request', { method: 'POST', body: fd })
            .then((r) => r.json())
            .then((data) => {
                if (data.status === 'success') {
                    GosirkLead.saveFrom('cn');
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: data.message, confirmButtonColor: '#29b471' }).then(() => {
                        bootstrap.Modal.getInstance(document.getElementById('conceptNoteModal')).hide();
                        form.reset();
                        GosirkLead.fill('cn');
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'Oops!', text: data.message });
                }
            })
            .catch(() => Swal.fire({ icon: 'error', title: 'Error!', text: 'Terjadi kesalahan sistem.' }))
            .finally(() => { btn.disabled = false; btn.innerHTML = original; });
    });
});
</script>
<?php endif; ?>
