<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Dto;

final class BookingPriceDto
{
    public function __construct(
        public readonly PriceItemDto $supplierPrice,
        public readonly PriceItemDto $clientPrice,
        public readonly ProfitItemDto $profit,
    ) {}
}
