<?php

declare(strict_types=1);

namespace App\Application\Clip\Command;

use App\Application\Core\Command\DownloadVideoCommand;
use App\Application\Storage\Command\UploadThumbnailCommand;
use App\Application\Video\Command\CreateVideoFromUrlCommand;
use App\Domain\Clip\Entity\Clip;
use App\Domain\Clip\Repository\ClipRepository;
use App\Domain\Video\Entity\Video;
use App\Shared\Application\Bus\CommandBusInterface;
use App\Shared\Utils\ConvertBase64ToFile;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CreateClipFromUrlCommandHandler
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
        private readonly ClipRepository $clipRepository,
    ) {
    }

    public function __invoke(CreateClipFromUrlCommand $command): Clip
    {
        $clip = Clip::createFromUrl();

        $thumbnailFileName = $this->commandBus->dispatch(new UploadThumbnailCommand(
            clipId: $clip->getId(),
            thumbnail: ConvertBase64ToFile::convertBase64ToFile($command->getThumbnail()),
        ));

        /** @var Video $video */
        $video = $this->commandBus->dispatch(new CreateVideoFromUrlCommand(
            originalName: $command->getOriginalName(),
            url: $command->getUrl(),
        ));

        $clip->setOriginalVideo($video);
        $clip->setThumbnail($thumbnailFileName);

        $this->clipRepository->save($clip, true);

        $this->commandBus->dispatch(new DownloadVideoCommand(
            clipId: $clip->getId(),
        ));

        return $clip;
    }
}
