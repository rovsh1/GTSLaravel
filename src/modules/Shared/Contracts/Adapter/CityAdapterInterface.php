<?php

declare(strict_types=1);

namespace Module\Shared\Contracts\Adapter;

use Sdk\Shared\Dto\CityInfoDto;

interface CityAdapterInterface
{
    public function find(int $id): ?CityInfoDto;
}
