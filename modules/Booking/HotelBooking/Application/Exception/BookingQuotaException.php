<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Application\Exception;

use Module\Shared\Application\Exception\ApplicationException;

class BookingQuotaException extends ApplicationException
{
    public function __construct(
        int $code,
        ?\Throwable $previous = null,
        ?string $message = 'Нет доступных квот на период бронирования.',
    ) {
        parent::__construct($message, $code, $previous);
    }
}
