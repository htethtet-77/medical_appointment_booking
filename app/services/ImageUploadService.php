<?php
// require_once __DIR__ . '/../interfaces/ImageUploadServiceInterface.php';
namespace Asus\Medical\Services;
use Asus\Medical\interfaces\ImageUploadServiceInterface;
use Exception;
class ImageUploadService implements ImageUploadServiceInterface
{
    // 
    public function upload(array $file, string $uploadDir, string $prefix = ''): string
{
    if (empty($file['tmp_name']) || $file['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Image not uploaded or error occurred.');
    }

    // ✅ Validate upload directory
    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true)) {
        throw new Exception('Failed to create upload directory.');
    }

    // ✅ Limit file size (e.g., 2MB max)
    $maxSize = 2 * 1024 * 1024; 
    if ($file['size'] > $maxSize) {
        throw new Exception('File is too large.');
    }

    // ✅ Only allow safe image extensions
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp','avif'];
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, $allowedExtensions)) {
        throw new Exception('Invalid file type.');
    }

    // ✅ Double-check MIME type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    $allowedMime = ['image/jpeg', 'image/png', 'image/gif', 'image/webp','image/avif'];
    if (!in_array($mimeType, $allowedMime)) {
        throw new Exception('Invalid MIME type.');
    }

    // ✅ Generate safe filename
    $imageName = $prefix . bin2hex(random_bytes(8)) . '.' . $extension;
    $targetPath = rtrim($uploadDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $imageName;

    // ✅ Move securely
    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        throw new Exception('Failed to move uploaded file.');
    }

    return $imageName; // Return only filename, not path
}

}

