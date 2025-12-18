<?php

declare(strict_types=1);

namespace App\Application\Core\RemoteEvent;

use App\Application\Core\Command\ExtractSoundCommand;
use App\Domain\Clip\Enum\ClipStatus;
use App\Domain\Clip\Repository\ClipRepository;
use App\Domain\Core\Dto\DownloadVideoSuccess;
use App\Shared\Application\Bus\CommandBusInterface;
use App\Shared\Infrastructure\Workflow\WorkflowInterface;
use Override;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\RemoteEvent\Attribute\AsRemoteEventConsumer;
use Symfony\Component\RemoteEvent\Consumer\ConsumerInterface;
use Symfony\Component\RemoteEvent\RemoteEvent;

#[AsRemoteEventConsumer('downloadvideosuccess')]
final readonly class DownloadVideoSuccessWebhookConsumer implements ConsumerInterface
{
    public function __construct(
        private ClipRepository $clipRepository,
        private LoggerInterface $logger,
        private WorkflowInterface $workflow,
        private CommandBusInterface $commandBus,
    ) {
    }

    #[Override]
    public function consume(RemoteEvent $event): void
    {
        /** @var DownloadVideoSuccess $data */
        $data = $event->getPayload()['payload'];

        $clip = $this->clipRepository->findByUuid($data->getClipId());

        if (null === $clip) {
            $this->logger->error('Clip not found', [
                'clip_id' => $data->getClipId()->toString(),
            ]);

            return;
        }

        if (null === $clip->getOriginalVideo()) {
            $this->logger->error('Clip has no video', [
                'clip_id' => $data->getClipId()->toString(),
            ]);

            return;
        }

        if (false === $clip->getOriginalVideo()->getId()->equals($data->getVideoId())) {
            $this->logger->error('Video ID mismatch', [
                'clip_id' => $data->getClipId()->toString(),
                'video_id' => $data->getVideoId()->toString(),
            ]);

            return;
        }

        $video = $clip->getOriginalVideo();

        $video->setSize($data->getSize());
        $video->setFormat($data->getFormat());
        $video->setName($data->getName());
        $video->setOriginalName($data->getOriginalFileName());

        try {
            $this->workflow->apply($clip, 'downloading_completed');
            $this->workflow->apply($clip, 'processing');
        } catch (RuntimeException $e) {
            $clip->setStatus(ClipStatus::DOWNLOADING_FAILED);
            throw new UnrecoverableMessageHandlingException($e->getMessage());
        } finally {
            $this->clipRepository->save($clip, true);
        }

        $this->commandBus->dispatch(new ExtractSoundCommand(
            clipId: $clip->getId(),
        ));
    }
}
