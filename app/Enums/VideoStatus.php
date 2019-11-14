<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class VideoStatus extends Enum
{
    const Queued = "Queued";
    const Playing = "Playing";
    const Played = "Played";
}
