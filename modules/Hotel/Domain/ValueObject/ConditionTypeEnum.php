<?php

declare(strict_types=1);

namespace Module\Hotel\Domain\ValueObject;

enum ConditionTypeEnum: int
{
    case CheckIn = 1;
    case CheckOut = 2;
}
