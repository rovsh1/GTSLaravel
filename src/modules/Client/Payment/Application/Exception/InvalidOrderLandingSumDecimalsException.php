<?php

declare(strict_types=1);

namespace Module\Client\Payment\Application\Exception;

use Sdk\Shared\Exception\ApplicationException;

class InvalidOrderLandingSumDecimalsException extends ApplicationException
{
    protected $code = self::LEND_ORDER_INVALID_SUM_DECIMALS;
}
