<?php

declare(strict_types=1);

namespace App\Application\Storage\Command;

use App\Shared\Application\Command\SynchronousInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Uid\Uuid;

final class UploadVideoCommand implements SynchronousInterface
{
    public function __construct(
        public Uuid $clipId,
        public UploadedFile $video,
    ) {
    }

    public function getClipId(): Uuid
    {
        return $this->clipId;
    }

    public function getVideo(): UploadedFile
    {
        return $this->video;
    }
}
