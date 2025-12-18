<?php

declare(strict_types=1);

namespace App\Application\Video\Command;

use App\Domain\Clip\Enum\VideoFormat;
use App\Shared\Application\Command\SynchronousInterface;

final class CreateVideoFromFileCommand implements SynchronousInterface
{
    public function __construct(
        public string $originalName,
        public string $fileName,
        public int $size,
        public VideoFormat $format,
    ) {
    }

    public function getOriginalName(): string
    {
        return $this->originalName;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function getFormat(): VideoFormat
    {
        return $this->format;
    }

    public function getSize(): int
    {
        return $this->size;
    }
}
