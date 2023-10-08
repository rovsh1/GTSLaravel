<?php

namespace Module\Catalog\Application\Admin\Query\Quota;

use Carbon\CarbonPeriod;
use Sdk\Module\Contracts\Bus\QueryInterface;

class GetSold implements QueryInterface
{
    public function __construct(
        public readonly int $hotelId,
        public readonly CarbonPeriod $period,
        public readonly ?int $roomId = null,
    ) {}
}
