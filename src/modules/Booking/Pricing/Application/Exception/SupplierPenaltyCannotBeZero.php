<?php

declare(strict_types=1);

namespace Module\Booking\Pricing\Application\Exception;

use Sdk\Shared\Exception\ApplicationException;

final class SupplierPenaltyCannotBeZero extends ApplicationException
{
    protected $code = self::BOOKING_SUPPLIER_PENALTY_CANNOT_BE_ZERO;
}
