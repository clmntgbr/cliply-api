<?php

declare(strict_types=1);

namespace App\Application\Core\Command;

use App\Application\Core\Message\DownloadVideoMessage;
use App\Domain\Clip\Enum\ClipStatus;
use App\Domain\Clip\Repository\ClipRepository;
use App\Shared\Application\Bus\CoreBusInterface;
use App\Shared\Infrastructure\Workflow\WorkflowInterface;
use RuntimeException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;

use function sprintf;

#[AsMessageHandler]
class DownloadVideoCommandHandler
{
    public function __construct(
        private readonly CoreBusInterface $coreBus,
        private readonly ClipRepository $clipRepository,
        private readonly WorkflowInterface $workflow,
    ) {
    }

    public function __invoke(DownloadVideoCommand $command): void
    {
        $clip = $this->clipRepository->findByUuid($command->getClipId());

        if (null === $clip) {
            throw new UnrecoverableMessageHandlingException(sprintf('Clip "%s" not found', $command->getClipId()));
        }

        try {
            $this->workflow->apply($clip, 'downloading');

            $this->coreBus->dispatch(new DownloadVideoMessage(
                clipId: $clip->getId(),
                videoId: $clip->getOriginalVideo()->getId(),
                url: $clip->getOriginalVideo()->getUrl(),
            ));
        } catch (RuntimeException $e) {
            $clip->setStatus(ClipStatus::DOWNLOADING_FAILED);
            throw new UnrecoverableMessageHandlingException($e->getMessage());
        } finally {
            $this->clipRepository->save($clip);
        }
    }
}
