<?php
if (!isset($settings)) {
    require_once dirname(dirname(__DIR__)) . '/models/Setting_model.php';
    $settingModel = new Setting_model();
    $settings = $settingModel->getAll();
}
?>
<footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-3 mb-4">
                    <h6 data-i18n="footer.brand">GoSirk</h6>
                    <p data-i18n="footer.desc">We are a private company with a strong social business orientation, dedicated to developing innovative and eco-friendly solutions for waste management.</p>
                </div>
                <div class="col-md-3 mb-4">
                    <h6 data-i18n="footer.menu">Menu</h6>
                    <a href="<?= BASE_URL ?>gi" data-i18n="nav.capacity_building">Peningkatan Kapasitas</a>
                    <a href="<?= BASE_URL ?>implementasi_partner" data-i18n="nav.program_dev">Pengembangan Program & Implementasi Partner</a>
                    <a href="<?= BASE_URL ?>konsultan" data-i18n="nav.consultancy">Konsultansi</a>
                    <a href="<?= BASE_URL ?>partnership" data-i18n="nav.partnership">Kerjasama</a>
                    <a href="<?= BASE_URL ?>about" data-i18n="nav.about">Tentang</a>
                    <a href="<?= BASE_URL ?>gi" data-i18n="nav.gi">GoSirk Institute</a>
                    <a href="<?= BASE_URL ?>ggc" data-i18n="nav.ggc">GoSirk Green Community</a>
                    <a href="<?= BASE_URL ?>go_ngompos_project" data-i18n="nav.gnp">Go Ngompos Project</a>
                    <a href="<?= BASE_URL ?>blog" data-i18n="nav.blog">Blog</a>
                    <a href="<?= BASE_URL ?>publication" data-i18n="nav.pub_gosirk">Publikasi</a>
                    <a href="<?= BASE_URL ?>contact" data-i18n="nav.contact">Kontak</a>

                </div>
                <div class="col-md-3 mb-4">
                    <h6 data-i18n="footer.office">Kantor</h6>
                    <p><i class="fas fa-map-marker-alt me-2"></i> <strong data-i18n="footer.hq">Kantor Pusat, Banyuwangi</strong><br>
                    <?= $settings['address_hq'] ?? 'Jln Kepodang, Dusun Kepuh Wetan, RT002 RW005, Desa Kalirejo, Kecamatan Kabat, Kabupaten Banyuwangi, Jawa Timur 68461' ?></p>
                    <p><i class="fas fa-map-marker-alt me-2"></i> <strong data-i18n="footer.branch">Kantor Cabang, Bali</strong><br>
                    <?= $settings['address_branch'] ?? 'Perum Royal Griya Loka Blok S-23, Samsam, Kec. Kerambitan, Tabanan' ?></p>
                </div>
                <div class="col-md-3 mb-4">
                    <h6 data-i18n="footer.contact">Hubungi kami</h6>
                    <?php
                        $whatsappNumberClean = $this->waNumber();
                        $whatsappLink = $this->waLink();
                    ?>
                    <p class="small fst-italic mb-1" data-i18n="footer.free_consultation">Gratis konsultasi layanan</p>
                    <div class="d-flex align-items-start mb-2 gap-2">
                        <i class="fab fa-whatsapp"></i>
                        <div>
                            <a href="<?= $whatsappLink ?>" target="_blank" class="text-decoration-none">+<?= $whatsappNumberClean ?></a>
                        </div>
                    </div>
                    <p><i class="fas fa-envelope me-2"></i> <?= $settings['contact_email'] ?? 'medcom.gosirk@gmail.com' ?></p>
                    <div class="footer-socials">
                        <?php if (!empty($settings['social_instagram'])) : ?>
                        <a href="<?= $settings['social_instagram'] ?>" target="_blank"><i class="fab fa-instagram"></i></a>
                        <?php endif; ?>
                        <?php if (!empty($settings['social_linkedin'])) : ?>
                        <a href="<?= $settings['social_linkedin'] ?>" target="_blank"><i class="fab fa-linkedin"></i></a>
                        <?php endif; ?>
                        <?php if (!empty($settings['social_youtube'])) : ?>
                        <a href="<?= $settings['social_youtube'] ?>" target="_blank"><i class="fab fa-youtube"></i></a>
                        <?php endif; ?>
                        <?php if (!empty($settings['social_facebook'])) : ?>
                        <a href="<?= $settings['social_facebook'] ?>" target="_blank"><i class="fab fa-facebook"></i></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="copyright">
                &copy; <?= date('Y') ?> <?= $settings['footer_copyright'] ?? 'PT Gocircular Solution Indonesia' ?>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
      // Initialize Swiper for logo slider
      const logoSlider = new Swiper('.logo-slider', {
        loop: true,
        autoplay: {
          delay: 0,
          disableOnInteraction: false,
        },
        speed: 8000,
        slidesPerView: 1,
        spaceBetween: 20,
        breakpoints: {
          576: { slidesPerView: 2 },
          768: { slidesPerView: 3 },
          1024: { slidesPerView: 5 },
        },
      });

      const networkSlider = new Swiper('.network-slider', {
        loop: true,
        autoplay: {
          delay: 0,
          disableOnInteraction: false,
          reverseDirection: true,
        },
        speed: 8000,
        slidesPerView: 1,
        spaceBetween: 20,
        breakpoints: {
          576: { slidesPerView: 2 },
          768: { slidesPerView: 3 },
          1024: { slidesPerView: 5 },
        },
      });

      // Initialize Swiper for blog slider
      const blogSlider = new Swiper('.blog-slider', {
        loop: false,
        slidesPerView: 1,
        spaceBetween: 24,
        navigation: {
          nextEl: '.next-blog',
          prevEl: '.prev-blog',
        },
        breakpoints: {
          576: {
            slidesPerView: 2,
          },
          1024: {
            slidesPerView: 3,
          },
        },
      });

      // Global Counter Animation Logic
      const counters = document.querySelectorAll('.counter');
      
      const animateCounter = (counter) => {
          const target = +counter.getAttribute('data-target');
          const decimals = +counter.getAttribute('data-decimals') || 0;
          const duration = 2000; // 2 seconds
          const startTime = performance.now();

          const updateCount = (currentTime) => {
              const elapsed = currentTime - startTime;
              const progress = Math.min(elapsed / duration, 1);
              
              const easeProgress = progress === 1 ? 1 : 1 - Math.pow(2, -10 * progress);
              const currentValue = easeProgress * target;
              
              // Format with thousand separator if it's a large number and not decimal
              let formattedValue = currentValue.toFixed(decimals);
              if (decimals === 0 && target >= 1000) {
                  const currentLang = (typeof GoSirkLang !== 'undefined') ? GoSirkLang.getCurrent() : 'id';
                  const locale = currentLang === 'en' ? 'en-US' : 'id-ID';
                  formattedValue = Math.floor(currentValue).toLocaleString(locale);
              }
              
              counter.innerText = formattedValue;

              if (progress < 1) {
                  requestAnimationFrame(updateCount);
              } else {
                  const currentLang = (typeof GoSirkLang !== 'undefined') ? GoSirkLang.getCurrent() : 'id';
                  const locale = currentLang === 'en' ? 'en-US' : 'id-ID';
                  counter.innerText = decimals === 0 && target >= 1000 ? target.toLocaleString(locale) : target.toFixed(decimals);
              }
          };

          requestAnimationFrame(updateCount);
      };

      const observerOptions = {
          threshold: 0.2
      };

      const counterObserver = new IntersectionObserver((entries, observer) => {
          entries.forEach(entry => {
              if (entry.isIntersecting) {
                  animateCounter(entry.target);
                  observer.unobserve(entry.target);
              }
          });
      }, observerOptions);

      counters.forEach(counter => {
          counterObserver.observe(counter);
      });

      // Update counters when language changes
      window.addEventListener('languageChanged', function() {
          counters.forEach(counter => {
              const target = +counter.getAttribute('data-target');
              const decimals = +counter.getAttribute('data-decimals') || 0;
              const currentLang = GoSirkLang.getCurrent();
              const locale = currentLang === 'en' ? 'en-US' : 'id-ID';
              
              counter.innerText = (decimals === 0 && target >= 1000) ? 
                  target.toLocaleString(locale) : 
                  target.toFixed(decimals);
          });
      });
    </script>

    <style>
      /* Required-field marker on public forms (CSS so the language switcher can't remove it) */
      label.is-required::after { content: " *"; color: #dc3545; font-weight: 700; }
    </style>
    <script>
      // Public forms: required markers + inline validation messages in the current site language
      (function () {
        const lang = () => (localStorage.getItem('gosirk_language') || 'en') === 'id' ? 'id' : 'en';
        const T = {
          id: { required: (l) => `${l} wajib diisi.`, min: (l, n, c) => `${l} minimal ${n} karakter (sekarang ${c}).`, max: (l, n) => `${l} maksimal ${n} karakter.`, email: (l) => `${l} harus berupa alamat email yang valid.`, title: 'Data belum lengkap' },
          en: { required: (l) => `${l} is required.`, min: (l, n, c) => `${l} must be at least ${n} characters (currently ${c}).`, max: (l, n) => `${l} must be at most ${n} characters.`, email: (l) => `${l} must be a valid email address.`, title: 'Please complete the form' }
        };

        const labelFor = (el) => (el.id && document.querySelector(`label[for="${el.id}"]`)) || (el.closest('.mb-3, .mb-4, [class*="col-"]') || document).querySelector('label');
        const labelText = (el) => { const l = labelFor(el); return (l ? l.textContent : (el.dataset.label || '')).replace(/\*\s*$/, '').trim(); };

        const message = (el) => {
          const t = T[lang()], l = labelText(el), v = el.validity;
          if (v.valueMissing) return t.required(l);
          if (v.typeMismatch && el.type === 'email') return t.email(l);
          if (v.tooShort) return t.min(l, el.minLength, el.value.length);
          if (v.tooLong) return t.max(l, el.maxLength);
          return el.validationMessage;
        };

        const clear = (el) => {
          el.classList.remove('is-invalid');
          const fb = el.parentElement.querySelector(':scope > .invalid-feedback.js-feedback');
          if (fb) fb.remove();
        };
        const show = (el, msg) => {
          el.classList.add('is-invalid');
          let fb = el.parentElement.querySelector(':scope > .invalid-feedback.js-feedback');
          if (!fb) { fb = document.createElement('div'); fb.className = 'invalid-feedback js-feedback d-block'; el.insertAdjacentElement('afterend', fb); }
          fb.textContent = msg;
        };

        document.querySelectorAll('form').forEach((form) => {
          const fields = [...form.querySelectorAll('input, textarea, select')].filter((el) => el.hasAttribute('data-label'));
          if (!fields.length) return;

          fields.forEach((el) => { if (el.required) { const l = labelFor(el); if (l) l.classList.add('is-required'); } });

          form.setAttribute('novalidate', 'novalidate');
          form.addEventListener('submit', (e) => {
            fields.forEach(clear);
            const invalid = fields.filter((el) => !el.checkValidity());
            if (!invalid.length) return;
            // Stop the page's own submit handler (it sends the form with fetch)
            e.preventDefault();
            e.stopImmediatePropagation();
            invalid.forEach((el) => show(el, message(el)));
            invalid[0].focus();
          }, true);
          form.addEventListener('input', (e) => { if (e.target.classList.contains('is-invalid') && e.target.checkValidity()) clear(e.target); });
          // Start clean each time a modal form is reopened
          const modal = form.closest('.modal');
          if (modal) modal.addEventListener('hidden.bs.modal', () => fields.forEach(clear));
        });
      })();
    </script>
</body>
</html>
