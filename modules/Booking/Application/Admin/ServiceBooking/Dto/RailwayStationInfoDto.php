<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\ServiceBooking\Dto;

class RailwayStationInfoDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
    ) {}
}
