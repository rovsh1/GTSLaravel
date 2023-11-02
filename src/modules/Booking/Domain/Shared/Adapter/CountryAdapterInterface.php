<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Shared\Adapter;

use Module\Booking\Application\Dto\CountryDto;

interface CountryAdapterInterface
{
    /**
     * @return array<int, CountryDto>
     */
    public function get(): array;
}
