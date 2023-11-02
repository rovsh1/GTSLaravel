<?php

declare(strict_types=1);

namespace Module\Booking\Infrastructure\Adapter;

use Module\Booking\Application\Dto\CountryDto;
use Module\Booking\Domain\Shared\Adapter\CountryAdapterInterface;
use Module\Booking\Infrastructure\Models\Country;

class CountryAdapter implements CountryAdapterInterface
{
    public function get(): array
    {
        $countries = Country::all();

        return CountryDto::collection($countries)->all();
    }
}
