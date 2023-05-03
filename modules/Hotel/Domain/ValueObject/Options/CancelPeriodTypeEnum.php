<?php

declare(strict_types=1);

namespace Module\Hotel\Domain\ValueObject\Options;

enum CancelPeriodTypeEnum: int
{
    case FIRST_NIGHT = 1;
    case FULL_PERIOD = 2;
}
