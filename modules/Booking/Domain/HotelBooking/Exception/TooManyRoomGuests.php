<?php

declare(strict_types=1);

namespace Module\Booking\Domain\HotelBooking\Exception;

use Module\Shared\Contracts\Domain\DomainEntityExceptionInterface;
use Module\Shared\Enum\ErrorCodeEnum;

class TooManyRoomGuests extends \RuntimeException implements DomainEntityExceptionInterface
{
    public function domainCode(): ErrorCodeEnum
    {
        return ErrorCodeEnum::TOO_MANY_ROOM_GUESTS;
    }
}
