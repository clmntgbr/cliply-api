<?php

declare(strict_types=1);

namespace App\Application\Video\Command;

use App\Domain\Video\Entity\Video;
use App\Domain\Video\Repository\VideoRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CreateVideoFromFileCommandHandler
{
    public function __construct(
        private readonly VideoRepository $videoRepository,
    ) {
    }

    public function __invoke(CreateVideoFromFileCommand $command): Video
    {
        $video = Video::createFromFile(
            originalName: $command->getOriginalName(),
            fileName: $command->getFileName(),
            format: $command->getFormat(),
            size: $command->getSize(),
        );

        $this->videoRepository->save($video, true);

        return $video;
    }
}
