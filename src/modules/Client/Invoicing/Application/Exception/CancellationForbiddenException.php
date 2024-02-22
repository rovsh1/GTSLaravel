<?php

declare(strict_types=1);

namespace Module\Client\Invoicing\Application\Exception;

use Sdk\Shared\Exception\ApplicationException;

final class CancellationForbiddenException extends ApplicationException
{
    protected $code = self::ORDER_INVOICE_CANCELLATION_FORBIDDEN;
}
