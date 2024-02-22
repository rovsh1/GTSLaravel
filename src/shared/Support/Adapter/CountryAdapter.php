<?php

declare(strict_types=1);

namespace Shared\Support\Adapter;

use Sdk\Shared\Contracts\Adapter\CountryAdapterInterface;
use Sdk\Shared\Dto\CountryDto;
use Shared\Models\Country;

class CountryAdapter implements CountryAdapterInterface
{
    public function get(): array
    {
        $countries = Country::all();

        return $countries->map(fn($r) => new CountryDto($r->id, $r->name))->all();
    }
}
