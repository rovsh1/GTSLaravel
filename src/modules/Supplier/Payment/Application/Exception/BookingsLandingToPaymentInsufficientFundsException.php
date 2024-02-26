<?php

declare(strict_types=1);

namespace Module\Supplier\Payment\Application\Exception;

use Sdk\Shared\Exception\ApplicationException;

class BookingsLandingToPaymentInsufficientFundsException extends ApplicationException
{
    protected $code = self::LEND_BOOKING_TO_PAYMENT_INSUFFICIENT_FUNDS;
}
