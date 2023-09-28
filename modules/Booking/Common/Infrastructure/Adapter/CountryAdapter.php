<?php

declare(strict_types=1);

namespace Module\Booking\Common\Infrastructure\Adapter;

use Module\Booking\Common\Application\Response\CountryDto;
use Module\Booking\Common\Domain\Adapter\CountryAdapterInterface;
use Module\Booking\Common\Infrastructure\Models\Country;

class CountryAdapter implements CountryAdapterInterface
{
    public function get(): array
    {
        $countries = Country::all();

        return CountryDto::collection($countries)->all();
    }
}
