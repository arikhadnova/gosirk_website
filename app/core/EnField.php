<?php
// app/core/EnField.php
// English version of an Indonesian field in the admin forms. No extra box: the form gets one
// ID | EN toggle (added by layouts/admin_footer.php) and in EN mode every translatable field
// shows its English version in the same place.
// Usage in a view, right after the <input>/<textarea> named "<field>_id":
//     echo EnField::render('title', $item->title_id ?? '', $item->title_en ?? '');
// Saving is handled by Admin::en(): English changed by the admin is kept, empty English is
// translated automatically, and when Indonesian changes the English follows.

class EnField {
    /** $kind: 'text' (one line), 'textarea', or 'editor' (rich text, like the Indonesian field) */
    /** $idName: name of the Indonesian input when it is not "<field>_id" (e.g. office_hours) */
    public static function render($field, $idValue, $enValue, $kind = 'textarea', $rows = 3, $idName = null) {
        $f = htmlspecialchars($field, ENT_QUOTES);
        $en = htmlspecialchars((string) $enValue, ENT_QUOTES);
        $attrs = 'name="' . $f . '_en" data-en-for="' . $f . '"' . ($idName ? ' data-id-name="' . htmlspecialchars($idName, ENT_QUOTES) . '"' : '')
            . ' placeholder="English version (kosongkan untuk terjemahan otomatis)"';

        $input = $kind === 'text'
            ? '<input type="text" class="form-control en-input d-none" ' . $attrs . ' value="' . $en . '">'
            : '<textarea class="form-control en-input d-none' . ($kind === 'editor' ? ' en-editor' : '') . '" ' . $attrs . ' rows="' . (int) $rows . '">' . $en . '</textarea>';

        return '<input type="hidden" name="' . $f . '_id_was" value="' . htmlspecialchars((string) $idValue, ENT_QUOTES) . '">'
            . '<input type="hidden" name="' . $f . '_en_was" value="' . $en . '">'
            . $input;
    }

    /** true when the English text is missing or still the Indonesian text */
    public static function untranslated($idValue, $enValue) {
        $norm = fn($t) => trim(preg_replace('/\s+/u', ' ', html_entity_decode(strip_tags((string) $t), ENT_QUOTES, 'UTF-8')));
        return $norm($idValue) !== '' && ($norm($enValue) === '' || $norm($enValue) === $norm($idValue));
    }
}
