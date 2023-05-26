<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Domain\Exception;

use Module\Shared\Domain\Exception\DomainEntityExceptionInterface;
use Module\Shared\Domain\Exception\ErrorCodeEnum;

class TooManyRoomGuests extends \RuntimeException implements DomainEntityExceptionInterface
{
    public function domainCode(): ErrorCodeEnum
    {
        return ErrorCodeEnum::TOO_MANY_ROOM_GUESTS;
    }
}
