<?php

declare(strict_types=1);

namespace Module\Supplier\Application\Dto;

class CarDto
{
    public function __construct(
        public readonly int $id,
        public readonly int $typeId,
        public readonly string $mark,
        public readonly string $model,
        public readonly int $passengersNumber,
        public readonly int $bagsNumber,
    ) {}
}
