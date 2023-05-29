<?php

namespace Module\HotelOld\Domain\Exception\Room;

use Module\Shared\Domain\Exception\DomainEntityExceptionInterface;
use Module\Shared\Domain\Exception\ErrorCodeEnum;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class RoomNotFound extends EntityNotFoundException implements DomainEntityExceptionInterface
{
    public function __construct(string $message = "", int $code = 404, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function domainCode(): ErrorCodeEnum
    {
        return ErrorCodeEnum::ROOM_NOT_FOUND;
    }
}
