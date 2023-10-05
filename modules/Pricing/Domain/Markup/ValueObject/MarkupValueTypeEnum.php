<?php

declare(strict_types=1);

namespace Module\Pricing\Domain\Markup\ValueObject;

enum MarkupValueTypeEnum: int
{
    case PERCENT = 1;
    case ABSOLUTE = 2;
}
