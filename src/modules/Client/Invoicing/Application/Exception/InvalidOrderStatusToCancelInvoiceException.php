<?php

declare(strict_types=1);

namespace Module\Client\Invoicing\Application\Exception;

use Module\Shared\Exception\ApplicationException;

class InvalidOrderStatusToCancelInvoiceException extends ApplicationException
{
    public function __construct(
        ?\Throwable $previous = null,
        ?string $message = 'Невозможно отменить инвойс для заказа в текущем статусе.',
        int $code = self::INVALID_ORDER_STATUS_TO_CANCEL_INVOICE,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
