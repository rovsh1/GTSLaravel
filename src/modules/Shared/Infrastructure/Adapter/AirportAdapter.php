<?php

declare(strict_types=1);

namespace Module\Shared\Infrastructure\Adapter;

use Module\Shared\Contracts\Adapter\AirportAdapterInterface;
use Module\Shared\Dto\AirportInfoDto;
use Module\Shared\Infrastructure\Models\Airport;

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
