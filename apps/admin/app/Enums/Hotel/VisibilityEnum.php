<?php

namespace App\Admin\Enums\Hotel;


use App\Admin\Enums\TranslatableEnumTrait;

enum VisibilityEnum: int
{
    use TranslatableEnumTrait;

    public const LANG_PREFIX = 'enum.hotel.visibility';

    case Hidden = 0;
    case Public = 1;
    case B2B = 2;
}
