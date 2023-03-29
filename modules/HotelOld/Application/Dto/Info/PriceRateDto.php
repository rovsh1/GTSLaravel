<?php

namespace Module\HotelOld\Application\Dto\Info;

use Custom\Framework\Foundation\Support\Dto\Dto;

class PriceRateDto extends Dto
{
    public function __construct(
        public readonly int    $id,
        public readonly string $name,
        public readonly string $text
    ) {}
}
