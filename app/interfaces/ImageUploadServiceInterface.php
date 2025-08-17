<?php
namespace Asus\Medical\interfaces;
interface ImageUploadServiceInterface{
    public function upload(array $file, string $uploadDir, string $prefix = ''): string;

}
?>