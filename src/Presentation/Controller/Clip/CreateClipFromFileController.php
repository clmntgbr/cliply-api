<?php

declare(strict_types=1);

namespace App\Presentation\Controller\Clip;

use App\Application\Clip\Command\CreateClipFromFileCommand;
use App\Domain\Clip\Dto\CreateClipFromFilePayload;
use App\Domain\User\Entity\User;
use App\Shared\Application\Bus\CommandBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Attribute\MapUploadedFile;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[AsController]
class CreateClipFromFileController extends AbstractController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
    ) {
    }

    public function __invoke(
        #[MapUploadedFile]
        UploadedFile $file,
        #[MapUploadedFile]
        UploadedFile $thumbnail,
        #[MapRequestPayload()]
        CreateClipFromFilePayload $payload,
        #[CurrentUser]
        User $user,
    ): JsonResponse {
        $this->commandBus->dispatch(new CreateClipFromFileCommand(
            userId: $user->getId(),
            video: $file,
            thumbnail: $thumbnail,
            format: $payload->format,
            originalName: $payload->originalName,
        ));

        return new JsonResponse([]);
    }
}
