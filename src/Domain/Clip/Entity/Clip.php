<?php

declare(strict_types=1);

namespace App\Domain\Clip\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Domain\Clip\Enum\ClipStatus;
use App\Domain\Clip\Repository\ClipRepository;
use App\Domain\Video\Entity\Video;
use App\Shared\Domain\Trait\UuidTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ClipRepository::class)]
#[ApiResource]
class Clip
{
    use UuidTrait;
    use TimestampableEntity;

    #[ORM\OneToOne(targetEntity: Video::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Video $originalVideo;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $thumbnail = null;

    #[ORM\Column(type: Types::STRING)]
    private string $status;

    public function __construct()
    {
        $this->id = Uuid::v7();
        $this->status = ClipStatus::DRAFT->value;
    }

    public static function createFromUrl(): self
    {
        return self::create(ClipStatus::DOWNLOADING);
    }

    public static function createFromFile(): self
    {
        return self::create(ClipStatus::PROCESSING);
    }

    public function getStatus(): ClipStatus
    {
        return ClipStatus::from($this->status);
    }

    public function getOriginalVideo(): Video
    {
        return $this->originalVideo;
    }

    public function setOriginalVideo(Video $originalVideo): self
    {
        $this->originalVideo = $originalVideo;

        return $this;
    }

    public function getThumbnail(): ?string
    {
        return $this->thumbnail;
    }

    public function setThumbnail(string $thumbnail): self
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    private static function create(ClipStatus $status): self
    {
        $clip = new self();
        $clip->status = $status->value;

        return $clip;
    }
}
