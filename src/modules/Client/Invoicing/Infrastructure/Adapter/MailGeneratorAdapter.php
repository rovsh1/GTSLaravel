<?php

declare(strict_types=1);

namespace Module\Client\Invoicing\Infrastructure\Adapter;

use Module\Booking\Invoicing\Application\UseCase\GetInvoiceMailBody;
use Module\Client\Invoicing\Domain\Invoice\Adapter\MailGeneratorAdapterInterface;
use Module\Client\Shared\Domain\ValueObject\OrderId;

class MailGeneratorAdapter implements MailGeneratorAdapterInterface
{
    public function generate(OrderId $orderId): string
    {
        return app(GetInvoiceMailBody::class)->execute($orderId->value());
    }
}
