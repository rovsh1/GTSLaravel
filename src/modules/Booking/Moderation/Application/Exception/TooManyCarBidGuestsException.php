<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Exception;

use Sdk\Shared\Exception\ApplicationException;

final class TooManyCarBidGuestsException extends ApplicationException
{
    protected $code = self::BOOKING_CAR_BID_TOO_MANY_GUESTS;
}
