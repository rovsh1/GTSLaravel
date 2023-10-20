<?php

declare(strict_types=1);

namespace Module\Supplier\Application\Dto;

class AirportDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
    ) {}
}
