<?php

declare(strict_types=1);

namespace App\Domain\Clip\Enum;

enum ClipStatus: string
{
    case DRAFT = 'draft';
    case PROCESSING = 'processing';

    case DOWNLOADING = 'downloading';
    case DOWNLOADING_FAILED = 'downloading_failed';
    case DOWNLOADING_COMPLETED = 'downloading_completed';

    case EXTRACTING_SOUND = 'extracting_sound';
    case EXTRACTING_SOUND_FAILED = 'extracting_sound_failed';
    case EXTRACTING_SOUND_COMPLETED = 'extracting_sound_completed';

    case TRANSCRIBING_AUDIO = 'transcribing_audio';
    case TRANSCRIBING_AUDIO_FAILED = 'transcribing_audio_failed';
    case TRANSCRIBING_AUDIO_COMPLETED = 'transcribing_audio_completed';
}
