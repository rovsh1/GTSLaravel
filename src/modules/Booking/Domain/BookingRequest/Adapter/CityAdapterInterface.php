<?php

declare(strict_types=1);

namespace Module\Booking\Domain\BookingRequest\Adapter;

use Module\Booking\Application\Dto\ServiceBooking\CityInfoDto;

interface CityAdapterInterface
{
    public function find(int $id): ?CityInfoDto;
}
