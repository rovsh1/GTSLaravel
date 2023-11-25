<?php

declare(strict_types=1);

namespace Module\Client\Invoicing\Application\Exception;

use Module\Shared\Exception\ApplicationException;

final class InvoiceCreatingForbiddenException extends ApplicationException
{
    protected $code = self::ORDER_INVOICE_CREATING_FORBIDDEN;
}
