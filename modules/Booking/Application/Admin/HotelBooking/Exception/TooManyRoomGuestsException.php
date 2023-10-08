<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\HotelBooking\Exception;

use Module\Shared\Application\Exception\ApplicationException;

class TooManyRoomGuestsException extends ApplicationException
{
    public function __construct(
        ?\Throwable $previous = null,
        int $code = self::BOOKING_ROOM_TOO_MANY_GUESTS,
        ?string $message = 'Превышено допустимое количество гостей в номере.',
    ) {
        parent::__construct($message, $code, $previous);
    }
}
