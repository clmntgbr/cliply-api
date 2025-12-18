<?php

declare(strict_types=1);

namespace App\Domain\Core\Dto;

use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

readonly class DownloadVideoSuccess
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(max: 255)]
        #[Assert\Type('string')]
        private readonly string $name,
        #[SerializedName('original_file_name')]
        #[Assert\NotBlank]
        #[Assert\Length(max: 255)]
        #[Assert\Type('string')]
        private readonly string $originalFileName,
        #[Assert\NotBlank]
        #[Assert\Length(max: 255)]
        #[Assert\Type('string')]
        private readonly string $format,
        #[Assert\NotBlank]
        #[Assert\Positive]
        #[Assert\Type('integer')]
        private readonly int $size,
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

    public function getName(): string
    {
        return $this->name;
    }

    public function getFormat(): string
    {
        return $this->format;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function getOriginalFileName(): string
    {
        return $this->originalFileName;
    }
}
