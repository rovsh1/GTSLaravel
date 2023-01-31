<?php

namespace GTS\Hotel\Application\Query;

use GTS\Shared\Application\Query\QueryInterface;

class GetHotelById implements QueryInterface
{
    public function __construct(
        public readonly int $id
    ) {}
}
