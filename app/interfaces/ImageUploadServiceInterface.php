<?php
interface ImageUploadServiceInterface{
    public function upload(array $file, string $uploadDir, string $prefix = ''): string;

}
?>