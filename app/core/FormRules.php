<?php
// app/core/FormRules.php
// Single source of truth for admin form validation: used for HTML attributes
// in the views (client-side) and by Admin::validateForm() (server-side).

class FormRules {
    const PHONE = '/^[0-9+\(\)\-\s]{9,20}$/';
    const USERNAME = '/^[A-Za-z0-9_.]+$/';

    // form => field => [label, 'rule|rule|...']  ('|' separates rules, so regex rules must not contain '|'; use in:a,b,c for lists)
    // Extra tokens: required_store (required only when creating), editor (rich-text field:
    // server-side only), file (upload input; required/required_store checks the upload).
    const FORMS = [
        'article' => [
            'title_id'   => ['Judul', 'required|min:5|max:255'],
            'content_id' => ['Konten', 'required|min:20|editor'],
            'tags'       => ['Tags', 'max:255'],
        ],
        'collaboration' => [
            'title_id' => ['Judul Dokumen', 'required|min:3|max:255'],
            'type'     => ['Tipe Dokumen', 'required|in:executive_summary,company_profile,concept_note'],
            'status'   => ['Status', 'required'],
            'document' => ['File Dokumen', 'file|required_store'],
        ],
        'faq' => [
            'page'        => ['Halaman', 'required'],
            'question_id' => ['Pertanyaan', 'required|min:5|max:500'],
            'answer_id'   => ['Jawaban', 'required|min:5|max:2000'],
            'sort_order'  => ['Urutan', 'int|min_value:0'],
        ],
        'founder' => [
            'name'          => ['Nama', 'required|min:3|max:255'],
            'role_id'       => ['Jabatan', 'required|min:2|max:255'],
            'quote_id'      => ['Kutipan', 'max:1000'],
            'linkedin_url'  => ['URL LinkedIn', 'url|max:255'],
            'display_order' => ['Urutan Tampil', 'int|min_value:0'],
            'image'         => ['Foto', 'file|required_store'],
        ],
        'ggc_action' => [
            'title_id'       => ['Judul', 'required|min:3|max:255'],
            'description_id' => ['Deskripsi', 'required|min:10|max:1000'],
            'order_priority' => ['Urutan', 'int|min_value:0'],
            'image'          => ['Gambar', 'file|required_store'],
        ],
        'gi_service' => [
            'title_id'          => ['Judul Layanan', 'required|min:5|max:255'],
            'description_id'    => ['Deskripsi Singkat', 'required|min:10|max:1000'],
            'detail_content_id' => ['Konten Detail', 'editor'],
            'category'          => ['Kategori', 'required'],
            'location_id'       => ['Lokasi', 'max:255'],
            'service_type_id'   => ['Tipe Layanan', 'max:255'],
            'order_priority'    => ['Urutan', 'int|min_value:0'],
        ],
        'gi_video' => [
            'url'            => ['URL Video YouTube', 'required|url|youtube|max:255'],
            'order_priority' => ['Urutan Prioritas', 'int|min_value:0'],
        ],
        'gi_video_section' => [
            'title_id'    => ['Judul', 'max:255'],
            'content_id'  => ['Subjudul', 'max:500'],
            'youtube_url' => ['Link Tombol YouTube', 'url|max:255'],
        ],
        'hero' => [
            'tag_id'      => ['Tag', 'max:255'],
            'title_id'    => ['Judul', 'required|min:3|max:500'],
            'subtitle_id' => ['Subjudul', 'max:1000'],
        ],
        'impact' => [
            'label_id'         => ['Label', 'required|min:2|max:255'],
            'value'            => ['Nilai', 'required|max:100'],
            'unit'             => ['Satuan', 'max:50'],
            'page'             => ['Halaman', 'required'],
            'section'          => ['Section', 'required'],
            'section_title_id' => ['Judul Section', 'max:255'],
            'note_id'          => ['Catatan', 'max:500'],
            'order_num'        => ['Urutan', 'int|min_value:0'],
        ],
        'page_section' => [
            'badge_id'   => ['Badge', 'max:255'],
            'title_id'   => ['Judul', 'required|min:3|max:500'],
            'content_id' => ['Konten', 'required|min:10'],
        ],
        'partner' => [
            'name'     => ['Nama Partner', 'required|min:2|max:255'],
            'type'     => ['Tipe', 'required'],
            'category' => ['Kategori', 'required'],
            'logo'     => ['Logo', 'file|required_store'],
        ],
        'partnership_settings' => [
            'ps_comm_title_id' => ['Judul Community', 'max:255'],
            'ps_comm_sub_id'   => ['Subjudul Community', 'max:255'],
            'ps_comm_desc_id'  => ['Deskripsi Community', 'max:1000'],
            'ps_acad_title_id' => ['Judul Academic', 'max:255'],
            'ps_acad_sub_id'   => ['Subjudul Academic', 'max:255'],
            'ps_acad_desc_id'  => ['Deskripsi Academic', 'max:1000'],
            'ps_prog_title_id' => ['Judul Program', 'max:255'],
            'ps_prog_sub_id'   => ['Subjudul Program', 'max:255'],
            'ps_prog_desc_id'  => ['Deskripsi Program', 'max:1000'],
        ],
        'pilot_village' => [
            'name_id'        => ['Nama Desa', 'required|min:3|max:255'],
            'order_priority' => ['Urutan', 'int|min_value:0'],
            'image'          => ['Gambar', 'file|required_store'],
        ],
        'portfolio' => [
            'title_id'          => ['Judul Proyek', 'required|min:5|max:255'],
            'subtitle_id'       => ['Sub-judul', 'max:255'],
            'description_id'    => ['Deskripsi Ringkas', 'max:500'],
            'icon_name'         => ['Icon FontAwesome', 'max:100'],
            'client_name'       => ['Client Name', 'max:255'],
            'detail_content_id' => ['Tentang Proyek', 'editor'],
        ],
        'publication' => [
            'title_id'       => ['Judul', 'required|min:5|max:255'],
            'description_id' => ['Deskripsi', 'max:2000'],
            'type'           => ['Tipe', 'required'],
            'external_link'  => ['Link Eksternal', 'url|max:255'],
            'price'          => ['Harga', 'numeric|min_value:0'],
        ],
        'service_item' => [
            'title_id'       => ['Judul', 'required|min:3|max:255'],
            'description_id' => ['Deskripsi', 'required|min:10|max:1000'],
            'link_url'       => ['Link', 'max:255'],
            'partner_name'   => ['Nama Partner', 'max:255'],
            'order_priority' => ['Urutan', 'int|min_value:0'],
            'image'          => ['Gambar', 'file|required_store'],
        ],
        'service' => [
            'name_id'        => ['Nama Layanan', 'required|min:3|max:255'],
            'description_id' => ['Deskripsi', 'max:2000'],
            'order_priority' => ['Urutan', 'int|min_value:0'],
        ],
        'settings_header' => [
            'site_title'       => ['Judul Website', 'required|min:3|max:255'],
            'site_description' => ['Deskripsi Website', 'max:500'],
        ],
        'settings_footer' => [
            'footer_text'      => ['Copyright Text', 'max:255'],
            'contact_email'    => ['Email Kontak', 'email|max:100'],
            'contact_whatsapp' => ['WhatsApp Kontak', 'regex:' . self::PHONE],
            'office_hours'     => ['Jam Kerja', 'max:100'],
            'address_hq'       => ['Alamat Kantor Pusat', 'max:500'],
            'address_branch'   => ['Alamat Kantor Cabang', 'max:500'],
            'social_facebook'  => ['Facebook', 'url|max:255'],
            'social_instagram' => ['Instagram', 'url|max:255'],
            'social_linkedin'  => ['LinkedIn', 'url|max:255'],
            'social_youtube'   => ['YouTube', 'url|max:255'],
        ],
        'testimonial' => [
            'name'       => ['Nama', 'required|min:2|max:255'],
            'role_id'    => ['Jabatan / Instansi', 'required|min:2|max:255'],
            'content_id' => ['Isi Testimoni', 'required|min:10|max:1000'],
            'page'       => ['Halaman', 'required'],
            'status'     => ['Status', 'required'],
        ],
        'user' => [
            'name'     => ['Nama', 'required|min:3|max:255'],
            'username' => ['Username', 'required|min:3|max:100|regex:' . self::USERNAME],
            'password' => ['Password', 'required_store|min:8|max:255'],
        ],
        'profile' => [
            'name'     => ['Nama', 'required|min:3|max:255'],
            'username' => ['Username', 'required|min:3|max:100|regex:' . self::USERNAME],
        ],
        'email_settings' => [
            'mail_from'          => ['Email Pengirim', 'required|email|max:100'],
            'mail_from_name'     => ['Nama Pengirim', 'required|min:2|max:100'],
            'mail_admin_address' => ['Email Admin (Notifikasi)', 'required|email|max:100'],
            'mail_brevo_api_key' => ['API Key Brevo', 'min:20|max:255'],
        ],
        'email_templates' => [
            'mail_tpl_doc_user_subject' => ['Subjek Email dokumen', 'max:200'],
            'mail_tpl_doc_user_body'    => ['Isi Email dokumen', 'max:5000'],
            'mail_tpl_doc_admin_subject' => ['Subjek Notifikasi permintaan dokumen', 'max:200'],
            'mail_tpl_doc_admin_body'    => ['Isi Notifikasi permintaan dokumen', 'max:5000'],
            'mail_tpl_contact_admin_subject' => ['Subjek Notifikasi pesan kontak', 'max:200'],
            'mail_tpl_contact_admin_body'    => ['Isi Notifikasi pesan kontak', 'max:5000'],
        ],
        'gnp_program' => [
            'title_id'       => ['Judul Program', 'required|min:3|max:255'],
            'description_id' => ['Deskripsi', 'required|min:10|max:1000'],
            'badge_id'       => ['Label', 'max:100'],
            'badge_color'    => ['Warna Label', 'in:success,primary,orange,warning,dark'],
            'order_priority' => ['Urutan', 'int|min_value:0'],
            'image'          => ['Foto', 'file|required_store'],
        ],
        'gnp_program_section' => [
            'title_id'   => ['Judul Section', 'max:255'],
            'content_id' => ['Subjudul', 'max:500'],
        ],
        // Public forms
        'contact' => [
            'name'    => ['Nama', 'required|min:2|max:100'],
            'email'   => ['Email', 'required|email|max:100'],
            'message' => ['Pesan', 'required|min:10|max:3000'],
        ],
        'doc_request' => [
            'doc_id'       => ['Dokumen', 'required|int'],
            'name'         => ['Nama', 'required|min:2|max:100'],
            'email'        => ['Email', 'required|email|max:100'],
            'organization' => ['Instansi', 'required|min:2|max:150'],
            'jabatan'      => ['Jabatan', 'required|min:2|max:100'],
        ],
        'password_change' => [
            'current_password' => ['Password Lama', 'required'],
            'new_password'     => ['Password Baru', 'required|min:8|max:255'],
            'confirm_password' => ['Konfirmasi Password', 'required'],
        ],
    ];

