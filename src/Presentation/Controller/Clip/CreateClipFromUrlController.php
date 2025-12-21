<?php

declare(strict_types=1);

namespace App\Presentation\Controller\Clip;

use App\Application\Clip\Command\CreateClipFromUrlCommand;
use App\Domain\Clip\Dto\CreateClipFromUrlPayload;
use App\Domain\User\Entity\User;
use App\Shared\Application\Bus\CommandBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[AsController]
class CreateClipFromUrlController extends AbstractController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
    ) {
    }

    public function __invoke(#[MapRequestPayload()] CreateClipFromUrlPayload $payload, #[CurrentUser] User $user): JsonResponse
    {
        $this->commandBus->dispatch(new CreateClipFromUrlCommand(
            userId: $user->getId(),
            originalName: $payload->getOriginalName(),
            url: $payload->getUrl(),
            thumbnail: $payload->getThumbnail(),
        ));

        return new JsonResponse([]);
    }
}
