<?php

namespace App\Enums;

use App\Concerns\HasEnumHelpers;

enum RotationMode: string
{
    use HasEnumHelpers;

    case FixedTeams = 'Fixed Teams';
    case WinnerStays = 'Winner Stays';
    case LoserStays = 'Loser Stays';
    case TimedRotation = 'Timed Rotation';
    case ManualRotation = 'Manual Rotation';
}
