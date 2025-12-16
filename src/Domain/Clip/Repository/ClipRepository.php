<?php

declare(strict_types=1);

namespace App\Domain\Clip\Repository;

use App\Domain\Clip\Entity\Clip;
use App\Shared\Domain\Repository\AbstractRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractRepository<Clip>
 */
class ClipRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Clip::class);
    }
}
