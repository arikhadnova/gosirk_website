<?php
$email = $data['contact_email'] ?: 'medcom.gosirk@gmail.com';
$updated = PrivacyPolicy::updatedAt();
?>
<section class="section py-5 bg-light privacy-page">
    <div class="container">
        <div class="mx-auto" style="max-width: 820px;">
            <h1 class="fw-bold mb-2" data-i18n="privacy.title">Kebijakan Privasi</h1>
            <p class="text-muted small mb-4">
                <span data-i18n="privacy.updated_label">Terakhir diperbarui</span>:
                <span data-lang-id="<?= PrivacyPolicy::formatDate($updated, 'id') ?>" data-lang-en="<?= PrivacyPolicy::formatDate($updated, 'en') ?>"><?= PrivacyPolicy::formatDate($updated, 'id') ?></span>
            </p>
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 p-lg-5">
                    <!-- Written in Admin > Pengaturan > Situs > Kebijakan Privasi -->
                    <div class="privacy-body" data-privacy-lang="id"><?= PrivacyPolicy::content('id') ?></div>
                    <div class="privacy-body d-none" data-privacy-lang="en"><?= PrivacyPolicy::content('en') ?></div>
                    <p class="mb-0 mt-3"><a href="mailto:<?= htmlspecialchars($email) ?>" class="fw-semibold"><i class="fas fa-envelope me-1"></i><?= htmlspecialchars($email) ?></a></p>
                </div>
            </div>
        </div>
    </div>
</section>
<style>
    .privacy-page { margin-top: 70px; }
    .privacy-body h2 { font-size: 1.15rem; font-weight: 700; margin: 1.75rem 0 .6rem; }
    .privacy-body h3 { font-size: 1.05rem; font-weight: 700; margin: 1.25rem 0 .5rem; }
    .privacy-body ul, .privacy-body ol { padding-left: 1.2rem; margin-bottom: .75rem; }
    .privacy-body li { margin-bottom: .35rem; }
    .privacy-body > :first-child { margin-top: 0; }
    .privacy-body table { width: 100%; margin-bottom: 1rem; }
    .privacy-body td, .privacy-body th { border: 1px solid #e5e7eb; padding: .4rem .6rem; }
</style>
<script>
    // Show the document in the visitor's language
    (function () {
        function show(lang) {
            document.querySelectorAll('[data-privacy-lang]').forEach((el) => el.classList.toggle('d-none', el.dataset.privacyLang !== (lang === 'en' ? 'en' : 'id')));
        }
        document.addEventListener('DOMContentLoaded', () => show(typeof GoSirkLang !== 'undefined' ? GoSirkLang.getCurrent() : 'id'));
        window.addEventListener('languageChanged', (e) => show(e.detail.language));
    })();
</script>
