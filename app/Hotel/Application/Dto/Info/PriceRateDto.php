<?php

namespace GTS\Hotel\Application\Dto\Info;

use GTS\Shared\Application\Dto\Dto;

class PriceRateDto extends Dto
{
    public function __construct(
        public readonly int    $id,
        public readonly string $name,
        public readonly string $text
    ) {}
}
