<?php

namespace Module\Hotel\Domain\Exception\Room;

use Module\Shared\Domain\Exception\DomainEntityExceptionInterface;
use Module\Shared\Domain\Exception\ErrorCodeEnum;

class UnsupportedRoomGuestsNumber extends \RuntimeException implements DomainEntityExceptionInterface
{
    public function domainCode(): ErrorCodeEnum
    {
        return ErrorCodeEnum::UNSUPPORTED_ROOM_GUESTS_NUMBER;
    }
}
