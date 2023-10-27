<?php

declare(strict_types=1);

namespace Module\Booking\Infrastructure\Adapter;

use App\Admin\Models\Reference\City;
use Module\Booking\Application\Admin\ServiceBooking\Dto\CityInfoDto;
use Module\Booking\Domain\BookingRequest\Adapter\CityAdapterInterface;

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
