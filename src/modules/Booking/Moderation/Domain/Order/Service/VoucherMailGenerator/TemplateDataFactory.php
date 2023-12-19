<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Domain\Order\Service\VoucherMailGenerator;

use Sdk\Booking\ValueObject\OrderId;

class TemplateDataFactory
{
    public function build(OrderId $orderId): array
    {
        return [];
    }
}
