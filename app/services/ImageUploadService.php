<?php

class ImageUploadService
{
    public function upload(array $file, string $uploadDir, string $prefix = ''): string
    {
        if (empty($file['tmp_name']) || $file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Image not uploaded or error occurred.');
        }

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $imageName = $prefix . uniqid('_') . '.' . $extension;
        $targetPath = rtrim($uploadDir, '/') . '/' . $imageName;

        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            throw new Exception('Failed to move uploaded file.');
        }

        return $imageName; // just filename
    }
}

