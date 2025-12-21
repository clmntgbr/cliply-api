<?php

declare(strict_types=1);

namespace App\Application\Clip\Command;

use App\Application\Core\Command\ExtractSoundCommand;
use App\Application\Storage\Command\UploadThumbnailCommand;
use App\Application\Storage\Command\UploadVideoCommand;
use App\Application\Video\Command\CreateVideoFromFileCommand;
use App\Domain\Clip\Entity\Clip;
use App\Domain\Clip\Repository\ClipRepository;
use App\Domain\User\Repository\UserRepository;
use App\Domain\Video\Entity\Video;
use App\Shared\Application\Bus\CommandBusInterface;
use App\Shared\Infrastructure\Workflow\WorkflowInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;

#[AsMessageHandler]
class CreateClipFromFileCommandHandler
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
        private readonly ClipRepository $clipRepository,
        private readonly WorkflowInterface $workflow,
        private readonly UserRepository $userRepository,
    ) {
    }

    public function __invoke(CreateClipFromFileCommand $command): Clip
    {
        $user = $this->userRepository->findByUuid($command->getUserId());
        if (null === $user) {
            throw new UnrecoverableMessageHandlingException('User not found');
        }

        $clip = Clip::createFromFile($user);

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
