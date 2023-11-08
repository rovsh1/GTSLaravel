<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Exception;

use Module\Shared\Exception\ApplicationException;

class ServiceDateUndefinedException extends ApplicationException
{
    public function __construct(
        ?\Throwable $previous = null,
        int $code = self::BOOKING_TRANSFER_SERVICE_DATE_UNDEFINED,
        ?string $message = 'Не заполнена дата или период бронирования услуги.',
    ) {
        parent::__construct($message, $code, $previous);
    }
}