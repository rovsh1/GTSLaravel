<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Domain\ValueObject\Details\CancelCondition;

/**
 * @see \Module\Hotel\Domain\ValueObject\MarkupSettings\CancelPeriodTypeEnum
 */
enum CancelPeriodTypeEnum: int
{
    case FIRST_NIGHT = 1;
    case FULL_PERIOD = 2;
}
