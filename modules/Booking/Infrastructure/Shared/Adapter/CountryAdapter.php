<?php

declare(strict_types=1);

namespace Module\Booking\Infrastructure\Shared\Adapter;

use Module\Booking\Application\Shared\Response\CountryDto;
use Module\Booking\Domain\Shared\Adapter\CountryAdapterInterface;
use Module\Booking\Infrastructure\Shared\Models\Country;

class CountryAdapter implements CountryAdapterInterface
{
    public function get(): array
    {
        $countries = Country::all();

        return CountryDto::collection($countries)->all();
    }
}
