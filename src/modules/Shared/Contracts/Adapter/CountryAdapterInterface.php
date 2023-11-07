<?php

declare(strict_types=1);

namespace Module\Shared\Contracts\Adapter;

use Module\Shared\Dto\CountryDto;

interface CountryAdapterInterface
{
    /**
     * @return array<int, CountryDto>
     */
    public function get(): array;
}
