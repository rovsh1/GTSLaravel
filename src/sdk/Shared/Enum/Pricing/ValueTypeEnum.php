<?php

declare(strict_types=1);

namespace Sdk\Shared\Enum\Pricing;

enum ValueTypeEnum: int
{
    case PERCENT = 1;
    case ABSOLUTE = 2;
}
