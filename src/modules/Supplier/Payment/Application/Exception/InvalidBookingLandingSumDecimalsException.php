<?php

declare(strict_types=1);

namespace Module\Supplier\Payment\Application\Exception;

use Sdk\Shared\Exception\ApplicationException;

class InvalidBookingLandingSumDecimalsException extends ApplicationException
{
    protected $code = self::LEND_BOOKING_INVALID_SUM_DECIMALS;
}
