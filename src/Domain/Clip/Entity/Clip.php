<?php

declare(strict_types=1);

namespace App\Domain\Clip\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Domain\Clip\Repository\ClipRepository;
use App\Domain\Video\Entity\Video;
use App\Shared\Domain\Trait\UuidTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: ClipRepository::class)]
#[ApiResource]
class Clip
{
    use UuidTrait;
    use TimestampableEntity;

    #[ORM\OneToOne(targetEntity: Video::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Video $originalVideo;

    public function getOriginalVideo(): Video
    {
        return $this->originalVideo;
    }

    public function setOriginalVideo(Video $originalVideo): self
    {
        $this->originalVideo = $originalVideo;

        return $this;
    }
}
