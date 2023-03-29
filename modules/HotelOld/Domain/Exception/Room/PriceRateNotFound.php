<?php

namespace Module\HotelOld\Domain\Exception\Room;

use Custom\Framework\Foundation\Exception\EntityNotFoundException;
use Module\Shared\Domain\Exception\DomainEntityExceptionInterface;
use Module\Shared\Domain\Exception\ErrorCodeEnum;

class PriceRateNotFound extends EntityNotFoundException implements DomainEntityExceptionInterface
{
    public function __construct(string $message = "", int $code = 404, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function domainCode(): ErrorCodeEnum
    {
        return ErrorCodeEnum::PriceRateNotFound;
    }
}
