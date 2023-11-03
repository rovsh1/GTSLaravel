<?php

declare(strict_types=1);

namespace Module\Booking\Application\Dto;

use Sdk\Module\Foundation\Support\Dto\Dto;

class BookingPriceDto extends Dto
{
    public function __construct(
        public readonly PriceItemDto $supplierPrice,
        public readonly PriceItemDto $clientPrice,
        public readonly ProfitItemDto $profit,
    ) {}
}
