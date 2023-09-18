<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Application\Exception;

use Module\Shared\Application\Exception\ApplicationException;

class NotFoundHotelRoomPriceException extends ApplicationException
{
    public function __construct(
        ?\Throwable $previous = null,
        int $code = self::BOOKING_HOTEL_ROOM_PRICE_NOT_FOUND,
        ?string $message = 'Не найдены цены на период брони.',
    ) {
        parent::__construct($message, $code, $previous);
    }
}
