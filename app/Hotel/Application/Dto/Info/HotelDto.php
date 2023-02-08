<?php

namespace GTS\Hotel\Application\Dto\Info;

use Custom\Dto\Dto;

class HotelDto extends Dto
{
    public function __construct(
        public readonly int    $id,
        public readonly string $name
    ) {}
}
