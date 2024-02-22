<?php

declare(strict_types=1);

namespace Module\Hotel\Quotation\Application\Exception;

use Sdk\Shared\Exception\ApplicationException;

final class BookingQuotaException extends ApplicationException
{
    protected $code = self::BOOKING_NOT_FOUND_ROOM_DATE_QUOTA;
}
