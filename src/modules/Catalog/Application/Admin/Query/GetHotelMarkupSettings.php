<?php

namespace Module\Catalog\Application\Admin\Query;

use Sdk\Module\Contracts\Bus\QueryInterface;

class GetHotelMarkupSettings implements QueryInterface
{
    public function __construct(
        public readonly int $hotelId,
    ) {}
}
