<?php

namespace App\Helpers;

class FileHelper
{
    public static function storeFile($file)
    {
        return $file->store('uploads', 'public');
    }

    public static function removeFile($filePath): void
    {
        $fullPath = storage_path("app/public/$filePath");
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }
}
