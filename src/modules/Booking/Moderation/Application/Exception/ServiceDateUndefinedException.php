<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Exception;

use Module\Shared\Exception\ApplicationException;

final class ServiceDateUndefinedException extends ApplicationException
{
    protected $code = self::BOOKING_TRANSFER_SERVICE_DATE_UNDEFINED;
}
