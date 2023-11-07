<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Shared\ValueObject\CancelCondition;

/**
 * @see \Module\Catalog\Domain\Hotel\ValueObject\MarkupSettings\CancelPeriodTypeEnum
 */
enum CancelPeriodTypeEnum: int
{
    case FIRST_NIGHT = 1;
    case FULL_PERIOD = 2;
}
