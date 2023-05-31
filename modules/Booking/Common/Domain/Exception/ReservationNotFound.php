<?php

namespace Module\Booking\Common\Domain\Exception;

use Module\Shared\Domain\Exception\DomainEntityExceptionInterface;
use Module\Shared\Domain\Exception\ErrorCodeEnum;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class ReservationNotFound extends EntityNotFoundException implements DomainEntityExceptionInterface
{
    public function domainCode(): ErrorCodeEnum
    {
        return ErrorCodeEnum::RESERVATION_NOT_FOUND;
    }
}
