<?php

declare(strict_types=1);

namespace App\Application\Clip\Command;

use App\Domain\Clip\Enum\VideoFormat;
use App\Shared\Application\Command\SynchronousInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Uid\Uuid;

final class CreateClipFromFileCommand implements SynchronousInterface
{
    public function __construct(
        public Uuid $userId,
        public UploadedFile $video,
        public UploadedFile $thumbnail,
        public VideoFormat $format,
        public string $originalName,
    ) {
    }

    public function getOriginalName(): string
    {
        return $this->originalName;
    }

    public function getFormat(): VideoFormat
    {
        return $this->format;
    }

    public function getVideo(): UploadedFile
    {
        return $this->video;
    }

    public function getThumbnail(): UploadedFile
    {
        return $this->thumbnail;
    }

    public function getUserId(): Uuid
    {
        return $this->userId;
    }
}
