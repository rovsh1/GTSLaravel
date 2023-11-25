<?php

declare(strict_types=1);

namespace Module\Shared\Infrastructure\Adapter;

use App\Admin\Models\Reference\RailwayStation;
use Sdk\Shared\Contracts\Adapter\RailwayStationAdapterInterface;
use Sdk\Shared\Dto\RailwayStationInfoDto;

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
