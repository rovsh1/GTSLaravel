<?php

namespace App\Admin\Enums\Hotel;

enum StatusEnum: int
{
    public const LANG_PREFIX = 'enum.hotel.status';

    case Draft = 1;
    case Published = 2;
    case Blocked = 3;
    case Archive = 4;
}
