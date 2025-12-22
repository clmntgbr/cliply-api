<?php

declare(strict_types=1);

namespace App\Application\Core\Command;

use App\Application\Core\Message\TranscriptAudioMessage;
use App\Domain\Clip\Enum\ClipStatus;
use App\Domain\Clip\Repository\ClipRepository;
use App\Shared\Application\Bus\CoreBusInterface;
use App\Shared\Infrastructure\Workflow\WorkflowInterface;
use RuntimeException;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;

use function sprintf;

#[AsMessageHandler]
class TranscriptAudioCommandHandler
{
    public function __construct(
        private readonly CoreBusInterface $coreBus,
        private readonly ClipRepository $clipRepository,
        private readonly WorkflowInterface $workflow,
        #[Autowire('%env(ACTIVATE_TRANSCRIBING_AUDIO)%')]
        private readonly bool $activateTranscribingAudio,
    ) {
    }

    public function __invoke(TranscriptAudioCommand $command): void
    {
        $clip = $this->clipRepository->findByUuid($command->getClipId());

        if (null === $clip) {
            throw new UnrecoverableMessageHandlingException(sprintf('Clip "%s" not found', $command->getClipId()));
        }

        try {
            $this->workflow->apply($clip, 'transcribing_audio');

            $this->coreBus->dispatch(new TranscriptAudioMessage(
                clip: $clip,
                activateTranscribingAudio: $this->activateTranscribingAudio,
            ));
        } catch (RuntimeException $e) {
            $clip->setStatus(ClipStatus::TRANSCRIBING_AUDIO_FAILED);
            throw new UnrecoverableMessageHandlingException($e->getMessage());
        } finally {
            $this->clipRepository->save($clip);
        }
    }
}
