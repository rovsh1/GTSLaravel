<?php

declare(strict_types=1);

namespace Module\Client\Invoicing\Domain\Invoice\Adapter;

use Module\Client\Shared\Domain\ValueObject\OrderId;

interface MailGeneratorAdapterInterface
{
    public function generate(OrderId $orderId): string;
}
