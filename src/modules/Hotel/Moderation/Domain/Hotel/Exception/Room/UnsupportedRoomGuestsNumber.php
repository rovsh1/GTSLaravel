<?php

namespace Module\Hotel\Moderation\Domain\Hotel\Exception\Room;

use Module\Shared\Contracts\Domain\DomainEntityExceptionInterface;
use Module\Shared\Enum\ErrorCodeEnum;

class UnsupportedRoomGuestsNumber extends \RuntimeException implements DomainEntityExceptionInterface
{
    public function domainCode(): ErrorCodeEnum
    {
        return ErrorCodeEnum::UNSUPPORTED_ROOM_GUESTS_NUMBER;
    }
}
