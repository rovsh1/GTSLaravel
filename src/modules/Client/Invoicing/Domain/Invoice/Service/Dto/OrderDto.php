<?php

declare(strict_types=1);

namespace Module\Client\Invoicing\Domain\Invoice\Service\Dto;

class OrderDto
{
    public function __construct(
        public readonly string $number,
        public readonly string $status,
        public readonly string $currency,
    ) {}
}
