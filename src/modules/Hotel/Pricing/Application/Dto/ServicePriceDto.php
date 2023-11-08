<?php

declare(strict_types=1);

namespace Module\Hotel\Pricing\Application\Dto;

use Sdk\Module\Foundation\Support\Dto\Dto;

class ServicePriceDto extends Dto
{
    public function __construct(
        public readonly PriceDto $supplierPrice,
        public readonly PriceDto $clientPrice,
    ) {}
}
