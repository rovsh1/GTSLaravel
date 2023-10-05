<?php

declare(strict_types=1);

namespace Module\Shared\Enum\Pricing;

enum MarkupValueTypeEnum: int
{
    case PERCENT = 1;
    case ABSOLUTE = 2;
}
