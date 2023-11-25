<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Exception;

use Sdk\Shared\Exception\ApplicationException;

final class NotFoundServicePriceException extends ApplicationException
{
    protected $code = self::BOOKING_SERVICE_PRICE_NOT_FOUND;
}
