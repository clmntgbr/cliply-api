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
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: VideoRepository::class)]
#[ApiResource]
class Video
{
    use UuidTrait;
    use TimestampableEntity;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(['video:read'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::STRING)]
    #[Groups(['video:read'])]
    private string $originalName;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['video:read'])]
    private ?string $url = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    #[Groups(['video:read'])]
    private ?int $duration = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    #[Groups(['video:read'])]
    private ?int $size = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(['video:read'])]
    private ?string $format = null;

    /**
     * @var array<int, string>
     */
    #[ORM\Column(type: Types::JSON, nullable: true)]
    #[Groups(['video:read'])]
    private array $audioFiles = [];

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

    public static function createFromFile(string $originalName, string $fileName, VideoFormat $format, int $size): self
    {
        $video = new self();
        $video->originalName = $originalName;
        $video->name = $fileName;
        $video->format = $format->value;
        $video->size = $size;

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

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function setSize(int $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function setOriginalName(string $originalName): self
    {
        $this->originalName = $originalName;

        return $this;
    }

    /**
     * @return array<int, string>
     */
    public function getAudioFiles(): array
    {
        return $this->audioFiles;
    }

    /**
     * @param array<int, string> $audioFiles
     */
    public function setAudioFiles(array $audioFiles): self
    {
        $this->audioFiles = $audioFiles;

        return $this;
    }
}
