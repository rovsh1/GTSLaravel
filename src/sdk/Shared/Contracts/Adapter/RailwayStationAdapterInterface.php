<?php

declare(strict_types=1);

namespace Sdk\Shared\Contracts\Adapter;

use Sdk\Shared\Dto\RailwayStationInfoDto;

interface RailwayStationAdapterInterface
{
    public function find(int $id): ?RailwayStationInfoDto;
}
