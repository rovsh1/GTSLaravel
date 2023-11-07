<?php

declare(strict_types=1);

namespace Module\Shared\Infrastructure\Adapter;

use App\Admin\Models\Reference\City;
use Module\Shared\Contracts\Adapter\CityAdapterInterface;
use Module\Shared\Dto\CityInfoDto;

class CityAdapter implements CityAdapterInterface
{
    public function find(int $id): ?CityInfoDto
    {
        $city = City::find($id);
        if ($city === null) {
            return null;
        }

        return new CityInfoDto(
            $city->id,
            $city->name,
        );
    }
}
