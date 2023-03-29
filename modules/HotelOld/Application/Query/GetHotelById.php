<?php

namespace Module\HotelOld\Application\Query;

use Custom\Framework\Contracts\Bus\QueryInterface;

class GetHotelById implements QueryInterface
{
    public function __construct(
        public readonly int $id
    ) {}
}
