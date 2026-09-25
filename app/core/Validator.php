<?php
// app/core/Validator.php

class Validator {
    /**
     * Validate $data against $rules.
     *   $rules  = ['field' => ['required', 'min:5', 'max:255', 'email', 'url', 'youtube', 'int', 'min_value:0', 'in:a,b,c', 'regex:/.../'], ...]
     *   $labels = ['field' => 'Human label'] (optional)
     * Returns ['field' => ['message', ...], ...]
     */
    public static function validate($data, $rules, $labels = []) {
        $errors = [];
        foreach ($rules as $field => $fieldRules) {
            $label = $labels[$field] ?? ucfirst(str_replace('_', ' ', $field));
            $raw = $data[$field] ?? '';
            $value = is_array($raw) ? '' : trim((string) $raw);
            // Rich-text editors send HTML, so measure the visible text
            $text = trim(html_entity_decode(strip_tags($value), ENT_QUOTES, 'UTF-8'));
            $empty = $text === '';

            foreach ($fieldRules as $rule) {
                [$name, $param] = array_pad(explode(':', $rule, 2), 2, null);

                if ($name === 'required') {
                    if ($empty) {
                        $errors[$field][] = "$label wajib diisi.";
                        break; // other rules are meaningless on an empty value
                    }
                    continue;
                }

                if ($empty) continue; // optional and empty: nothing else to check

                switch ($name) {
                    case 'min':
                        if (mb_strlen($text) < (int) $param) {
                            $errors[$field][] = "$label minimal $param karakter.";
                        }
                        break;
                    case 'max':
                        if (mb_strlen($value) > (int) $param) {
                            $errors[$field][] = "$label maksimal $param karakter.";
                        }
                        break;
                    case 'email':
                        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                            $errors[$field][] = "$label harus berupa alamat email yang valid.";
                        }
                        break;
                    case 'url':
                        if (!filter_var($value, FILTER_VALIDATE_URL) || !preg_match('#^https?://#i', $value)) {
                            $errors[$field][] = "$label harus berupa URL yang valid (diawali http:// atau https://).";
                        }
                        break;
                    case 'youtube':
                        if (!preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?|shorts)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $value)) {
                            $errors[$field][] = "$label harus berupa link video YouTube yang valid.";
                        }
                        break;
                    case 'int':
                        if (filter_var($value, FILTER_VALIDATE_INT) === false) {
                            $errors[$field][] = "$label harus berupa angka bulat.";
                        }
                        break;
                    case 'numeric':
                        if (!is_numeric($value)) {
                            $errors[$field][] = "$label harus berupa angka.";
                        }
                        break;
                    case 'min_value':
                        if (is_numeric($value) && $value < $param) {
                            $errors[$field][] = "$label tidak boleh kurang dari $param.";
                        }
                        break;
                    case 'in':
                        if (!in_array($value, explode(',', (string) $param), true)) {
                            $errors[$field][] = "$label tidak valid.";
                        }
                        break;
                    case 'regex':
                        if (!preg_match($param, $value)) {
                            $errors[$field][] = "Format $label tidak valid.";
                        }
                        break;
                }
            }
        }
        return $errors;
    }
}
