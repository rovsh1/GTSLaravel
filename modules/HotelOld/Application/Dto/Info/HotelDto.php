<?php

namespace Module\HotelOld\Application\Dto\Info;

use Sdk\Module\Foundation\Support\Dto\Dto;

class HotelDto extends Dto
{
    public function __construct(
        public readonly int    $id,
        public readonly string $name
    ) {}
}
