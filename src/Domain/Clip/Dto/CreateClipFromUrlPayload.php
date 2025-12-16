<?php

declare(strict_types=1);

namespace App\Domain\Clip\Dto;

use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

class CreateClipFromUrlPayload
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(max: 255)]
        #[Assert\Type('string')]
        private readonly string $originalName,
        #[Assert\NotBlank]
        #[Assert\Url]
        #[Assert\Length(max: 2048)]
        #[Assert\Regex(pattern: '/^https?:\/\/.+$/i', message: 'Invalid URL')]
        private readonly string $url,
        #[SerializedName('thumbnail')]
        #[Assert\NotBlank]
        #[Assert\Type('string')]
        #[Assert\Regex(pattern: '/^data:image\/(jpeg|jpg|png|gif|webp);base64,/', message: 'Invalid base64 image format')]
        private readonly string $thumbnail,
    ) {
    }

    public function getOriginalName(): string
    {
        return $this->originalName;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getThumbnail(): string
    {
        return $this->thumbnail;
    }
}
