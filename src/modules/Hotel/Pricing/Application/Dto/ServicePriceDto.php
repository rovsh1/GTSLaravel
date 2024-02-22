<?php

declare(strict_types=1);

namespace Module\Hotel\Pricing\Application\Dto;

final class ServicePriceDto
{
    public function __construct(
        public readonly PriceDto $supplierPrice,
        public readonly PriceDto $clientPrice,
    ) {}
}
