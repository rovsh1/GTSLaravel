<?php

declare(strict_types=1);

namespace Sdk\Shared\Contracts\Adapter;

use Sdk\Shared\Dto\CountryDto;

interface CountryAdapterInterface
{
    /**
     * @return array<int, CountryDto>
     */
    public function get(): array;
}
