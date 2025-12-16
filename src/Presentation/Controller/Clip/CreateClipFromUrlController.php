<?php

declare(strict_types=1);

namespace App\Presentation\Controller\Clip;

use App\Application\Clip\Command\CreateClipFromUrlCommand;
use App\Domain\Clip\Dto\CreateClipFromUrlPayload;
use App\Shared\Application\Bus\CommandBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

#[AsController]
class CreateClipFromUrlController extends AbstractController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
    ) {
    }

    public function __invoke(#[MapRequestPayload()] CreateClipFromUrlPayload $payload): JsonResponse
    {
        $this->commandBus->dispatch(new CreateClipFromUrlCommand(
            originalName: $payload->getOriginalName(),
            url: $payload->getUrl(),
            thumbnail: $payload->getThumbnail(),
        ));

        return new JsonResponse([]);
    }
}
