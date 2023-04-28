<?php

declare(strict_types=1);

namespace Module\Hotel\Application\Dto;

use Custom\Framework\Foundation\Support\Dto\Dto;

class AdditionalConditionsDto extends Dto
{
    public function __construct(
        public readonly string $startTime,
        public readonly string $endTime,
        public readonly int $type,
        public readonly int $priceMarkup
    ) {}
}
