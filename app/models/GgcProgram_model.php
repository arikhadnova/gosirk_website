<?php
// Program cards on the GoSirk Green Community page (Admin > GoSirk Green Community > Program).
// Same fields and behaviour as the Go Ngompos programs; only the table and image folder differ.

require_once __DIR__ . '/GnpProgram_model.php';

class GgcProgram_model extends GnpProgram_model {
    protected $table = 'ggc_programs';
    const FOLDER = 'img/ggc';

    // The five programs that were written in the page until now. Texts edited in Teks Halaman and
    // images replaced in Gambar Halaman before this table existed are carried over.
    protected function seed() {
        $rows = [
            // badge ID/EN, colour, title ID/EN, description ID/EN, old image slot, default image
            ['Pemberdayaan', 'Empowerment', 'success', 'Pelatihan Pengolahan Sampah', 'Waste Processing Training',
             'Meningkatkan kemampuan kader desa dalam pemilahan dan pengolahan sampah.', "Improving village cadres' skills in waste sorting and processing.",
             'ggc.program_1', 'https://images.unsplash.com/photo-1526951521990-620dc14c214b?auto=format&fit=crop&q=80&w=800'],
            ['Edukasi', 'Education', 'primary', 'Pelatihan Kader (PRA)', 'Cadre Training (PRA)',
             'Mendorong peran kader PKK dalam pengelolaan sampah rumah tangga.', "Driving PKK cadres' roles in household waste management.",
             'ggc.program_2', 'https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&q=80&w=800'],
            ['Pendampingan', 'Mentoring', 'orange', 'Pendampingan & Edukasi', 'Mentoring & Education',
             'Praktik pengelolaan sampah berkelanjutan di desa binaan.', 'Sustainable waste management practices in fostered villages.',
             'ggc.program_3', 'https://images.unsplash.com/photo-1531482615713-2afd69097998?auto=format&fit=crop&q=80&w=800'],
            ['Lingkungan', 'Environment', 'info', 'Pengurangan Emisi', 'Emission Reduction',
             'Berkontribusi dalam pengurangan emisi gas rumah kaca.', 'Contributing to the reduction of greenhouse gas emissions.',
             'ggc.program_4', 'https://images.unsplash.com/photo-1497215728101-856f4ea42174?auto=format&fit=crop&q=80&w=800'],
            ['Ketahanan Pangan', 'Food Security', 'warning', 'Penanaman Bibit Tanaman', 'Plant Seedling Planting',
             'Mendukung ketahanan pangan dan pemenuhan tanaman obat.', 'Supporting food security and medicinal plant fulfillment.',
             'ggc.program_5', 'https://images.unsplash.com/photo-1466692476868-aef1dfb1e735?auto=format&fit=crop&q=80&w=800'],
        ];

        $overrides = $this->textOverrides();
        $seed = [];
        foreach ($rows as $i => [$badgeId, $badgeEn, $color, $titleId, $titleEn, $descId, $descEn, $slot, $defaultImage]) {
            $n = $i + 1;
            foreach (['badge' => [&$badgeId, &$badgeEn], 'title' => [&$titleId, &$titleEn], 'desc' => [&$descId, &$descEn]] as $part => $vals) {
                $o = $overrides["ggc.programs.p{$n}_{$part}"] ?? null;
                if ($o && trim((string) $o['id']) !== '') $vals[0] = $o['id'];
                if ($o && trim((string) $o['en']) !== '') $vals[1] = $o['en'];
            }
            $custom = $this->slotImage($slot);
            $seed[] = [$badgeId, $badgeEn, $color, $titleId, $titleEn, $descId, $descEn, $custom ? 'pages/' . $custom : $defaultImage, $n];
        }
        return $seed;
    }

    private function textOverrides() {
        try {
            if (!class_exists('PageText_model')) require_once __DIR__ . '/PageText_model.php';
            return (new PageText_model())->getAll();
        } catch (Throwable $e) {
            return [];
        }
    }

    private function slotImage($slot) {
        $this->db->query("SELECT setting_value FROM settings WHERE setting_key = :k");
        $this->db->bind(':k', 'img_slot.' . $slot);
        $row = $this->db->single();
        return $row && trim((string) $row->setting_value) !== '' ? $row->setting_value : null;
    }
}
