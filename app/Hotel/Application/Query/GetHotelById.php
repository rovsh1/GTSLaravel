<?php

namespace GTS\Hotel\Application\Query;

use Custom\Framework\Bus\QueryInterface;

class GetHotelById implements QueryInterface
{
    public function __construct(
        public readonly int $id
    ) {}
}
