<?php

declare(strict_types=1);

namespace App\Application\Core\Message;

use App\Shared\Application\Command\AsynchronousCoreInterface;
use Override;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use Symfony\Component\Uid\Uuid;

readonly class ExtractSoundMessage implements AsynchronousCoreInterface
{
    public function __construct(
        private Uuid $clipId,
        private Uuid $videoId,
    ) {
    }

    public function getClipId(): Uuid
    {
        return $this->clipId;
    }

    public function getVideoId(): Uuid
    {
        return $this->videoId;
    }

    /**
     * @return array<string, mixed>
     */
    #[Override]
    public function jsonSerialize(): array
    {
        return [
            'clip_id' => (string) $this->clipId,
            'video_id' => (string) $this->videoId,
        ];
    }

    /**
     * @return AmqpStamp[]
     */
    #[Override]
    public function getStamps(): array
    {
        return [
            new AmqpStamp('core.extract_sound'),
        ];
    }

    #[Override]
    public function getWebhookUrlSuccess(): string
    {
        return 'webhook/extractsoundsuccess';
    }

    #[Override]
    public function getWebhookUrlFailure(): string
    {
        return 'webhook/extractsoundfailure';
    }
}