    private static function parse($form, $field) {
        if (!isset(self::FORMS[$form][$field])) return null;
        [$label, $rules] = self::FORMS[$form][$field];
        return [$label, explode('|', $rules)];
    }

    /** Tidy submitted values in place: add https:// to URL fields typed without a scheme. */
    public static function normalize($form, array &$data) {
        foreach (self::FORMS[$form] ?? [] as $field => [$label, $rules]) {
            if (!in_array('url', explode('|', $rules), true) || !isset($data[$field]) || !is_string($data[$field])) continue;
            $v = trim($data[$field]);
            if ($v !== '' && !preg_match('#^[a-z][a-z0-9+.-]*://#i', $v)) {
                $v = 'https://' . ltrim($v, '/');
            }
            $data[$field] = $v;
        }
    }

    /** Server-side check. Returns Validator-style errors. $mode is 'store' or 'update'. */
    public static function validate($form, $data, $files = [], $mode = 'store') {
        $rules = [];
        $labels = [];
        $errors = [];

        foreach (self::FORMS[$form] ?? [] as $field => $_) {
            [$label, $tokens] = self::parse($form, $field);
            $labels[$field] = $label;

            if (in_array('file', $tokens, true)) {
                $needed = in_array('required', $tokens, true) || ($mode === 'store' && in_array('required_store', $tokens, true));
                $err = $files[$field]['error'] ?? UPLOAD_ERR_NO_FILE;
                if ($needed && $err === UPLOAD_ERR_NO_FILE) {
                    $errors[$field][] = "$label wajib diupload.";
                } elseif ($err === UPLOAD_ERR_INI_SIZE || $err === UPLOAD_ERR_FORM_SIZE) {
                    $errors[$field][] = "$label melebihi batas ukuran " . Upload::maxSizeLabel() . '.';
                } elseif ($err !== UPLOAD_ERR_OK && $err !== UPLOAD_ERR_NO_FILE) {
                    $errors[$field][] = "$label gagal diupload (kode $err).";
                }
                continue;
            }

            $list = [];
            foreach ($tokens as $t) {
                if ($t === 'editor') continue;
                if ($t === 'required_store') {
                    if ($mode === 'store') $list[] = 'required';
                    continue;
                }
                $list[] = $t;
            }
            $rules[$field] = $list;
        }

        return array_merge_recursive($errors, Validator::validate($data, $rules, $labels));
    }

