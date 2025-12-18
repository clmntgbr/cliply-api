<?php

declare(strict_types=1);

namespace App\Application\Core\RemoteEvent;

use App\Domain\Clip\Enum\ClipStatus;
use App\Domain\Clip\Repository\ClipRepository;
use App\Domain\Core\Dto\DownloadVideoFailure;
use Override;
use Psr\Log\LoggerInterface;
use Symfony\Component\RemoteEvent\Attribute\AsRemoteEventConsumer;
use Symfony\Component\RemoteEvent\Consumer\ConsumerInterface;
use Symfony\Component\RemoteEvent\RemoteEvent;

#[AsRemoteEventConsumer('downloadvideofailure')]
final readonly class DownloadVideoFailureWebhookConsumer implements ConsumerInterface
{
    public function __construct(
        private ClipRepository $clipRepository,
        private LoggerInterface $logger,
    ) {
    }

    #[Override]
    public function consume(RemoteEvent $event): void
    {
        /** @var DownloadVideoFailure $data */
        $data = $event->getPayload()['payload'];

        $clip = $this->clipRepository->findByUuid($data->getClipId());

        if (null === $clip) {
            $this->logger->error('Clip not found', [
                'clip_id' => $data->getClipId()->toString(),
            ]);

            return;
        }

        $clip->setStatus(ClipStatus::DOWNLOADING_FAILED);
        $this->clipRepository->save($clip, true);
    }
}
