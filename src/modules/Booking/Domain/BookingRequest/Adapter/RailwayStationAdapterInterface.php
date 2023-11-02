<?php

declare(strict_types=1);

namespace Module\Booking\Domain\BookingRequest\Adapter;

use Module\Booking\Application\Dto\ServiceBooking\RailwayStationInfoDto;

interface RailwayStationAdapterInterface
{
    public function find(int $id): ?RailwayStationInfoDto;
}
