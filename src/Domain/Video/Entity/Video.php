<?php

declare(strict_types=1);

namespace App\Domain\Video\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Domain\Clip\Enum\VideoFormat;
use App\Domain\Video\Repository\VideoRepository;
use App\Shared\Domain\Trait\UuidTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: VideoRepository::class)]
#[ApiResource]
class Video
{
    use UuidTrait;
    use TimestampableEntity;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(type: Types::STRING)]
    private string $originalName;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $url = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $duration = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $size = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $format = null;

    public function __construct()
    {
        $this->id = Uuid::v7();
    }

    public static function createFromUrl(string $originalName, string $url): self
    {
        $video = new self();
        $video->originalName = $originalName;
        $video->url = $url;

        return $video;
    }

    public static function createFromFile(string $originalName, string $fileName, VideoFormat $format): self
    {
        $video = new self();
        $video->originalName = $originalName;
        $video->name = $fileName;
        $video->format = $format->value;

        return $video;
    }

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

    public function getFormat(): ?string
    {
        return $this->format;
    }

    public function setFormat(string $format): self
    {
        $this->format = $format;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
