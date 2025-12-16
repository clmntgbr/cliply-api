<?php

declare(strict_types=1);

namespace App\Domain\Clip\Enum;

enum ClipStatus: string
{
    case DRAFT = 'draft';
    case PROCESSING = 'processing';
    case DOWNLOADING = 'downloading';
}
