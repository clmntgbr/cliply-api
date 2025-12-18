<?php

declare(strict_types=1);

namespace App\Application\Clip\Command;

use App\Application\Core\Command\ExtractSoundCommand;
use App\Application\Storage\Command\UploadThumbnailCommand;
use App\Application\Storage\Command\UploadVideoCommand;
use App\Application\Video\Command\CreateVideoFromFileCommand;
use App\Domain\Clip\Entity\Clip;
use App\Domain\Clip\Repository\ClipRepository;
use App\Domain\Video\Entity\Video;
use App\Shared\Application\Bus\CommandBusInterface;
use App\Shared\Infrastructure\Workflow\WorkflowInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CreateClipFromFileCommandHandler
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
        private readonly ClipRepository $clipRepository,
        private readonly WorkflowInterface $workflow,
    ) {
    }

    public function __invoke(CreateClipFromFileCommand $command): Clip
    {
        $clip = Clip::createFromFile();

        $videoFileName = $this->commandBus->dispatch(new UploadVideoCommand(
            clipId: $clip->getId(),
            video: $command->getVideo(),
        ));

        $thumbnailFileName = $this->commandBus->dispatch(new UploadThumbnailCommand(
            clipId: $clip->getId(),
            thumbnail: $command->getThumbnail(),
        ));

        /** @var Video $video */
        $video = $this->commandBus->dispatch(new CreateVideoFromFileCommand(
            originalName: $command->getOriginalName(),
            fileName: $videoFileName,
            size: $command->getVideo()->getSize(),
            format: $command->getFormat(),
        ));

        $clip->setOriginalVideo($video);
        $clip->setThumbnail($thumbnailFileName);

        $this->workflow->apply($clip, 'processing_no_download');

        $this->clipRepository->save($clip, true);

        $this->commandBus->dispatch(new ExtractSoundCommand(
            clipId: $clip->getId(),
        ));

        return $clip;
    }
}
