<?php

declare(strict_types=1);

namespace App\Infrastructure\Storage\S3;

use League\Flysystem\FilesystemOperator;
use Override;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Uid\Uuid;

use function is_resource;
use function Safe\fclose;
use function Safe\fopen;
use function Safe\mkdir;
use function Safe\stream_copy_to_stream;

class S3StorageService implements S3StorageServiceInterface
{
    public function __construct(
        private readonly FilesystemOperator $awsStorage,
    ) {
    }

    #[Override]
    public function deleteAll(Uuid $uuid): void
    {
        $streamPath = $uuid->toRfc4122();

        $files = $this->awsStorage->listContents($streamPath, true);

        foreach ($files as $file) {
            if ('file' === $file['type']) {
                $this->awsStorage->delete($file['path']);
            }
        }
    }

    #[Override]
    public function delete(Uuid $uuid, string $fileName): void
    {
        $this->awsStorage->delete($uuid . '/' . $fileName);
    }

    #[Override]
    public function download(Uuid $uuid, string $fileName): string
    {
        $tmpDir = sys_get_temp_dir() . '/' . $uuid->toRfc4122();
        if (! is_dir($tmpDir)) {
            mkdir($tmpDir, 0777, true);
        }

        $tmpFilePath = $tmpDir . '/' . basename($fileName);

        $stream = null;
        $tmpFile = null;

        try {
            $stream = $this->awsStorage->readStream($uuid . '/' . $fileName);
            $tmpFile = fopen($tmpFilePath, 'w');
            if (false === $tmpFile) {
                return $tmpFilePath;
            }

            if (is_resource($stream)) {
                stream_copy_to_stream($stream, $tmpFile);
            }

            return $tmpFilePath;
        } finally {
            if (is_resource($stream)) {
                fclose($stream);
            }

            if (is_resource($tmpFile)) {
                fclose($tmpFile);
            }
        }
    }

    #[Override]
    public function upload(Uuid $path, UploadedFile $file): string
    {
        $fileName = Uuid::v4() . '.' . $file->guessExtension();
        $path = $path . '/' . $fileName;

        $handle = null;

        try {
            $handle = fopen($file->getPathname(), 'r');

            $this->awsStorage->writeStream($path, $handle, [
                'visibility' => 'public',
                'mimetype' => $file->getMimeType(),
            ]);

            return $fileName;
        } finally {
            if (is_resource($handle)) {
                fclose($handle);
            }
        }
    }
}
