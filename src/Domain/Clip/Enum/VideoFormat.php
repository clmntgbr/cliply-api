<?php

declare(strict_types=1);

namespace App\Domain\Clip\Enum;

enum VideoFormat: string
{
    /**
     * @return string[]
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    case MP4 = 'mp4';
}
