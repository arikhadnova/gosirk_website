<?php

class Flasher {
    public static function setFlash($pesan, $aksi = '', $tipe = '', $errors = []) {
        // Surface uploads that failed during this request
        $uploadErrors = class_exists('Upload') ? Upload::takeErrors() : [];
        if ($uploadErrors) {
            $errors['upload'] = array_merge($errors['upload'] ?? [], $uploadErrors);
            if ($tipe === 'success') {
                $tipe = 'warning';
                $aksi .= ', tetapi ada file yang gagal diupload';
            }
        }
        $_SESSION['flash'] = [
            'pesan' => $pesan,
            'aksi' => $aksi,
            'tipe' => $tipe,
            'errors' => $errors
        ];
    }

    // Remember submitted values (except passwords) so a form can be refilled after a failed save
    public static function keepOldInput($data) {
        $keep = [];
        foreach ((array) $data as $k => $v) {
            if (stripos($k, 'password') !== false || !is_scalar($v)) continue;
            $keep[$k] = (string) $v;
        }
        $_SESSION['old_input'] = $keep;
    }

    public static function takeOldInput() {
        $old = $_SESSION['old_input'] ?? [];
        unset($_SESSION['old_input']);
        return $old;
    }

    public static function flash() {
        if (isset($_SESSION['flash'])) {
            $f = $_SESSION['flash'];
            $pesan = $f['pesan'];
            $aksi = $f['aksi'];
            $tipe = $f['tipe']; // success, danger, warning, info
            
            // Map Bootstrap types to SweetAlert2 icon types
            $icon = $tipe;
            if($tipe == 'danger') $icon = 'error';
            if($tipe == 'info') $icon = 'info';

            $htmlContent = '<strong>' . $pesan . '</strong> ' . $aksi;
            
            if (!empty($f['errors'])) {
                $htmlContent .= '<ul style="text-align: left; margin-top: 10px; font-size: 14px;">';
                foreach ($f['errors'] as $field => $errList) {
                    foreach ($errList as $e) {
                        $htmlContent .= '<li>' . $e . '</li>';
                    }
                }
                $htmlContent .= '</ul>';
            }

            $safeHtml = addslashes($htmlContent);
            // Keep error/warning popups open until dismissed so the list can be read
            $timer = empty($f['errors']) ? 5000 : 'undefined';
            $safeTitle = addslashes($tipe == 'success' ? 'Berhasil!' : ($tipe == 'warning' ? 'Perhatian' : 'Oops...'));

            echo "
            <script>
                Swal.fire({
                    icon: '{$icon}',
                    title: '{$safeTitle}',
                    html: '{$safeHtml}',
                    confirmButtonColor: '#0D4A7C',
                    timer: {$timer},
                    timerProgressBar: true
                });
            </script>";
            
            unset($_SESSION['flash']);
        }
    }
}
