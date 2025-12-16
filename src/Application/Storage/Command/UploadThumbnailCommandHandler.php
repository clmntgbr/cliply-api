<?php

declare(strict_types=1);

namespace App\Application\Storage\Command;

use App\Infrastructure\Storage\S3\S3StorageServiceInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class UploadThumbnailCommandHandler
{
    public function __construct(
        private readonly S3StorageServiceInterface $s3StorageService,
    ) {
    }

    public function __invoke(UploadThumbnailCommand $command): string
    {
        /* @var Video $video */
        return $this->s3StorageService->upload(
            $command->getClipId(),
            $command->getThumbnail(),
        );
    }
}
