<?php

declare(strict_types=1);

namespace Module\Shared\Contracts\Adapter;

use Module\Shared\Dto\AirportInfoDto;

interface AirportAdapterInterface
{
    public function find(int $id): ?AirportInfoDto;
}
