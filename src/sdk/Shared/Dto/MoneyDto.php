<?php

declare(strict_types=1);

namespace Sdk\Shared\Dto;

class MoneyDto
{
    public function __construct(
        public readonly CurrencyDto $currency,
        public readonly float $value,
    ) {}
}
