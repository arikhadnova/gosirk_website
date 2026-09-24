<?php

class Upload {
    /**
     * Upload a file to a specific folder in public/assets
     * @param array $file The $_FILES['input_name'] array
     * @param string $folder The subfolder under assets (e.g., 'img/blog')
     * @param array $allowedExtensions List of allowed file extensions
     * @return string|false New filename on success, false on failure
     */
    public static function file($file, $folder, $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'webp'], $customBase = null) {
        if (!isset($file) || $file['error'] !== 0) {
            return false;
        }

        $fileName = $file['name'];
        $fileSize = $file['size'];
        $tmpName = $file['tmp_name'];

        $fileExtension = explode('.', $fileName);
        $fileExtension = strtolower(end($fileExtension));

        if (!in_array($fileExtension, $allowedExtensions)) {
            return false;
        }

        // 2MB limit for images, 10MB for PDF
        $maxSize = ($fileExtension === 'pdf') ? 10 * 1024 * 1024 : 5 * 1024 * 1024;
        if ($fileSize > $maxSize) {
            return false;
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

        return false;
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
