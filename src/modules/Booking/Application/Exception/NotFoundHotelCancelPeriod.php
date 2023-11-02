<?php

declare(strict_types=1);

namespace Module\Booking\Application\Exception;

use Module\Shared\Exception\ApplicationException;

class NotFoundHotelCancelPeriod extends ApplicationException
{
    public function __construct(
        ?\Throwable $previous = null,
        ?string $message = 'У отеля не заполнены условия отмены на период брони.',
        int $code = self::BOOKING_NOT_FOUND_HOTEL_CANCEL_PERIOD,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
