<?php

declare(strict_types=1);

namespace App\Application\Core\RemoteEvent;

use App\Domain\Clip\Enum\ClipStatus;
use App\Domain\Clip\Repository\ClipRepository;
use App\Domain\Core\Dto\TranscriptAudioFailure;
use Override;
use Psr\Log\LoggerInterface;
use Symfony\Component\RemoteEvent\Attribute\AsRemoteEventConsumer;
use Symfony\Component\RemoteEvent\Consumer\ConsumerInterface;
use Symfony\Component\RemoteEvent\RemoteEvent;

#[AsRemoteEventConsumer('transcriptaudiofailure')]
final readonly class TranscriptAudioFailureWebhookConsumer implements ConsumerInterface
{
    public function __construct(
        private ClipRepository $clipRepository,
        private LoggerInterface $logger,
    ) {
    }

    #[Override]
    public function consume(RemoteEvent $event): void
    {
        /** @var TranscriptAudioFailure $data */
        $data = $event->getPayload()['payload'];

        $clip = $this->clipRepository->findByUuid($data->getClipId());

        if (null === $clip) {
            $this->logger->error('Clip not found', [
                'clip_id' => $data->getClipId()->toString(),
            ]);

            return;
        }

        $clip->setStatus(ClipStatus::TRANSCRIBING_AUDIO_FAILED);
        $this->clipRepository->save($clip);
    }
}
