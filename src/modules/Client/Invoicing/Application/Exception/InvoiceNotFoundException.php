<?php

declare(strict_types=1);

namespace Module\Client\Invoicing\Application\Exception;

use Sdk\Module\Foundation\Exception\NotFoundExceptionInterface;
use Sdk\Shared\Exception\ApplicationException;

final class InvoiceNotFoundException extends ApplicationException implements NotFoundExceptionInterface
{
    protected $code = self::ORDER_INVOICE_NOT_FOUND;
}
