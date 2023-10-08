<?php

namespace Module\Hotel\Domain\Exception\Room;

use Module\Shared\Contracts\Domain\DomainEntityExceptionInterface;
use Module\Shared\Enum\ErrorCodeEnum;
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
