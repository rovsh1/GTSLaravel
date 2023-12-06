<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Exception;

use Sdk\Shared\Exception\ApplicationException;

final class OrderWithoutBookingsException extends ApplicationException
{
    protected $code = self::ORDER_WITHOUT_BOOKINGS;
}
