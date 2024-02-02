<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Exception;

use Sdk\Shared\Exception\ApplicationException;

final class OrderHasNotCancelledBookingException extends ApplicationException
{
    protected $code = self::ORDER_HAS_NOT_CANCELLED_BOOKING;
}