    /** HTML validation attributes for a field, e.g. <input name="x" <?= FormRules::attrs('form','x') ?>> */
    public static function attrs($form, $field, $mode = 'store') {
        $parsed = self::parse($form, $field);
        if (!$parsed) return '';
        [$label, $tokens] = $parsed;
        if (in_array('editor', $tokens, true)) {
            // CKEditor hides the textarea, so admin_footer.php checks the editor content instead
            $attrs = ['data-label="' . htmlspecialchars($label, ENT_QUOTES) . '"'];
            if (in_array('required', $tokens, true)) $attrs[] = 'data-editor-required';
            foreach ($tokens as $t) {
                if (strpos($t, 'min:') === 0) $attrs[] = 'data-editor-min="' . (int) substr($t, 4) . '"';
            }
            return implode(' ', $attrs);
        }

        $attrs = ['data-label="' . htmlspecialchars($label, ENT_QUOTES) . '"'];
        foreach ($tokens as $t) {
            [$name, $param] = array_pad(explode(':', $t, 2), 2, null);
            if ($name === 'required' || ($name === 'required_store' && $mode === 'store')) $attrs[] = 'required';
            if ($name === 'min') $attrs[] = 'minlength="' . (int) $param . '"';
            if ($name === 'max') $attrs[] = 'maxlength="' . (int) $param . '"';
            if ($name === 'min_value') $attrs[] = 'min="' . $param . '"';
            if ($name === 'regex') {
                // '/^...$/' -> HTML pattern (anchors are implicit)
                $pattern = preg_replace('#^/\^?|\$?/[a-z]*$#', '', $param);
                $attrs[] = 'pattern="' . htmlspecialchars($pattern, ENT_QUOTES) . '"';
            }
        }
        return implode(' ', array_unique($attrs));
    }
}
