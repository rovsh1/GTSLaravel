<?php

declare(strict_types=1);

namespace Module\Booking\Domain\BookingRequest\Adapter;

use Module\Booking\Application\Admin\ServiceBooking\Dto\AirportInfoDto;

interface AirportAdapterInterface
{
    public function find(int $id): ?AirportInfoDto;
}
