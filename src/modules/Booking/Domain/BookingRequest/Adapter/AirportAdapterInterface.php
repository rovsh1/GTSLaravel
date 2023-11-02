<?php

declare(strict_types=1);

namespace Module\Booking\Domain\BookingRequest\Adapter;

use Module\Booking\Application\Dto\ServiceBooking\AirportInfoDto;

interface AirportAdapterInterface
{
    public function find(int $id): ?AirportInfoDto;
}
