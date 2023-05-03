<?php

namespace Module\Hotel\Application\Query;

use Carbon\CarbonPeriod;
use Custom\Framework\Contracts\Bus\QueryInterface;

class GetHotelMarkupSettings implements QueryInterface
{
    public function __construct(
        public readonly int $hotelId,
    ) {}
}
