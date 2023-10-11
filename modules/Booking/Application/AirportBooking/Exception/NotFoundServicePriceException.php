<?php

declare(strict_types=1);

namespace Module\Booking\Application\AirportBooking\Exception;

use Module\Shared\Exception\ApplicationException;

class NotFoundServicePriceException extends ApplicationException
{
    public function __construct(
        ?\Throwable $previous = null,
        int $code = self::BOOKING_AIRPORT_SERVICE_PRICE_NOT_FOUND,
        ?string $message = 'Не заполнены цены на выбранную услугу в валюте клиента.',
    ) {
        parent::__construct($message, $code, $previous);
    }
}
