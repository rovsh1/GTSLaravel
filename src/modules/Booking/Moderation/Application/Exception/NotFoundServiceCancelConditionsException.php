<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Exception;

use Sdk\Shared\Exception\ApplicationException;

final class NotFoundServiceCancelConditionsException extends ApplicationException
{
    protected $code = self::BOOKING_NOT_FOUND_SERVICE_CANCEL_CONDITIONS;
}
