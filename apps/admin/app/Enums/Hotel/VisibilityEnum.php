<?php

namespace App\Admin\Enums\Hotel;

enum VisibilityEnum: int
{
    case HIDDEN = 0;
    case PUBLIC = 1;
    case B2B = 2;
}