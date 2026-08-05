<?php

namespace App\Enums;

use App\Concerns\HasEnumHelpers;

enum SessionPlayerStatus: string
{
    use HasEnumHelpers;

    case Playing = 'Playing';
    case Bench = 'Bench';
    case Injured = 'Injured';
    case Absent = 'Absent';
}
