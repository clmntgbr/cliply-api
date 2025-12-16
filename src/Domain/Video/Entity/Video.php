<?php

declare(strict_types=1);

namespace App\Domain\Video\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Domain\Video\Repository\VideoRepository;
use App\Shared\Domain\Trait\UuidTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: VideoRepository::class)]
#[ApiResource]
class Video
{
    use UuidTrait;
    use TimestampableEntity;

    #[ORM\Column(type: Types::STRING)]
    private ?string $originalName = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $url = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $duration = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $size = null;

    public function getOriginalName(): ?string
    {
        return $this->originalName;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }
}
