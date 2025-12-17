<?php

declare(strict_types=1);

namespace App\Domain\Clip\Dto;

use App\Domain\Clip\Enum\VideoFormat;
use Symfony\Component\Validator\Constraints as Assert;

class CreateClipFromFilePayload
{
    public function __construct(
        #[Assert\NotBlank(message: 'Original name is required')]
        #[Assert\Length(min: 3, max: 255, minMessage: 'Original name must be at least {{ limit }} characters', maxMessage: 'Original name cannot be longer than {{ limit }} characters')]
        public string $originalName,
        #[Assert\NotBlank(message: 'Format is required')]
        public VideoFormat $format,
    ) {
    }
}
