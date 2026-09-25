<?php
// app/core/PageImages.php
// Fixed images and backgrounds on the public pages that admins can replace (Admin > Gambar Halaman).
// Each slot has a default (a file in assets/img/ or a full URL). A replacement is stored in the
// `settings` table as "img_slot.<key>" and the file lives in assets/img/pages/.

class PageImages {
    // key => [page, label, default]
    const SLOTS = [
        'home.cta_bg'         => ['Home', 'Background section CTA', 'https://images.pexels.com/photos/48148/document-agreement-documents-sign-48148.jpeg'],
        'home.banner'         => ['Home', 'Banner bawah halaman', 'img/banner-1.png'],
        'about.hero_bg'       => ['About Us', 'Background hero', 'img/about-2.jpg'],
        'about.banner'        => ['About Us', 'Banner bawah halaman', 'img/banner-1.png'],
        'ggc.program_1'       => ['GoSirk Green Community', 'Foto program 1', 'https://images.unsplash.com/photo-1526951521990-620dc14c214b?auto=format&fit=crop&q=80&w=800'],
        'ggc.program_2'       => ['GoSirk Green Community', 'Foto program 2', 'https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&q=80&w=800'],
        'ggc.program_3'       => ['GoSirk Green Community', 'Foto program 3', 'https://images.unsplash.com/photo-1531482615713-2afd69097998?auto=format&fit=crop&q=80&w=800'],
        'ggc.program_4'       => ['GoSirk Green Community', 'Foto program 4', 'https://images.unsplash.com/photo-1497215728101-856f4ea42174?auto=format&fit=crop&q=80&w=800'],
        'ggc.program_5'       => ['GoSirk Green Community', 'Foto program 5', 'https://images.unsplash.com/photo-1466692476868-aef1dfb1e735?auto=format&fit=crop&q=80&w=800'],
        'ggc.cta_bg'          => ['GoSirk Green Community', 'Background section CTA', 'https://images.unsplash.com/photo-1593113598332-cd288d649433?auto=format&fit=crop&q=80&w=1200'],
        'gnp.cta_bg'          => ['Go Ngompos Project', 'Background section CTA', 'https://images.unsplash.com/photo-1593113598332-cd288d649433?auto=format&fit=crop&q=80&w=1200'],
        'partner.service_1'   => ['Implementasi Partner', 'Foto jenis layanan 1', 'img/pexels-fauxels-3184416.jpg'],
        'partner.service_2'   => ['Implementasi Partner', 'Foto jenis layanan 2', 'img/IMG_8093.jpg'],
        'partner.service_3'   => ['Implementasi Partner', 'Foto jenis layanan 3', 'img/pexels-diva-plavalaguna-6147016.jpg'],
        'partner.service_4'   => ['Implementasi Partner', 'Foto jenis layanan 4', 'img/IMG_8082.jpg'],
        'partner.service_5'   => ['Implementasi Partner', 'Foto jenis layanan 5', 'img/DSC00079-1024x683.jpg'],
        'clocc.logo_1'        => ['Logo Mitra CLOCC', 'Logo 1 (tampil di Implementasi Partner & Partnership)', 'img/Logo-GoSirk-01.png'],
        'clocc.logo_2'        => ['Logo Mitra CLOCC', 'Logo 2', 'img/Logo CLOCC.png'],
        'clocc.logo_3'        => ['Logo Mitra CLOCC', 'Logo 3', 'img/logo-sirk-norge.png'],
        'clocc.logo_4'        => ['Logo Mitra CLOCC', 'Logo 4', 'img/logo-kab-tabanan.png'],
        'konsultan.service_1' => ['Konsultansi', 'Foto layanan 1', 'img/IMG_8084.jpg'],
        'konsultan.service_2' => ['Konsultansi', 'Foto layanan 2', 'img/IMG_0506-1536x1024.jpg'],
        'konsultan.service_3' => ['Konsultansi', 'Foto layanan 3', 'img/petugas-baju-biru.png'],
        'konsultan.service_4' => ['Konsultansi', 'Foto layanan 4', 'img/pexels-fauxels-3184416.jpg'],
        'partnership.hero_bg' => ['Partnership', 'Background hero', 'https://images.unsplash.com/photo-1517048676732-d65bc937f952?q=80&w=1920&auto=format&fit=crop'],
        'partnership.project' => ['Partnership', 'Foto "Proyek Sedang Berjalan"', 'img/petugas-baju-biru.png'],
        'partnership.cta_bg'  => ['Partnership', 'Background section CTA', 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=1200&auto=format&fit=crop'],
        'contact.banner'      => ['Contact', 'Banner bawah halaman', 'img/banner-2.png'],
        'portfolio.cta_bg'    => ['Detail Portfolio', 'Background section CTA', 'img/IMG_8084.jpg'],
    ];

    const FOLDER = 'img/pages';

    private static function saved() {
        static $saved = null;
        if ($saved === null) {
            $saved = [];
            try {
                if (!class_exists('Setting_model')) require_once dirname(__DIR__) . '/models/Setting_model.php';
                foreach ((new Setting_model())->getAll() as $k => $v) {
                    if (strpos($k, 'img_slot.') === 0 && $v !== '') $saved[substr($k, 9)] = $v;
                }
            } catch (Throwable $e) {
                error_log('[GoSirk] Gambar halaman tidak bisa dibaca: ' . $e->getMessage());
            }
        }
        return $saved;
    }

    /** Stored replacement file name for a slot, or null when the default is used. */
    public static function custom($key) {
        return self::saved()[$key] ?? null;
    }

    /** Public URL of the image for a slot. */
    public static function url($key) {
        $custom = self::custom($key);
        if ($custom) return ASSETS_URL . self::FOLDER . '/' . rawurlencode($custom);
        $default = self::SLOTS[$key][2] ?? '';
        if (preg_match('#^https?://#i', $default)) return $default;
        return ASSETS_URL . implode('/', array_map('rawurlencode', explode('/', $default)));
    }

    /** HTML-escaped URL for use inside src="" or style="". */
    public static function attr($key) {
        return htmlspecialchars(self::url($key), ENT_QUOTES);
    }
}
