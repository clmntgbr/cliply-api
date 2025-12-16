<?php

declare(strict_types=1);

namespace App\Application\Storage\Command;

use App\Infrastructure\Storage\S3\S3StorageServiceInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class UploadVideoCommandHandler
{
    public function __construct(
        private readonly S3StorageServiceInterface $s3StorageService,
    ) {
    }

    public function __invoke(UploadVideoCommand $command): string
    {
        return $this->s3StorageService->upload(
            $command->getClipId(),
            $command->getVideo(),
        );
    }
}
