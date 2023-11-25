<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Exception;

use Module\Shared\Exception\ApplicationException;

final class OrderHasBookingInProgressException extends ApplicationException
{
    protected $code = self::ORDER_HAS_BOOKING_IN_PROGRESS;
}
