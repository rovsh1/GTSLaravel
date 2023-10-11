<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\HotelBooking\Exception;

use Module\Shared\Exception\ApplicationException;

class InvalidRoomClientResidencyException extends ApplicationException
{
    public function __construct(
        ?\Throwable $previous = null,
        int $code = self::BOOKING_INVALID_ROOM_CLIENT_RESIDENCY,
        ?string $message = 'Клиент не поддерживает выбранный тип стоимости.',
    ) {
        parent::__construct($message, $code, $previous);
    }
}
