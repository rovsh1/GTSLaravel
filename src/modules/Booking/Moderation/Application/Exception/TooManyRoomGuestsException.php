<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Exception;

use Sdk\Shared\Exception\ApplicationException;

final class TooManyRoomGuestsException extends ApplicationException
{
    protected $code = self::BOOKING_ROOM_TOO_MANY_GUESTS;
}
