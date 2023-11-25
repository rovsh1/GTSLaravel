<?php

declare(strict_types=1);

namespace Module\Shared\Infrastructure\Adapter;

use Module\Shared\Contracts\Adapter\CountryAdapterInterface;
use Module\Shared\Infrastructure\Models\Country;
use Sdk\Shared\Dto\CountryDto;

class CountryAdapter implements CountryAdapterInterface
{
    public function get(): array
    {
        $countries = Country::all();

        return $countries->map(fn($r) => new CountryDto($r->id, $r->name))->all();
    }
}
