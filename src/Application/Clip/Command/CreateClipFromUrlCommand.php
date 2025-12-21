<?php

declare(strict_types=1);

namespace App\Application\Clip\Command;

use App\Shared\Application\Command\SynchronousInterface;
use Symfony\Component\Uid\Uuid;

final class CreateClipFromUrlCommand implements SynchronousInterface
{
    public function __construct(
        public Uuid $userId,
        public string $originalName,
        public string $url,
        public string $thumbnail,
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

    public function getThumbnail(): string
    {
        return $this->thumbnail;
    }

    public function getUserId(): Uuid
    {
        return $this->userId;
    }
}
