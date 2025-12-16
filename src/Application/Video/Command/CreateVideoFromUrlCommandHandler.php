<?php

declare(strict_types=1);

namespace App\Application\Video\Command;

use App\Domain\Video\Entity\Video;
use App\Domain\Video\Repository\VideoRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CreateVideoFromUrlCommandHandler
{
    public function __construct(
        private readonly VideoRepository $videoRepository,
    ) {
    }

    public function __invoke(CreateVideoFromUrlCommand $command): Video
    {
        $video = Video::createFromUrl(
            url: $command->getUrl(),
        );

        $this->videoRepository->save($video, true);

        return $video;
    }
}
