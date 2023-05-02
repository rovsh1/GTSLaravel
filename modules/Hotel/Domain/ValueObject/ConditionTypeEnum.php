<?php

declare(strict_types=1);

namespace Module\Hotel\Domain\ValueObject;

enum ConditionTypeEnum: int
{
    case CHECK_IN = 1;
    case CHECK_OUT = 2;
}
