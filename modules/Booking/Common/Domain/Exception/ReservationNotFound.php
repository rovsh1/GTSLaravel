<?php

namespace Module\Booking\Common\Domain\Exception;

use Custom\Framework\Foundation\Exception\EntityNotFoundException;
use Module\Shared\Domain\Exception\DomainEntityExceptionInterface;
use Module\Shared\Domain\Exception\ErrorCodeEnum;

class ReservationNotFound extends EntityNotFoundException implements DomainEntityExceptionInterface
{
    public function domainCode(): ErrorCodeEnum
    {
        return ErrorCodeEnum::ReservationNotFound;
    }
}
