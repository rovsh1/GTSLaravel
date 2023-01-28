<?php

namespace GTS\Hotel\Application\Dto\Info;

use GTS\Shared\Application\Dto\AbstractDto;

class HotelDto extends AbstractDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $name
    ) {}
}
