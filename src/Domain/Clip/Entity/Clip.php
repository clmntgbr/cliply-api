<?php

declare(strict_types=1);

namespace App\Domain\Clip\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Domain\Clip\Enum\ClipStatus;
use App\Domain\Clip\Repository\ClipRepository;
use App\Domain\Video\Entity\Video;
use App\Presentation\Controller\Clip\CreateClipFromFileController;
use App\Presentation\Controller\Clip\CreateClipFromUrlController;
use App\Shared\Domain\Trait\UuidTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ClipRepository::class)]
#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/clips/url',
            controller: CreateClipFromUrlController::class,
        ),
        new Post(
            uriTemplate: '/clips/file',
            controller: CreateClipFromFileController::class,
        ),
        new Get(
            normalizationContext: ['groups' => ['clip:read', 'video:read']],
        ),
        new GetCollection(
            normalizationContext: ['groups' => ['clip:read', 'video:read']],
        ),
    ]
)]
class Clip
{
    use UuidTrait;
    use TimestampableEntity;

    #[ORM\OneToOne(targetEntity: Video::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    #[Groups(['clip:read'])]
    private Video $originalVideo;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(['clip:read'])]
    private ?string $thumbnail = null;

    #[ORM\Column(type: Types::STRING)]
    #[Groups(['clip:read'])]
    private string $status;

    #[ORM\Column(type: Types::JSON)]
    #[Groups(['clip:read'])]
    /**
     * @var list<string>
     */
    private array $statuses = [];

    public function __construct()
    {
        $this->id = Uuid::v7();
    }

    #[Groups(['clip:read'])]
    public function getId(): Uuid
    {
        return $this->id;
    }

    public static function createFromUrl(): self
    {
        return self::create();
    }

    public static function createFromFile(): self
    {
        return self::create();
    }

    public function getStatus(): ClipStatus
    {
        return ClipStatus::from($this->status);
    }

    public function setStatus(ClipStatus $status): self
    {
        $this->status = $status->value;

        $this->statuses[] = $status->value;

        return $this;
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

    /**
     * @return list<string>
     */
    public function getStatuses(): array
    {
        return $this->statuses;
    }

    private static function create(): self
    {
        $clip = new self();

        $clip->status = ClipStatus::DRAFT->value;
        $clip->statuses[] = ClipStatus::DRAFT->value;

        return $clip;
    }
}
