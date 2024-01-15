<?php

declare(strict_types=1);

namespace Shared\Support\Adapter;

use Sdk\Shared\Contracts\Adapter\AirportAdapterInterface;
use Sdk\Shared\Dto\AirportInfoDto;
use Shared\Models\Airport;

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
