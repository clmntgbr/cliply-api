<?php

declare(strict_types=1);

namespace App\Application\Core\RemoteEvent;

use App\Domain\Clip\Repository\ClipRepository;
use App\Domain\Core\Dto\TranscriptAudioSuccess;
use Override;
use Psr\Log\LoggerInterface;
use Symfony\Component\RemoteEvent\Attribute\AsRemoteEventConsumer;
use Symfony\Component\RemoteEvent\Consumer\ConsumerInterface;
use Symfony\Component\RemoteEvent\RemoteEvent;

#[AsRemoteEventConsumer('transcriptaudiosuccess')]
final readonly class TranscriptAudioSuccessWebhookConsumer implements ConsumerInterface
{
    public function __construct(
        private ClipRepository $clipRepository,
        private LoggerInterface $logger,
    ) {
    }

    #[Override]
    public function consume(RemoteEvent $event): void
    {
        /** @var TranscriptAudioSuccess $data */
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

        $clip->getOriginalVideo();
    }
}
