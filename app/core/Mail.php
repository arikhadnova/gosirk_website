<?php
// app/core/Mail.php

class Mail {
    private static $config = null;

    /**
     * Mail settings: values saved in Admin > Email Settings win, otherwise .env / config.php.
     */
    public static function config() {
        if (self::$config !== null) return self::$config;
        $saved = [];
        try {
            if (!class_exists('Setting_model')) require_once dirname(__DIR__) . '/models/Setting_model.php';
            $saved = (new Setting_model())->getAll();
        } catch (Throwable $e) {
            error_log('[GoSirk] Pengaturan email tidak bisa dibaca dari database: ' . $e->getMessage());
        }
        $pick = fn($key, $fallback) => trim((string) ($saved[$key] ?? '')) !== '' ? trim($saved[$key]) : $fallback;
        $envKey = BREVO_API_KEY === 'YOUR_BREVO_API_KEY_HERE' ? '' : BREVO_API_KEY;
        return self::$config = [
            'api_key'    => $pick('mail_brevo_api_key', $envKey),
            'from'       => $pick('mail_from', MAIL_FROM),
            'from_name'  => $pick('mail_from_name', MAIL_FROM_NAME),
            'admin'      => $pick('mail_admin_address', MAIL_ADMIN_ADDRESS),
        ];
    }

    // Editable email templates (Admin > Email Settings). Body is plain text: new lines are kept,
    // **text** becomes bold, and {placeholders} are replaced with escaped values.
    const TEMPLATES = [
        'doc_user' => [
            'label' => 'Email dokumen ke pemohon',
            'hint' => 'Dikirim ke pengunjung bersama lampiran PDF (dokumen dengan pengiriman otomatis).',
            'placeholders' => ['nama', 'email', 'instansi', 'jabatan', 'dokumen', 'situs'],
            'subject' => 'Dokumen yang Anda Minta: {dokumen}',
            'body' => "Halo {nama},\n\nTerima kasih telah tertarik dengan inisiatif kami. Terlampir adalah dokumen **{dokumen}** yang Anda minta.\n\nSalam,\n{situs}",
        ],
        'doc_admin' => [
            'label' => 'Notifikasi permintaan dokumen (ke admin)',
            'hint' => 'Jika dokumen harus dikirim manual (pengiriman otomatis nonaktif atau gagal), subjek otomatis diawali "[Perlu Tindak Lanjut]".',
            'placeholders' => ['nama', 'email', 'instansi', 'jabatan', 'dokumen', 'status_pengiriman', 'situs'],
            'subject' => 'Permintaan Dokumen Baru: {dokumen}',
            'body' => "Halo Admin,\n\nSeseorang telah meminta dokumen:\n**Nama:** {nama}\n**Email:** {email}\n**Instansi:** {instansi}\n**Jabatan:** {jabatan}\n**Dokumen:** {dokumen}\n**Email ke pemohon:** {status_pengiriman}\n\nSilakan tindak lanjuti jika diperlukan.",
        ],
        'contact_admin' => [
            'label' => 'Notifikasi pesan kontak (ke admin)',
            'hint' => 'Dikirim saat pengunjung mengisi form di halaman Contact.',
            'placeholders' => ['nama', 'email', 'pesan', 'situs'],
            'subject' => 'Pesan Baru dari {nama}',
            'body' => "**Pesan Baru dari Website**\n\n**Nama:** {nama}\n**Email:** {email}\n**Pesan:**\n{pesan}\n\nPesan ini juga tersimpan di Dashboard Admin GoSirk.",
        ],
    ];

    /** Saved (or default) subject/body text for a template, before placeholders are filled. */
    public static function template($key) {
        $def = self::TEMPLATES[$key];
        $saved = [];
        try {
            if (!class_exists('Setting_model')) require_once dirname(__DIR__) . '/models/Setting_model.php';
            $saved = (new Setting_model())->getAll();
        } catch (Throwable $e) {
            error_log('[GoSirk] Template email tidak bisa dibaca: ' . $e->getMessage());
        }
        $subject = trim((string) ($saved["mail_tpl_{$key}_subject"] ?? ''));
        $body = trim((string) ($saved["mail_tpl_{$key}_body"] ?? ''));
        return ['subject' => $subject !== '' ? $subject : $def['subject'], 'body' => $body !== '' ? $body : $def['body']];
    }

