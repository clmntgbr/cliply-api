<?php

declare(strict_types=1);

namespace App\Application\Video\Command;

use App\Shared\Application\Command\SynchronousInterface;

final class CreateVideoFromUrlCommand implements SynchronousInterface
{
    public function __construct(
        public string $originalName,
        public string $url,
    ) {
    }

    public function getOriginalName(): string
    {
        return $this->originalName;
    }

    public function getUrl(): string
    {
        return $this->url;
    }
}
