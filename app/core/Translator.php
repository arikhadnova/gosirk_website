<?php

class Translator {
    // Texts that could not be translated in this request; Flasher shows them to the admin
    private static $failures = [];

    public static function takeFailures() {
        $failures = self::$failures;
        self::$failures = [];
        return $failures;
    }

    private static function fail($text) {
        $plain = trim(preg_replace('/\s+/u', ' ', html_entity_decode(strip_tags((string) $text), ENT_QUOTES, 'UTF-8')));
        self::$failures[] = mb_strlen($plain) > 60 ? mb_substr($plain, 0, 60) . '...' : $plain;
        return $text;
    }

    /**
     * Translates text from Indonesian to English using Google's free endpoints.
     * client=gtx now answers PHP requests with 429 ("automated queries"), so the Chrome-extension
     * endpoint is tried first. Text goes in the POST body (long rich text does not fit in a URL)
     * and HTML is sent as format=html so tags and attributes survive.
     *
     * @param string $text The Indonesian text to translate
     * @param string $from Source language (default 'id')
     * @param string $to Target language (default 'en')
     * @return string Translated text (the original text when every endpoint fails)
     */
    public static function translate($text, $from = 'id', $to = 'en') {
        if (empty(trim($text))) return $text;

        $isHtml = $text !== strip_tags($text);
        $lang = 'sl=' . urlencode($from) . '&tl=' . urlencode($to);
        $endpoints = [
            'https://clients5.google.com/translate_a/t?client=dict-chrome-ex&' . $lang . '&format=' . ($isHtml ? 'html' : 'text'),
            'https://translate.googleapis.com/translate_a/single?client=gtx&' . $lang . '&dt=t',
        ];

        $status = '';
        foreach ($endpoints as $url) {
            [$code, $response] = self::post($url, http_build_query(['q' => $text]));
            if ($code === 200 && ($translated = self::parse($response)) !== null) return $translated;
            $status .= ($status ? ', ' : '') . parse_url($url, PHP_URL_HOST) . ' ' . ($code ?: 'tidak ada respons');
        }

        error_log('[GoSirk] Terjemahan otomatis gagal (' . $status . '), teks EN memakai teks asli: ' . mb_substr($text, 0, 60));
        return self::fail($text); // Fallback to original
    }

    /** POST a form body; returns [http status (0 when unreachable), body] */
    private static function post($url, $body) {
        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $body,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CONNECTTIMEOUT => 5,
                CURLOPT_TIMEOUT => 20,
            ]);
            $response = curl_exec($ch);
            $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            return [$code, $response === false ? '' : $response];
        }

        $context = stream_context_create(['http' => [
            'method' => 'POST',
            'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
            'content' => $body,
            'timeout' => 20,
            'ignore_errors' => true,
        ]]);
        $response = @file_get_contents($url, false, $context);
        $code = preg_match('{HTTP/\S+ (\d{3})}', $http_response_header[0] ?? '', $m) ? (int) $m[1] : 0;
        return [$code, $response === false ? '' : $response];
    }

    /**
     * Translated text from either endpoint's JSON, null when it is not there:
     * dict-chrome-ex -> ["text"] (or [["text","id"]]), gtx -> [[["sentence", ...], ...], ...]
     */
    private static function parse($response) {
        $result = json_decode($response, true);
        if (!is_array($result) || !isset($result[0])) return null;

        if (is_string($result[0])) return $result[0];
        if (is_array($result[0]) && isset($result[0][0]) && is_string($result[0][0])) return $result[0][0];

        if (is_array($result[0])) {
            $translated = '';
            foreach ($result[0] as $sentence) {
                if (is_array($sentence) && isset($sentence[0]) && is_string($sentence[0])) $translated .= $sentence[0];
            }
            return $translated !== '' ? $translated : null;
        }
        return null;
    }
}
