<?php

declare(strict_types=1);

namespace Module\Supplier\Application\Response;

use Sdk\Module\Foundation\Support\Dto\Dto;

class ServicePriceDto extends Dto
{
    public function __construct(
        public readonly PriceDto $netPrice,
        public readonly PriceDto $grossPrice,
    ) {}
}
