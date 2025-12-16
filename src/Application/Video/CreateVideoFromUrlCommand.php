<?php

declare(strict_types=1);

namespace App\Application\Video\Command;

use App\Shared\Application\Command\SynchronousInterface;

final class CreateVideoFromUrlCommand implements SynchronousInterface
{
    public function __construct(
        public string $url,
    ) {
    }

    public function getUrl(): string
    {
        return $this->url;
    }
}
