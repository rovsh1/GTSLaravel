<?php

namespace Module\Hotel\Quotation\Application\Exception;

use Sdk\Module\Foundation\Exception\NotFoundExceptionInterface;
use Sdk\Shared\Exception\ApplicationException;
use Throwable;

final class RoomNotFound extends ApplicationException implements NotFoundExceptionInterface
{
    protected $code = self::HOTEL_ROOM_NOT_FOUND;

    public function __construct(public readonly int $roomId, ?Throwable $previous = null)
    {
        parent::__construct($previous);
    }

    protected function getErrorParameters(): array
    {
        return [
            'roomId' => $this->roomId
        ];
    }
}
