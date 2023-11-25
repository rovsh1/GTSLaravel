<?php

declare(strict_types=1);

namespace Module\Client\Invoicing\Application\Exception;

use Module\Shared\Exception\ApplicationException;

final class CancellationForbiddenException extends ApplicationException
{
    protected $code = self::ORDER_INVOICE_CANCELLATION_FORBIDDEN;
}
