<?php

declare(strict_types=1);

namespace App\Domain\Core\Dto;

use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

readonly class ExtractSoundSuccess
{
    /**
     * @param array<int, string> $audioFiles
     */
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
        #[SerializedName('duration')]
        #[Assert\NotBlank]
        #[Assert\Type('int')]
        private readonly int $duration,
        #[SerializedName('audio_files')]
        #[Assert\NotBlank]
        #[Assert\All([
            new Assert\NotBlank(),
            new Assert\Type('string'),
            new Assert\Length(max: 255),
        ])]
        private readonly array $audioFiles,
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

    public function getDuration(): int
    {
        return $this->duration;
    }

    /**
     * @return array<int, string>
     */
    public function getAudioFiles(): array
    {
        return $this->audioFiles;
    }
}
