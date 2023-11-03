<?php

declare(strict_types=1);

namespace Module\Booking\Infrastructure\Adapter;

use Module\Booking\Application\Dto\ServiceBooking\AirportInfoDto;
use Module\Booking\Domain\BookingRequest\Adapter\AirportAdapterInterface;
use Module\Booking\Infrastructure\Models\Airport;

class AirportAdapter implements AirportAdapterInterface
{
    public function find(int $id): ?AirportInfoDto
    {
        $airport = Airport::find($id);
        if ($airport === null) {
            return null;
        }

        return new AirportInfoDto(
            $airport->id,
            $airport->name,
            $airport->city_id,
        );
    }
}