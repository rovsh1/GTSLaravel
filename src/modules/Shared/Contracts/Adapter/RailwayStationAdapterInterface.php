<?php

declare(strict_types=1);

namespace Module\Shared\Contracts\Adapter;

use Module\Shared\Dto\RailwayStationInfoDto;

interface RailwayStationAdapterInterface
{
    public function find(int $id): ?RailwayStationInfoDto;
}
