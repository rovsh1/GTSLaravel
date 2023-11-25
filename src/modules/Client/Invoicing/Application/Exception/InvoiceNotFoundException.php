<?php

declare(strict_types=1);

namespace Module\Client\Invoicing\Application\Exception;

use Module\Shared\Exception\ApplicationException;
use Sdk\Module\Foundation\Exception\NotFoundExceptionInterface;

final class InvoiceNotFoundException extends ApplicationException implements NotFoundExceptionInterface
{
    protected $code = self::ORDER_INVOICE_NOT_FOUND;
}