    /** Fill a template: returns ['subject' => plain text, 'html' => HTML body]. */
    public static function render($key, array $vars) {
        $tpl = self::template($key);
        $vars['situs'] = $vars['situs'] ?? SITE_NAME;
        $plain = [];
        $html = [];
        foreach ($vars as $k => $v) {
            $plain['{' . $k . '}'] = preg_replace('/\s+/', ' ', (string) $v);
            $html['{' . $k . '}'] = htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8'); // line breaks handled below
        }
        $body = htmlspecialchars($tpl['body'], ENT_QUOTES, 'UTF-8');
        $body = strtr($body, $html);
        $body = preg_replace('/\*\*(.+?)\*\*/s', '<b>$1</b>', $body);
        $body = '<div style="font-family: Arial, sans-serif; font-size: 14px; line-height: 1.6; color: #333;">' . nl2br($body, false) . '</div>';
        return ['subject' => strtr($tpl['subject'], $plain), 'html' => $body];
    }

    public static function resetConfig() {
        self::$config = null;
    }

    /** Read-only check against Brevo: is the key valid and is the sender verified? */
    public static function checkConnection() {
        $c = self::config();
        if ($c['api_key'] === '') return ['ok' => false, 'messages' => ['API key Brevo belum diisi.']];

        $get = function ($path) use ($c) {
            $ch = curl_init('https://api.brevo.com/v3/' . $path);
            curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 15,
                CURLOPT_HTTPHEADER => ['api-key: ' . $c['api_key'], 'Accept: application/json']]);
            $body = curl_exec($ch);
            $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $err = curl_error($ch);
            curl_close($ch);
            return [$code, json_decode((string) $body, true) ?: [], $err];
        };

        [$code, $account, $err] = $get('account');
        if ($err) return ['ok' => false, 'messages' => ['Tidak bisa terhubung ke Brevo: ' . $err]];
        if ($code !== 200) return ['ok' => false, 'messages' => ['API key ditolak Brevo (HTTP ' . $code . '): ' . ($account['message'] ?? 'tidak diketahui')]];

        $messages = ['API key valid (akun: ' . ($account['email'] ?? '-') . ').'];
        [, $senders] = $get('senders');
        $verified = array_filter($senders['senders'] ?? [], fn($s) => strcasecmp($s['email'] ?? '', $c['from']) === 0 && !empty($s['active']));
        if ($verified) {
            $messages[] = 'Email pengirim ' . $c['from'] . ' sudah terverifikasi.';
            return ['ok' => true, 'messages' => $messages];
        }
        $messages[] = 'Email pengirim ' . $c['from'] . ' BELUM terverifikasi di Brevo (menu Senders, Domains & Dedicated IPs). Email akan ditolak sampai diverifikasi.';
        return ['ok' => false, 'messages' => $messages];
    }

    /**
     * Send an email using Brevo API (v3)
     * @param string $to Recipient email
     * @param string $subject Email subject
     * @param string $message Email body (HTML)
     * @param string $attachmentPath Optional absolute path to attachment
     * @param string $attachmentName Optional custom name for the attachment
     * @return bool
     */
    public static function send($to, $subject, $message, $attachmentPath = null, $attachmentName = null) {
        $c = self::config();
        if ($c['api_key'] === '') {
            error_log('[GoSirk] Email tidak dikirim: API key Brevo belum diisi (Admin > Email Settings atau .env)');
            return false;
        }

        $url = 'https://api.brevo.com/v3/smtp/email';
        
        $data = [
            'sender' => [
                'name' => $c['from_name'],
                'email' => $c['from']
            ],
            'to' => [
                ['email' => $to]
            ],
            'subject' => $subject,
            'htmlContent' => $message
        ];

        // Handle attachment if provided
        if ($attachmentPath && file_exists($attachmentPath)) {
            $fileName = $attachmentName ?: basename($attachmentPath);
            $content = base64_encode(file_get_contents($attachmentPath));
            
            $data['attachment'] = [
                [
                    'content' => $content,
                    'name' => $fileName
                ]
            ];
        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'api-key: ' . $c['api_key'],
            'Content-Type: application/json',
            'Accept: application/json'
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        $ok = ($httpCode === 201 || $httpCode === 200);
        if (!$ok) {
            // Keep the reason (invalid key, unverified sender, ...) in the server log
            $reason = $curlError ?: (json_decode((string) $response, true)['message'] ?? substr((string) $response, 0, 200));
            error_log('[GoSirk] Brevo gagal kirim ke ' . $to . " (HTTP $httpCode): " . $reason);
        }
        return $ok;
    }

    /**
     * Send a notification to Admin via Brevo API
     * @param string $subject
     * @param string $content
     * @return bool
     */
    public static function sendToAdmin($subject, $content) {
        $admin = self::config()['admin'];
        if ($admin === '') return false;
        return self::send($admin, $subject, $content);
    }
}
