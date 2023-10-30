<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\ServiceBooking\Dto;

class ServiceInfoDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly int $supplierId,
    ) {}
}
