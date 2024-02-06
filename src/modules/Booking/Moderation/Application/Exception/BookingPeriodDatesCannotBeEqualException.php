<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Exception;

use Sdk\Shared\Exception\ApplicationException;

final class BookingPeriodDatesCannotBeEqualException extends ApplicationException
{
    protected $code = self::BOOKING_PERIOD_DATES_CANNOT_BE_EQUAL;
}
