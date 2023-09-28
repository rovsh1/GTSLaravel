<?php

declare(strict_types=1);

namespace Module\Booking\Common\Domain\Adapter;

use Module\Booking\Common\Application\Response\CountryDto;

interface CountryAdapterInterface
{
    /**
     * @return array<int, CountryDto>
     */
    public function get(): array;
}
