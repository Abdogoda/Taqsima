<?php

namespace App\Enums;

use App\Concerns\HasEnumHelpers;

enum TeamGenerationMode: string
{
    use HasEnumHelpers;

    case Manual = 'Manual';
    case Automatic = 'Automatic';
    case Template = 'Template';
}
