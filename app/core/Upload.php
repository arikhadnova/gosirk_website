<?php

class Upload {
    // Reasons for uploads that were attempted but failed during this request
    private static $errors = [];

    public static function takeErrors() {
        $errors = self::$errors;
        self::$errors = [];
        return $errors;
    }

    private static function fail($fileName, $reason) {
        self::$errors[] = '"' . htmlspecialchars($fileName) . '" gagal diupload: ' . $reason;
        return false;
    }

    public static function maxSizeLabel() {
        return round(self::maxBytes('img') / 1024 / 1024, 1) . ' MB';
    }

    // Largest request body PHP accepts (all files + fields together)
    public static function maxPostBytes() {
        return self::iniBytes('post_max_size');
    }

    private static function iniBytes($key) {
        $ini = trim((string) ini_get($key));
        $units = ['k' => 1024, 'm' => 1048576, 'g' => 1073741824];
        $unit = strtolower(substr($ini, -1));
        return isset($units[$unit]) ? (int) $ini * $units[$unit] : (int) $ini;
    }

    // Effective limit = the smaller of our own limit and PHP's upload_max_filesize
    public static function maxBytes($kind = 'img') {
        $own = 10 * 1024 * 1024; // images are compressed after upload, so phone photos are fine
        $ini = trim(ini_get('upload_max_filesize'));
        $units = ['k' => 1024, 'm' => 1048576, 'g' => 1073741824];
        $unit = strtolower(substr($ini, -1));
        $php = isset($units[$unit]) ? (int) $ini * $units[$unit] : (int) $ini;
        return $php > 0 ? min($own, $php) : $own;
    }
    /**
     * Upload a file to a specific folder in public/assets
     * @param array $file The $_FILES['input_name'] array
     * @param string $folder The subfolder under assets (e.g., 'img/blog')
     * @param array $allowedExtensions List of allowed file extensions
     * @return string|false New filename on success, false on failure
     */
    public static function file($file, $folder, $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'webp'], $customBase = null, $optimize = true) {
        if (!isset($file) || !isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
            return false; // nothing was chosen: not an error
        }
        if ($file['error'] === UPLOAD_ERR_INI_SIZE || $file['error'] === UPLOAD_ERR_FORM_SIZE) {
            return self::fail($file['name'], 'ukuran file melebihi batas ' . self::maxSizeLabel() . '.');
        }
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return self::fail($file['name'], 'terjadi kesalahan saat upload (kode ' . $file['error'] . ').');
        }

        $fileName = $file['name'];
        $fileSize = $file['size'];
        $tmpName = $file['tmp_name'];

        $fileExtension = explode('.', $fileName);
        $fileExtension = strtolower(end($fileExtension));

        if (!in_array($fileExtension, $allowedExtensions)) {
            return self::fail($fileName, 'format .' . $fileExtension . ' tidak didukung (gunakan ' . implode(', ', $allowedExtensions) . ').');
        }

        // 10 MB for PDFs and images (images are compressed right after upload)
        $maxSize = 10 * 1024 * 1024;
        if ($fileSize > $maxSize) {
            return self::fail($fileName, 'ukuran file melebihi batas ' . round($maxSize / 1048576) . ' MB.');
        }

        $newFileName = uniqid('gs_', true) . '.' . $fileExtension;
        
        // Use an absolute path based on the location of this file.
        // This project serves assets from /assets; older code expected /public/assets.
        $defaultBase = realpath(__DIR__ . '/../../public/assets') ?: realpath(__DIR__ . '/../../assets');
        $basePath = ($customBase ? realpath($customBase) : $defaultBase) . DIRECTORY_SEPARATOR;
        $folderPath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $folder);
        $destination = $basePath . $folderPath . (empty($folderPath) ? '' : DIRECTORY_SEPARATOR) . $newFileName;
        
        // Ensure directory exists
        $targetDir = dirname($destination);
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        if (move_uploaded_file($tmpName, $destination)) {
            return $optimize ? self::optimizeImage($destination) : $newFileName;
        }

        return self::fail($fileName, 'file tidak dapat disimpan di server.');
    }

    // Photos straight from a phone/camera are often 3-8 MB. Uploaded JPG/PNG/WEBP images are
    // scaled down to at most MAX_SIDE px and saved as WebP when that is smaller.
    const MAX_SIDE = 1920;
    const WEBP_QUALITY = 82;

    // Largest side per folder, based on how big the images are shown on the site
    const MAX_SIDE_BY_FOLDER = [
        'partners' => 480, 'profile' => 480,                                      // logos, avatars
        'services' => 1280, 'gi' => 1280, 'portfolio' => 1280, 'blog' => 1280, 'publications' => 1280, // cards
    ];

    public static function maxSideFor($path) {
        $folder = basename(dirname($path));
        return self::MAX_SIDE_BY_FOLDER[$folder] ?? self::MAX_SIDE;
    }

    /** Optimise an uploaded image in place. Returns the (possibly new) file name; on any problem the original stays. */
    public static function optimizeImage($path) {
        $name = basename($path);
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true) || !function_exists('imagewebp')) return $name;

        $info = @getimagesize($path);
        if (!$info || $info[0] < 1 || $info[1] < 1) return $name;
        [$w, $h] = $info;

        // GD needs roughly 5 bytes per pixel: allow more memory for big photos, skip images that would still not fit
        $limit = self::iniBytes('memory_limit');
        if ($limit > 0 && $limit < 256 * 1048576 && $w * $h * 5 > $limit / 2 && @ini_set('memory_limit', '256M') !== false) {
            $limit = self::iniBytes('memory_limit');
        }
        if ($limit > 0 && $w * $h * 5 > $limit - memory_get_usage() - 16 * 1048576) return $name;

        try {
            $loaders = [IMAGETYPE_JPEG => 'imagecreatefromjpeg', IMAGETYPE_PNG => 'imagecreatefrompng', IMAGETYPE_WEBP => 'imagecreatefromwebp'];
            $img = isset($loaders[$info[2]]) && function_exists($loaders[$info[2]]) ? @$loaders[$info[2]]($path) : false;
            if (!$img) return $name;

            // Phone photos are often stored sideways with an EXIF rotation flag
            if ($info[2] === IMAGETYPE_JPEG && function_exists('exif_read_data')) {
                $orientation = @exif_read_data($path)['Orientation'] ?? 1;
                $angle = [3 => 180, 6 => -90, 8 => 90][$orientation] ?? 0;
                if ($angle) {
                    $rotated = imagerotate($img, $angle, 0);
                    if ($rotated) { imagedestroy($img); $img = $rotated; [$w, $h] = [imagesx($img), imagesy($img)]; }
                }
            }

            $scale = min(1, self::maxSideFor($path) / max($w, $h));
            if ($scale < 1) {
                $nw = max(1, (int) round($w * $scale));
                $nh = max(1, (int) round($h * $scale));
                $resized = imagecreatetruecolor($nw, $nh);
                imagealphablending($resized, false);
                imagesavealpha($resized, true);
                imagecopyresampled($resized, $img, 0, 0, 0, 0, $nw, $nh, $w, $h);
                imagedestroy($img);
                $img = $resized;
            } else {
                imagepalettetotruecolor($img);
                imagealphablending($img, false);
                imagesavealpha($img, true);
            }

            $webpPath = preg_replace('/\.[a-z]+$/i', '', $path) . '.webp';
            $tmp = $webpPath . '.tmp';
            $ok = imagewebp($img, $tmp, self::WEBP_QUALITY);
            imagedestroy($img);

            clearstatcache();
            if (!$ok || !is_file($tmp) || filesize($tmp) === 0 || filesize($tmp) >= filesize($path)) {
                @unlink($tmp); // WebP not smaller: keep the original upload
                return $name;
            }
            if ($webpPath !== $path) @unlink($path);
            rename($tmp, $webpPath);
            return basename($webpPath);
        } catch (Throwable $e) {
            error_log('[GoSirk] Optimasi gambar gagal (' . $name . '): ' . $e->getMessage());
            return $name;
        }
    }

    /**
     * Shrink an existing image in place: same file name and format (so nothing in the database changes).
     * Scales down to MAX_SIDE and re-encodes; the file is only replaced when it gets at least 10% smaller.
     * Returns the number of bytes saved (0 = left as it was).
     */
    public static function shrinkInPlace($path) {
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true) || !is_file($path)) return 0;
        $info = @getimagesize($path);
        if (!$info || $info[0] < 1) return 0;
        [$w, $h] = $info;
        $before = filesize($path);

        $limit = self::iniBytes('memory_limit');
        if ($limit > 0 && $limit < 256 * 1048576 && $w * $h * 5 > $limit / 2) @ini_set('memory_limit', '256M');
        $limit = self::iniBytes('memory_limit');
        if ($limit > 0 && $w * $h * 5 > $limit - memory_get_usage() - 16 * 1048576) return 0;

        $loaders = [IMAGETYPE_JPEG => 'imagecreatefromjpeg', IMAGETYPE_PNG => 'imagecreatefrompng', IMAGETYPE_WEBP => 'imagecreatefromwebp'];
        $img = isset($loaders[$info[2]]) && function_exists($loaders[$info[2]]) ? @$loaders[$info[2]]($path) : false;
        if (!$img) return 0;

        if ($info[2] === IMAGETYPE_JPEG && function_exists('exif_read_data')) {
            $angle = [3 => 180, 6 => -90, 8 => 90][@exif_read_data($path)['Orientation'] ?? 1] ?? 0;
            if ($angle && ($r = imagerotate($img, $angle, 0))) { imagedestroy($img); $img = $r; [$w, $h] = [imagesx($img), imagesy($img)]; }
        }
        $scale = min(1, self::maxSideFor($path) / max($w, $h));
        if ($scale < 1) {
            $nw = max(1, (int) round($w * $scale)); $nh = max(1, (int) round($h * $scale));
            $resized = imagecreatetruecolor($nw, $nh);
            imagealphablending($resized, false); imagesavealpha($resized, true);
            imagecopyresampled($resized, $img, 0, 0, 0, 0, $nw, $nh, $w, $h);
            imagedestroy($img); $img = $resized;
        } elseif ($info[2] === IMAGETYPE_PNG) {
            imagesavealpha($img, true);
        }

        $tmp = $path . '.tmp';
        if ($info[2] === IMAGETYPE_JPEG) { imageinterlace($img, true); $ok = imagejpeg($img, $tmp, 80); }
        elseif ($info[2] === IMAGETYPE_PNG) { $ok = imagepng($img, $tmp, 9); }
        else { $ok = imagewebp($img, $tmp, self::WEBP_QUALITY); }
        imagedestroy($img);

        clearstatcache();
        if (!$ok || !is_file($tmp) || filesize($tmp) === 0 || filesize($tmp) > $before * 0.9) { @unlink($tmp); return 0; }
        $saved = $before - filesize($tmp);
        rename($tmp, $path);
        return $saved;
    }

    /**
     * Delete a file from public/assets or custom base
     * @param string $fileName Filename to delete
     * @param string $folder Subfolder
     * @param string $customBase Optional custom base path
     */
    public static function delete($fileName, $folder, $customBase = null) {
        if (empty($fileName)) return;
        
        $defaultBase = realpath(__DIR__ . '/../../public/assets') ?: realpath(__DIR__ . '/../../assets');
        $basePath = ($customBase ? realpath($customBase) : $defaultBase) . DIRECTORY_SEPARATOR;
        $folderPath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $folder);
        $path = $basePath . $folderPath . (empty($folderPath) ? '' : DIRECTORY_SEPARATOR) . $fileName;
        
        if (file_exists($path) && is_file($path)) {
            unlink($path);
        }
    }
}
