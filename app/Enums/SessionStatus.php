<?php

namespace App\Enums;

use App\Concerns\HasEnumHelpers;

enum SessionStatus: string
{
    use HasEnumHelpers;

    case Draft = 'Draft';
    case Live = 'Live';
    case Finished = 'Finished';
    case Cancelled = 'Cancelled';
}
