<?php

class Translator {
    /**
     * Translates text from Indonesian to English using a free service.
     * 
     * @param string $text The Indonesian text to translate
     * @param string $from Source language (default 'id')
     * @param string $to Target language (default 'en')
     * @return string Translated text
     */
    public static function translate($text, $from = 'id', $to = 'en') {
        if (empty(trim($text))) return $text;
        
        // Remove HTML tags temporarily to avoid translation issues, or handle them carefully
        // For simplicity with mixed content, we'll try to keep them, but large blocks might fail.
        
        $url = "https://translate.googleapis.com/translate_a/single?client=gtx&sl=" . $from . "&tl=" . $to . "&dt=t&q=" . urlencode($text);
        
        try {
            $context = stream_context_create([
                "http" => [
                    "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36\r\n"
                ]
            ]);
            
            $response = @file_get_contents($url, false, $context);
            
            if ($response === false) {
                // Usually 429 (rate limited) from the free endpoint: the English version falls back to the original text
                $status = isset($http_response_header[0]) ? $http_response_header[0] : (error_get_last()['message'] ?? 'tidak ada respons');
                error_log('[GoSirk] Terjemahan otomatis gagal (' . $status . '), teks EN memakai teks asli: ' . mb_substr($text, 0, 60));
                return $text; // Fallback to original
            }
            
            $result = json_decode($response);
            
            if (isset($result[0])) {
                $translatedText = "";
                foreach ($result[0] as $sentence) {
                    $translatedText .= $sentence[0];
                }
                return $translatedText;
            }
            
            return $text;
        } catch (Exception $e) {
            return $text;
        }
    }
}
