<?php

declare(strict_types=1);

namespace App\Shared\Utils;

use InvalidArgumentException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use function in_array;

class ConvertBase64ToFile
{
    public static function convertBase64ToFile(string $base64Data): UploadedFile
    {
        if (in_array(preg_match('/^data:image\/(jpeg|jpg|png|gif|webp);base64,(.+)$/', $base64Data, $matches), [0, false], true)) {
            throw new InvalidArgumentException('Invalid file format');
        }

        $mimeType = 'image/' . $matches[1];
        $base64Content = $matches[2];

        $imageData = base64_decode($base64Content, true);
        $tempFile = tempnam(sys_get_temp_dir(), 'file_');
        file_put_contents($tempFile, $imageData);

        $extension = match ($mimeType) {
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
            default => 'jpg',
        };

        return new UploadedFile(
            path: $tempFile,
            originalName: 'file.' . $extension,
            mimeType: $mimeType,
        );
    }
}
