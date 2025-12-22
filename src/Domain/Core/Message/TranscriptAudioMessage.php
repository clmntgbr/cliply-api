<?php

declare(strict_types=1);

namespace App\Application\Core\Message;

use App\Domain\Clip\Entity\Clip;
use App\Shared\Application\Command\AsynchronousCoreInterface;
use Override;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;

readonly class TranscriptAudioMessage implements AsynchronousCoreInterface
{
    public function __construct(
        private Clip $clip,
        private bool $activateTranscribingAudio,
    ) {
    }

    public function getClip(): Clip
    {
        return $this->clip;
    }

    /**
     * @return array<string, mixed>
     */
    #[Override]
    public function jsonSerialize(): array
    {
        return [
            'clip_id' => (string) $this->clip->getId(),
            'video_id' => (string) $this->clip->getOriginalVideo()->getId(),
            'audio_files' => $this->clip->getOriginalVideo()->getAudioFiles(),
            'language' => 'fr',
            'activate_transcribing_audio' => $this->activateTranscribingAudio,
        ];
    }

    /**
     * @return AmqpStamp[]
     */
    #[Override]
    public function getStamps(): array
    {
        return [
            new AmqpStamp('core.transcript_audio'),
        ];
    }

    #[Override]
    public function getWebhookUrlSuccess(): string
    {
        return 'webhook/transcriptaudiosuccess';
    }

    #[Override]
    public function getWebhookUrlFailure(): string
    {
        return 'webhook/transcriptaudiofailure';
    }
}
