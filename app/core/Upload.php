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
        $own = $kind === 'pdf' ? 10 * 1024 * 1024 : 5 * 1024 * 1024;
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
    public static function file($file, $folder, $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'webp'], $customBase = null) {
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

        // 2MB limit for images, 10MB for PDF
        $maxSize = ($fileExtension === 'pdf') ? 10 * 1024 * 1024 : 5 * 1024 * 1024;
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
            return $newFileName;
        }

        return self::fail($fileName, 'file tidak dapat disimpan di server.');
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
