<?php

declare(strict_types=1);

namespace App\Application\Core\Command;

use App\Shared\Application\Command\AsynchronousInterface;
use Override;
use Symfony\Component\Uid\Uuid;

final readonly class DownloadVideoCommand implements AsynchronousInterface
{
    public function __construct(
        public Uuid $clipId,
    ) {
    }

    public function getClipId(): Uuid
    {
        return $this->clipId;
    }

    #[Override]
    public function getStamps(): array
    {
        return [];
    }
}
