<?php

declare(strict_types=1);

namespace App\Domain\Video\Repository;

use App\Domain\Video\Entity\Video;
use App\Shared\Domain\Repository\AbstractRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractRepository<Video>
 */
class VideoRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Video::class);
    }
}
