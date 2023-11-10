<?php

declare(strict_types=1);

namespace Module\Client\Invoicing\Domain\Invoice\Service\Dto\Booking;

class CarDto
{
    public function __construct(
        public readonly string $mark,
        public readonly string $model,
        public readonly int $carsCount,
    ) {}
}
