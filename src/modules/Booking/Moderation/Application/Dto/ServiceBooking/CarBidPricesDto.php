<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Dto\ServiceBooking;

class CarBidPricesDto
{
    public function __construct(
        public readonly CarBidPriceItemDto $supplierPrice,
        public readonly CarBidPriceItemDto $clientPrice,
    ) {}
}
