<?php

declare(strict_types=1);

namespace App\Domain\Core\Dto;

use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

readonly class TranscriptAudioSuccess
{
    public function __construct(
        #[SerializedName('clip_id')]
        #[Assert\NotBlank]
        #[Assert\Uuid]
        #[Assert\Length(max: 36)]
        private readonly Uuid $clipId,
        #[SerializedName('video_id')]
        #[Assert\NotBlank]
        #[Assert\Uuid]
        #[Assert\Length(max: 36)]
        private readonly Uuid $videoId,
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
}
