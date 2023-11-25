<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Exception;

use Module\Shared\Exception\ApplicationException;

final class HotelDetailsExpectedException extends ApplicationException
{
    protected $code = self::BOOKING_HOTEL_DETAILS_EXPECTED;
}
