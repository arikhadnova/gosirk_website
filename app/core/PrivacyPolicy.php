<?php
// app/core/PrivacyPolicy.php
// Content of the /privacy page: one rich-text document per language, edited in
// Admin > Pengaturan > Situs > Kebijakan Privasi and stored in `settings`
// (privacy.content_id, privacy.content_en, privacy.updated_at).
// Until an admin saves it, the default text below is shown.

class PrivacyPolicy {
    const DEFAULT_UPDATED = '2026-09-26';

    const DEFAULTS = [
        'id' => <<<'HTML'
<p>PT Gocircular Solutions Indonesia ("GoSirk") menghargai privasi Anda. Halaman ini menjelaskan data apa yang kami kumpulkan melalui website ini, untuk apa data tersebut digunakan, dan hak Anda atas data tersebut.</p>
<h2>Data yang kami kumpulkan</h2>
<ul><li><strong>Formulir kontak:</strong> nama, email, dan pesan Anda.</li><li><strong>Permintaan dokumen dan unduhan publikasi:</strong> nama, email, instansi, dan jabatan.</li><li><strong>Statistik kunjungan:</strong> alamat IP dan jenis browser, untuk menghitung jumlah pengunjung.</li></ul>
<h2>Untuk apa data digunakan</h2>
<ul><li>Mengirimkan dokumen yang Anda minta dan membalas pesan Anda.</li><li>Menghubungi Anda terkait program dan kolaborasi GoSirk yang relevan.</li><li>Memahami minat pengunjung untuk meningkatkan layanan kami.</li></ul>
<p>Kami tidak menjual atau menyewakan data Anda kepada pihak lain.</p>
<h2>Pihak ketiga</h2>
<p>Email dikirim melalui layanan Brevo. Data hanya dibagikan sejauh diperlukan untuk mengirimkan email tersebut.</p>
<h2>Data yang tersimpan di perangkat Anda</h2>
<p>Website ini menyimpan pilihan bahasa dan data formulir terakhir Anda di browser (localStorage) selama 30 hari, agar formulir berikutnya terisi otomatis. Anda dapat menghapusnya kapan saja dengan menghapus data situs di pengaturan browser.</p>
<h2>Keamanan dan lama penyimpanan</h2>
<p>Data disimpan di server kami dan hanya dapat diakses oleh tim GoSirk yang berwenang. Data disimpan selama masih diperlukan untuk tujuan di atas.</p>
<h2>Hak Anda</h2>
<p>Anda dapat meminta untuk melihat, memperbaiki, atau menghapus data Anda dengan menghubungi kami melalui email berikut:</p>
HTML,
        'en' => <<<'HTML'
<p>PT Gocircular Solutions Indonesia ("GoSirk") respects your privacy. This page explains what data we collect through this website, what we use it for, and your rights over that data.</p>
<h2>Data we collect</h2>
<ul><li><strong>Contact form:</strong> your name, email, and message.</li><li><strong>Document requests and publication downloads:</strong> name, email, organization, and position.</li><li><strong>Visit statistics:</strong> IP address and browser type, to count visitors.</li></ul>
<h2>How we use the data</h2>
<ul><li>Sending the documents you request and replying to your messages.</li><li>Contacting you about relevant GoSirk programs and collaborations.</li><li>Understanding visitor interests to improve our services.</li></ul>
<p>We do not sell or rent your data to anyone.</p>
<h2>Third parties</h2>
<p>Emails are sent through the Brevo service. Data is shared only as far as needed to deliver those emails.</p>
<h2>Data stored on your device</h2>
<p>This website stores your language choice and your last form details in your browser (localStorage) for 30 days, so the next form is filled in automatically. You can remove them at any time by clearing this site's data in your browser settings.</p>
<h2>Security and retention</h2>
<p>Data is stored on our servers and can only be accessed by authorized GoSirk staff. It is kept for as long as it is needed for the purposes above.</p>
<h2>Your rights</h2>
<p>You can ask to see, correct, or delete your data by contacting us at the following email:</p>
HTML,
    ];

    private static function settings() {
        static $s = null;
        if ($s === null) {
            if (!class_exists('Setting_model')) require_once dirname(__DIR__) . '/models/Setting_model.php';
            $s = new Setting_model();
        }
        return $s;
    }

    /** HTML content for 'id' or 'en' (default text until an admin saves it). */
    public static function content($lang) {
        $saved = trim((string) self::settings()->getByKey("privacy.content_$lang"));
        return $saved !== '' ? $saved : self::DEFAULTS[$lang];
    }

    /** Date of the last change, Y-m-d. */
    public static function updatedAt() {
        return self::settings()->getByKey('privacy.updated_at') ?: self::DEFAULT_UPDATED;
    }

    public static function save($contentId, $contentEn) {
        return self::settings()->updateMultiple([
            'privacy.content_id' => self::clean($contentId),
            'privacy.content_en' => self::clean($contentEn),
            'privacy.updated_at' => date('Y-m-d'),
        ]);
    }

    /** Keep only formatting tags from the editor; no scripts, event handlers or javascript: links. */
    public static function clean($html) {
        $html = preg_replace('#<(script|style|iframe|object)\b[^>]*>.*?</\1\s*>#is', '', (string) $html);
        $html = strip_tags($html, '<h2><h3><h4><p><br><strong><b><em><i><u><a><ul><ol><li><blockquote><table><thead><tbody><tr><th><td><figure>');
        $html = preg_replace('/\s+on[a-z]+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $html);
        $html = preg_replace('/(href|src)\s*=\s*(["\']?)\s*(javascript|data|vbscript):[^"\'>\s]*\2/i', '$1="#"', $html);
        return trim($html);
    }

    /** "26 September 2026" (id) / "September 26, 2026" (en) */
    public static function formatDate($ymd, $lang) {
        $t = strtotime($ymd) ?: time();
        if ($lang === 'en') return date('F j, Y', $t);
        $months = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        return date('j', $t) . ' ' . $months[(int) date('n', $t)] . ' ' . date('Y', $t);
    }
}
