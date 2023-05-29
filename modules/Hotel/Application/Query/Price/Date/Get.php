<?php

namespace Module\Hotel\Application\Query\Price\Date;

use Sdk\Module\Contracts\Bus\QueryInterface;

class Get implements QueryInterface
{
    public function __construct(
        public readonly int $seasonId,
    ) {}
}
