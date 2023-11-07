<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Exception;

use Module\Shared\Exception\ApplicationException;

class NotFoundHotelRoomPriceException extends ApplicationException
{
    public function __construct(
        ?\Throwable $previous = null,
        int $code = self::BOOKING_HOTEL_ROOM_PRICE_NOT_FOUND,
        ?string $message = 'В отеле не заполнены цены на период брони.',
    ) {
        parent::__construct($message, $code, $previous);
    }
}
