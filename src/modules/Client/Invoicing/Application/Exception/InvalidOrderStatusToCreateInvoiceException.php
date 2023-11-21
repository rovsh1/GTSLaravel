<?php

declare(strict_types=1);

namespace Module\Client\Invoicing\Application\Exception;

use Module\Shared\Exception\ApplicationException;

class InvalidOrderStatusToCreateInvoiceException extends ApplicationException
{
    public function __construct(
        ?\Throwable $previous = null,
        ?string $message = 'Невозможно создать инвойс для заказа в текущем статусе.',
        int $code = self::INVALID_ORDER_STATUS_TO_CREATE_INVOICE,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
