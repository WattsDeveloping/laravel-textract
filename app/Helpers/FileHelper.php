<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;

class FileHelper
{
    public static function storeFile(UploadedFile $file): false|string
    {
        return $file->store(path: 'uploads', options: 'public');
    }

    public static function removeFile(string $filePath): void
    {
        $fullPath = storage_path(path: "app/public/$filePath");
        if (file_exists(filename: $fullPath)) {
            unlink(filename: $fullPath);
        }
    }
}
