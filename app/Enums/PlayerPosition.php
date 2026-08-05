<?php

namespace App\Enums;

use App\Concerns\HasEnumHelpers;

enum PlayerPosition: string
{
    use HasEnumHelpers;

    case GK = 'GK';
    case DEF = 'DEF';
    case MID = 'MID';
    case FWD = 'FWD';
}
