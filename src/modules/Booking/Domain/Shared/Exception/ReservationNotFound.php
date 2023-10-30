<?php

namespace Module\Booking\Domain\Shared\Exception;

use Module\Shared\Contracts\Domain\DomainEntityExceptionInterface;
use Module\Shared\Enum\ErrorCodeEnum;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class ReservationNotFound extends EntityNotFoundException implements DomainEntityExceptionInterface
{
    public function domainCode(): ErrorCodeEnum
    {
        return ErrorCodeEnum::RESERVATION_NOT_FOUND;
    }
}
