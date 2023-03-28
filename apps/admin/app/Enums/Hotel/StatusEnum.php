<?php

namespace App\Admin\Enums\Hotel;

use App\Admin\Enums\TranslatableEnumTrait;

enum StatusEnum: int
{
    use TranslatableEnumTrait;

    public const LANG_PREFIX = 'enum.hotel.status';

    case Draft = 1;
    case Published = 2;
    case Blocked = 3;
    case Archive = 4;
}
