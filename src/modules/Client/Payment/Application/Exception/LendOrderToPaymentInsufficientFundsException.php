<?php

declare(strict_types=1);

namespace Module\Client\Payment\Application\Exception;

use Sdk\Shared\Exception\ApplicationException;

class LendOrderToPaymentInsufficientFundsException extends ApplicationException
{
    protected $code = self::LEND_ORDER_TO_PAYMENT_INSUFFICIENT_FUNDS;
}
