<?php

declare(strict_types=1);

namespace Module\Booking\Common\Application\Response;

use Sdk\Module\Foundation\Support\Dto\Dto;

class BookingPriceDto extends Dto
{
    public function __construct(
        public readonly PriceItemDto $netPrice,
        public readonly PriceItemDto $grossPrice,
        public readonly PriceItemDto $profit,
    ) {}
}
