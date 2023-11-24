<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Shared\ValueObject\CancelCondition;

/**
 * @see \Module\Hotel\Moderation\Domain\Hotel\ValueObject\MarkupSettings\CancelPeriodTypeEnum
 */
enum CancelFeePeriodTypeEnum: int
{
    case FIRST_DAY = 1;
    case FULL_PERIOD = 2;
}
