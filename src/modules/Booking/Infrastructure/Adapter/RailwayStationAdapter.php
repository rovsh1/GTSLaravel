<?php

declare(strict_types=1);

namespace Module\Booking\Infrastructure\Adapter;

use App\Admin\Models\Reference\RailwayStation;
use Module\Booking\Application\Admin\ServiceBooking\Dto\RailwayStationInfoDto;
use Module\Booking\Domain\BookingRequest\Adapter\RailwayStationAdapterInterface;

class RailwayStationAdapter implements RailwayStationAdapterInterface
{
    public function find(int $id): ?RailwayStationInfoDto
    {
        $railwayStation = RailwayStation::find($id);
        if ($railwayStation === null) {
            return null;
        }

        return new RailwayStationInfoDto(
            $railwayStation->id,
            $railwayStation->city_id,
            $railwayStation->name,
        );
    }
}
