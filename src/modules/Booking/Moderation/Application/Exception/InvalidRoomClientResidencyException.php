<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Exception;

use Module\Shared\Exception\ApplicationException;

final class InvalidRoomClientResidencyException extends ApplicationException
{
    protected $code = self::BOOKING_INVALID_ROOM_CLIENT_RESIDENCY;
}
